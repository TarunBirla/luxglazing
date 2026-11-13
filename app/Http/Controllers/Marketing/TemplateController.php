<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\MarketingTemplate;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = MarketingTemplate::latest()->paginate(15);
        return view('marketing.templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'category' => 'nullable|string|max:100',
        ]);

        $template = MarketingTemplate::create([
            'name' => $validated['name'],
            'subject' => $validated['subject'],
            'body_html' => $validated['body_html'],
            'category' => $validated['category'] ?? 'General',
            'variables' => ['name', 'company', 'email', 'website', 'unsubscribe_url'],
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', "Template '{$template->name}' created successfully.");
    }

    public function update(Request $request, $id)
    {
        $template = MarketingTemplate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'category' => 'nullable|string|max:100',
            'is_active' => 'required|boolean',
        ]);

        $template->update($validated);

        return redirect()->back()->with('success', 'Template updated successfully.');
    }

    public function destroy($id)
    {
        $template = MarketingTemplate::findOrFail($id);
        $template->delete();

        return redirect()->back()->with('success', 'Template deleted successfully.');
    }
}
