<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\MarketingContact;
use App\Models\MarketingSuppression;
use App\Models\MarketingConsentLog;
use App\Services\Marketing\ContactImportService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected ContactImportService $importService;

    public function __construct(ContactImportService $importService)
    {
        $this->importService = $importService;
    }

    public function index(Request $request)
    {
        $query = MarketingContact::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        if ($type = $request->input('business_type')) {
            $query->where('business_type', $type);
        }

        if ($status = $request->input('status')) {
            $query->where('subscription_status', $status);
        }

        if ($request->has('consent_only') && $request->input('consent_only') === '1') {
            $query->where('consent_status', true);
        }

        $contacts = $query->latest()->paginate(25)->withQueryString();

        $businessTypes = MarketingContact::select('business_type')
            ->whereNotNull('business_type')
            ->distinct()
            ->pluck('business_type');

        return view('marketing.contacts.index', compact('contacts', 'businessTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:marketing_contacts,email',
            'company' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'business_type' => 'nullable|string|max:100',
            'consent_source' => 'required|string|max:100',
            'consent_status' => 'required|boolean',
        ]);

        $normalizedEmail = strtolower(trim($validated['email']));

        // Check central suppression list
        if (MarketingSuppression::isSuppressed($normalizedEmail)) {
            return back()->withErrors(['email' => 'This email address is on the global suppression list (previously unsubscribed or hard-bounced). Cannot add without direct legal consent.']);
        }

        $contact = MarketingContact::create(array_merge($validated, [
            'email' => $normalizedEmail,
            'verification_status' => 'verified',
            'subscription_status' => 'subscribed',
            'consent_date' => now(),
            'consent_ip' => $request->ip(),
        ]));

        MarketingConsentLog::create([
            'contact_id' => $contact->id,
            'email' => $normalizedEmail,
            'action' => 'opt_in',
            'consent_source' => $validated['consent_source'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'notes' => 'Created via Admin Panel by ' . auth()->user()->name,
        ]);

        return redirect()->route('marketing.contacts.index')->with('success', "Contact {$contact->name} added successfully with documented marketing consent.");
    }

    public function update(Request $request, $id)
    {
        $contact = MarketingContact::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'business_type' => 'nullable|string|max:100',
            'subscription_status' => 'required|string',
            'consent_status' => 'required|boolean',
        ]);

        $contact->update($validated);

        if ($validated['subscription_status'] === 'unsubscribed') {
            MarketingSuppression::suppress($contact->email, 'unsubscribe', 'Admin Manual Update');
        }

        return redirect()->back()->with('success', 'Contact updated successfully.');
    }

    public function destroy($id)
    {
        $contact = MarketingContact::findOrFail($id);
        $contact->delete();

        return redirect()->back()->with('success', 'Contact removed from active marketing directory.');
    }

    public function importView()
    {
        return view('marketing.contacts.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
            'consent_source' => 'required|string|max:100',
            'confirm_permission' => 'accepted',
        ], [
            'confirm_permission.accepted' => 'You must certify that all contacts in this list have provided documented marketing permission.',
        ]);

        $result = $this->importService->importCsv(
            $request->file('csv_file'),
            $request->input('consent_source', 'CSV Trade Import'),
            true,
            $request->ip()
        );

        if (!$result['success']) {
            return back()->withErrors($result['errors']);
        }

        $msg = "Import completed: {$result['imported']} contacts successfully added/updated. {$result['skipped']} skipped ({$result['suppressed_skipped']} suppressed/unsubscribed).";
        return redirect()->route('marketing.contacts.index')->with('success', $msg);
    }

    public function downloadSample()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="marketing_contacts_sample.csv"',
        ];

        $columns = ['name', 'email', 'company', 'website', 'phone', 'business_type'];
        $sampleData = [
            ['Oliver Henderson', 'oliver@hendersonarchitects.co.uk', 'Henderson Architects RIBA', 'https://hendersonarchitects.co.uk', '+44 20 7946 0111', 'Architect'],
            ['Sarah Jenkins', 'sjenkins@berkeleyhomes.co.uk', 'Berkeley Homes London', 'https://berkeleygroup.co.uk', '+44 20 7946 0222', 'Property Developer'],
            ['James Cunningham', 'procurement@octagon.co.uk', 'Octagon Bespoke Developments', 'https://octagon.co.uk', '+44 1932 280000', 'Bespoke Builder'],
        ];

        $callback = function () use ($columns, $sampleData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
