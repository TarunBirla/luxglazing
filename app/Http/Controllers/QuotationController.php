<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Lead;

class QuotationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'nullable|string',
            'site_address' => 'nullable|string',
            'project_title' => 'required|string',
            'lead_id' => 'nullable|exists:leads,id',
            'product_name' => 'required|string',
            'description' => 'nullable|string',
            'color_finish' => 'nullable|string',
            'glass_specification' => 'nullable|string',
            'unit_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $subtotal = $validated['unit_price'] * $validated['quantity'];
        $vat = $subtotal * 0.20; // 20% UK VAT
        $total = $subtotal + $vat;

        $quoteNumber = 'LUX-' . date('Y') . '-' . str_pad(Quotation::count() + 1, 3, '0', STR_PAD_LEFT);

        $quotation = Quotation::create([
            'quote_number' => $quoteNumber,
            'lead_id' => $validated['lead_id'] ?? null,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'site_address' => $validated['site_address'],
            'project_title' => $validated['project_title'],
            'subtotal' => $subtotal,
            'vat_rate' => 20.00,
            'vat_amount' => $vat,
            'discount' => 0.00,
            'total_amount' => $total,
            'status' => 'Sent',
            'valid_until' => now()->addDays(30),
            'terms_conditions' => "1. 10-Year Comprehensive Warranty on Aluminium Frames & Double Glazing Units.\n2. UK Building Regulations Part L & Part Q Compliant.\n3. FENSA Certification and Insurance Backed Guarantee (IBG) included upon installation.",
        ]);

        QuotationItem::create([
            'quotation_id' => $quotation->id,
            'product_name' => $validated['product_name'],
            'description' => $validated['description'] ?? 'Manufactured to bespoke architectural sizes with thermal break technology.',
            'color_finish' => $validated['color_finish'] ?? 'Anthracite Grey RAL 7016 Matt',
            'glass_specification' => $validated['glass_specification'] ?? '28mm Toughened Low-E Argon Filled Glazing',
            'quantity' => $validated['quantity'],
            'unit_price' => $validated['unit_price'],
            'line_total' => $subtotal,
        ]);

        if (!empty($validated['lead_id'])) {
            $lead = Lead::find($validated['lead_id']);
            if ($lead) {
                $lead->stage = 'Quote Sent';
                $lead->estimated_value = $total;
                $lead->save();
            }
        }

        return redirect()->route('crm.quotation.view', $quotation->id)->with('success', 'Quotation ' . $quoteNumber . ' created successfully.');
    }

    public function show($id)
    {
        $quotation = Quotation::with('items')->findOrFail($id);
        return view('crm.quotation-view', compact('quotation'));
    }
}
