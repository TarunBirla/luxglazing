@extends('marketing.layout')

@section('title', 'Import Marketing Contacts')
@section('page_title', 'Bulk Contact Import & Consent Verification')

@section('content')
<div style="max-width: 760px; margin: 0 auto;">
    <div class="data-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h3 style="font-family: 'Cinzel', serif; font-size: 17px; color: var(--lux-gold);">
                    <i class="fa-solid fa-file-csv"></i> Bulk CSV Contact Import
                </h3>
                <p style="font-size: 12px; color: var(--lux-muted); margin-top: 4px;">
                    Upload permitted B2B specifier contacts. Duplicates and suppressed recipients are automatically filtered.
                </p>
            </div>
            <a href="{{ route('marketing.contacts.sample') }}" class="btn btn-dark" style="font-size: 11px;">
                <i class="fa-solid fa-download"></i> Sample CSV Template
            </a>
        </div>

        <form action="{{ route('marketing.contacts.import.process') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Select CSV File (.csv, .txt - max 10MB)</label>
                <input type="file" name="csv_file" class="form-control" accept=".csv,text/csv,text/plain" required style="padding: 10px;">
                <span style="font-size: 11px; color: var(--lux-muted); display: block; margin-top: 4px;">
                    Required columns: <code>email</code>, <code>name</code>. Optional: <code>company</code>, <code>website</code>, <code>phone</code>, <code>business_type</code>.
                </span>
            </div>

            <div class="form-group">
                <label class="form-label">Documented Consent / Permission Source</label>
                <select name="consent_source" class="form-control" required>
                    <option value="RIBA Trade Directory Opt-in">RIBA Trade Directory Opt-in</option>
                    <option value="B2B Commercial Partnership Application">B2B Commercial Partnership Application</option>
                    <option value="Direct Architectural Inquiry">Direct Architectural Inquiry</option>
                    <option value="Trade Exhibition / Event Registration">Trade Exhibition / Event Registration</option>
                    <option value="Existing Contractor Framework">Existing Contractor Framework</option>
                </select>
                <span style="font-size: 11px; color: var(--lux-gold); display: block; margin-top: 4px;">
                    <i class="fa-solid fa-scale-balanced"></i> Every imported contact will have this audit trail recorded.
                </span>
            </div>

            <div style="background: rgba(229, 210, 165, 0.08); border: 1px solid var(--lux-border); padding: 18px; border-radius: 8px; margin: 24px 0;">
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <input type="checkbox" name="confirm_permission" id="confirmPermission" value="1" required style="margin-top: 4px; accent-color: var(--lux-gold);">
                    <label for="confirmPermission" style="font-size: 12px; color: #FFFFFF; line-height: 1.5; cursor: pointer;">
                        <strong>Legal Compliance Certification:</strong> I hereby certify that the contacts in this list have provided valid, documented permission to receive commercial communications. I acknowledge that Lux Glazing enforces strict anti-spam guidelines and will automatically suppress any contact who unsubscribes or hard-bounces.
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                <a href="{{ route('marketing.contacts.index') }}" class="btn btn-dark">
                    <i class="fa-solid fa-arrow-left"></i> Back to Contacts
                </a>
                <button type="submit" class="btn btn-gold" style="padding: 12px 28px;">
                    <i class="fa-solid fa-upload"></i> Process & Import Contacts →
                </button>
            </div>
        </form>
    </div>

    <!-- Quick CSV Guidelines -->
    <div class="data-card" style="background: rgba(18, 24, 36, 0.5);">
        <h4 style="font-size: 13px; color: var(--lux-gold); margin-bottom: 10px;">
            <i class="fa-solid fa-circle-info"></i> CSV Format Guidelines
        </h4>
        <div style="font-size: 12px; color: var(--lux-muted); line-height: 1.6;">
            <ul style="padding-left: 20px; margin: 0;">
                <li>The first row of your CSV must be the column header row.</li>
                <li>Email addresses are normalized to lowercase and checked against the global suppression list.</li>
                <li>If a contact already unsubscribed in the past, they will be safely skipped to avoid spam complaints.</li>
                <li>UTF-8 encoded CSV files are recommended for special characters and British names.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
