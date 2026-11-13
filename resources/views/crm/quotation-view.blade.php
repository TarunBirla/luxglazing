<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation {{ $quotation->quote_number }} – Lux Glazing UK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --lux-gold: #B89025;
            --lux-black: #0B0E14;
            --lux-dark: #121824;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background-color: #F8F9FA;
            color: #1F2937;
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding: 40px 20px;
        }
        .quote-sheet {
            max-width: 840px;
            margin: 0 auto;
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border-radius: 8px;
            padding: 48px;
        }
        .quote-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 24px;
            margin-bottom: 30px;
        }
        .brand-logo {
            font-family: 'Cinzel', serif;
            font-size: 26px;
            font-weight: 700;
            color: #0B0E14;
            letter-spacing: 2px;
        }
        .brand-sub {
            font-size: 11px;
            text-transform: uppercase;
            color: #6B7280;
            letter-spacing: 1px;
            margin-top: 4px;
        }
        .quote-title-block {
            text-align: right;
        }
        .quote-title {
            font-family: 'Cinzel', serif;
            font-size: 22px;
            color: #B89025;
            font-weight: 700;
        }
        .quote-meta-row {
            font-size: 13px;
            color: #4B5563;
            margin-top: 4px;
        }

        .parties-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 30px;
            font-size: 13px;
        }
        .party-box h4 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9CA3AF;
            margin-bottom: 8px;
        }
        .party-name {
            font-weight: 700;
            font-size: 15px;
            color: #111827;
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 13px;
        }
        th {
            background: #111827;
            color: #FFFFFF;
            text-align: left;
            padding: 12px 14px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        td {
            padding: 14px;
            border-bottom: 1px solid #E5E7EB;
        }

        .totals-block {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        .totals-table {
            width: 320px;
            font-size: 13px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #F3F4F6;
        }
        .grand-total {
            border-top: 2px solid #D4AF37;
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            padding-top: 10px;
            margin-top: 6px;
        }

        .terms-box {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            padding: 18px;
            border-radius: 6px;
            font-size: 11px;
            color: #4B5563;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .actions-bar {
            max-width: 840px;
            margin: 20px auto 0;
            display: flex;
            justify-content: space-between;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }
        .btn-gold {
            background: #D4AF37;
            color: #000;
        }
        .btn-dark {
            background: #111827;
            color: #FFF;
        }

        @media print {
            body { padding: 0; background: #FFF; }
            .quote-sheet { box-shadow: none; border: none; padding: 0; }
            .actions-bar { display: none; }
        }
    </style>
</head>
<body>

    <div class="actions-bar">
        <a href="{{ route('crm.dashboard') }}" class="btn btn-dark">← Back to CRM</a>
        <button onclick="window.print()" class="btn btn-gold">Print / Save PDF 🖨️</button>
    </div>

    <div class="quote-sheet">
        <div class="quote-header">
            <div>
                <div class="brand-logo">LUX GLAZING</div>
                <div class="brand-sub">Architectural Aluminium & Bespoke Glazing Specialists</div>
                <div style="font-size: 12px; color: #6B7280; margin-top: 8px;">
                    Registered UK Office: 45 Curzon Street, Mayfair, London W1J 7UZ<br>
                    Tel: +44 20 7946 0991 | Web: https://luxglazing.co.uk/<br>
                    FENSA Registered Installer No. 38492 | VAT Reg: GB 429 8812 04
                </div>
            </div>

            <div class="quote-title-block">
                <div class="quote-title">FORMAL PROPOSAL</div>
                <div class="quote-meta-row"><strong>Quotation Ref:</strong> {{ $quotation->quote_number }}</div>
                <div class="quote-meta-row"><strong>Date:</strong> {{ $quotation->created_at->format('d M Y') }}</div>
                <div class="quote-meta-row"><strong>Valid Until:</strong> {{ $quotation->valid_until ? \Carbon\Carbon::parse($quotation->valid_until)->format('d M Y') : '30 Days' }}</div>
            </div>
        </div>

        <div class="parties-grid">
            <div class="party-box">
                <h4>Client & Site Delivery Address</h4>
                <div class="party-name">{{ $quotation->customer_name }}</div>
                <div>{{ $quotation->site_address ?? 'Site Address as per Survey' }}</div>
                <div>Tel: {{ $quotation->customer_phone ?? 'N/A' }}</div>
                <div>Email: {{ $quotation->customer_email ?? 'N/A' }}</div>
            </div>
            <div class="party-box">
                <h4>Project Scope & Specification</h4>
                <div class="party-name">{{ $quotation->project_title }}</div>
                <div>Surveyor / Consultant: Oliver Davies</div>
                <div>Building Regs: Compliant with UK Part L (Thermal) & Part Q (Security)</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 45%;">Item & Technical Specification</th>
                    <th>Finish / RAL</th>
                    <th>Glass Specification</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Unit Price (£)</th>
                    <th style="text-align: right;">Line Total (£)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotation->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        <div style="font-size: 11px; color: #6B7280; margin-top: 4px;">{{ $item->description }}</div>
                        @if($item->width_mm && $item->height_mm)
                        <div style="font-size: 11px; color: #B89025; margin-top: 2px;">Dimensions: {{ $item->width_mm }}mm (W) × {{ $item->height_mm }}mm (H)</div>
                        @endif
                    </td>
                    <td>{{ $item->color_finish }}</td>
                    <td>{{ $item->glass_specification }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">£{{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right;"><strong>£{{ number_format($item->line_total, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-block">
            <div class="totals-table">
                <div class="totals-row">
                    <span>Net Subtotal:</span>
                    <span>£{{ number_format($quotation->subtotal, 2) }}</span>
                </div>
                <div class="totals-row">
                    <span>UK VAT ({{ $quotation->vat_rate }}%):</span>
                    <span>£{{ number_format($quotation->vat_amount, 2) }}</span>
                </div>
                <div class="totals-row grand-total">
                    <span>Total Amount (Inc. VAT):</span>
                    <span style="color: #B89025;">£{{ number_format($quotation->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="terms-box">
            <strong>Standard Terms of Supply & Installation:</strong><br>
            {!! nl2br(e($quotation->terms_conditions)) !!}
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 30px; font-size: 12px; border-top: 1px solid #E5E7EB; padding-top: 18px;">
            <div>
                <strong>Client Acceptance Signature:</strong> ___________________________
            </div>
            <div>
                <strong>Date:</strong> ___________________________
            </div>
        </div>
    </div>

</body>
</html>
