@extends('marketing.layout')

@section('title', 'AI Email Writer')
@section('page_title', 'OpenAI Personalized B2B Email Writer')

@section('content')
<div class="data-card">
    <div style="margin-bottom: 20px;">
        <h3 style="font-family: 'Cinzel', serif; font-size: 18px; color: var(--lux-gold);">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Autonomous AI B2B Copywriter
        </h3>
        <p style="font-size: 12px; color: var(--lux-muted); margin-top: 4px;">
            Generates ~100-word highly personalized, professional British English B2B introduction emails with clear call to actions using OpenAI ({{ config('services.openai.model', 'gpt-4o') }}).
        </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 28px;">
        <!-- Left: Input Controls -->
        <div>
            <div style="background: rgba(18, 24, 36, 0.6); padding: 18px; border-radius: 8px; border: 1px solid var(--lux-border-light); margin-bottom: 20px;">
                <label class="form-label">Auto-Fill from Existing Contact (Optional)</label>
                <select id="quickFillContact" class="form-control" onchange="autoFillContact(this.value)">
                    <option value="">-- Choose Existing Contact --</option>
                    @foreach($contacts as $c)
                        <option value="{{ $c->id }}" 
                            data-name="{{ $c->name }}" 
                            data-company="{{ $c->company }}" 
                            data-type="{{ $c->business_type }}" 
                            data-website="{{ $c->website }}">
                            {{ $c->name }} ({{ $c->company ?: $c->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Recipient Full Name</label>
                <input type="text" id="aiContactName" class="form-control" placeholder="e.g. Elena Rostova" value="Oliver Henderson">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Company / Practice</label>
                    <input type="text" id="aiCompany" class="form-control" placeholder="Studio Indigo Architects" value="Henderson Architects RIBA">
                </div>
                <div class="form-group">
                    <label class="form-label">Business Type</label>
                    <input type="text" id="aiBusinessType" class="form-control" placeholder="Architect / Builder" value="Architect">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Company Website</label>
                <input type="text" id="aiWebsite" class="form-control" placeholder="https://practice.co.uk" value="https://henderson-architects.co.uk">
            </div>

            <div class="form-group">
                <label class="form-label">Campaign Objective / Purpose</label>
                <input type="text" id="aiPurpose" class="form-control" placeholder="Introduce Cortizo Cor Vision Plus minimal sliding doors" value="Present Cortizo Cor Vision Plus 20mm sightlines and bespoke trade discounts for London residential extensions.">
            </div>

            <div class="form-group">
                <label class="form-label">Product / Value Highlights</label>
                <textarea id="aiProductDetails" class="form-control" rows="4" placeholder="Highlight key specifications and FENSA installation guarantees.">Ultra-slim Cortizo Cor Vision Plus sliding doors with 20mm minimalist sightlines, low U-value (1.1 W/m²K) compliant with UK Part L, integrated flush thresholds, and turnkey FENSA installation.</textarea>
            </div>

            <button type="button" class="btn btn-gold" id="btnGenerate" onclick="generateEmail()" style="width: 100%; padding: 12px; font-size: 14px;">
                <i class="fa-solid fa-wand-magic-sparkles"></i> Generate Personalized Email with OpenAI
            </button>
        </div>

        <!-- Right: Output Editor & Actions -->
        <div>
            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label class="form-label" style="margin-bottom: 0;">Generated Subject Line</label>
                    <span id="tokenBadge" class="badge badge-gold" style="display: none;">Tokens: <span id="tokenCount">0</span></span>
                </div>
                <input type="text" id="outSubject" class="form-control" placeholder="Generated subject line will appear here...">
            </div>

            <div class="form-group">
                <label class="form-label">Generated HTML Content (Editable)</label>
                <textarea id="outHtml" class="form-control" rows="12" placeholder="Generated email body will appear here..."></textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                <button type="button" class="btn btn-dark" onclick="copyEmailContent()">
                    <i class="fa-solid fa-copy"></i> Copy to Clipboard
                </button>
                <button type="button" class="btn btn-gold" onclick="openSaveTemplateModal()">
                    <i class="fa-solid fa-bookmark"></i> Save as Reusable Template
                </button>
            </div>

            <!-- Preview Card -->
            <div style="background: rgba(0,0,0,0.4); border: 1px solid var(--lux-border-light); padding: 16px; border-radius: 8px;">
                <span style="font-size: 11px; text-transform: uppercase; color: var(--lux-gold); font-weight: 700; display: block; margin-bottom: 8px;">
                    <i class="fa-solid fa-eye"></i> Live Text Preview
                </span>
                <div id="livePreview" style="font-size: 13px; color: #E5E7EB; line-height: 1.5; min-height: 80px;">
                    <em>Your AI generated copy preview will render here.</em>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Save as Template -->
<div id="saveTemplateModal" class="modal">
    <div class="modal-content">
        <h3 style="font-family: 'Cinzel', serif; color: var(--lux-gold); margin-bottom: 16px;">
            <i class="fa-solid fa-bookmark"></i> Save AI Copy as Template
        </h3>
        <form action="{{ route('marketing.ai-writer.save-template') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Template Name</label>
                <input type="text" name="name" class="form-control" required placeholder="e.g. Cortizo RIBA Architect Introduction">
            </div>
            <div class="form-group">
                <label class="form-label">Subject Line</label>
                <input type="text" name="subject" id="saveSubject" class="form-control" required>
            </div>
            <input type="hidden" name="body_html" id="saveBodyHtml">
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 18px;">
                <button type="button" class="btn btn-dark" onclick="closeModal('saveTemplateModal')">Cancel</button>
                <button type="submit" class="btn btn-gold">Save Template</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function autoFillContact(id) {
        if (!id) return;
        const sel = document.getElementById('quickFillContact');
        const opt = sel.options[sel.selectedIndex];
        document.getElementById('aiContactName').value = opt.getAttribute('data-name') || '';
        document.getElementById('aiCompany').value = opt.getAttribute('data-company') || '';
        document.getElementById('aiBusinessType').value = opt.getAttribute('data-type') || '';
        document.getElementById('aiWebsite').value = opt.getAttribute('data-website') || '';
    }

    function generateEmail() {
        const btn = document.getElementById('btnGenerate');
        const origText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating with OpenAI...';
        btn.disabled = true;

        const payload = {
            contact_name: document.getElementById('aiContactName').value,
            company_name: document.getElementById('aiCompany').value,
            business_type: document.getElementById('aiBusinessType').value,
            website: document.getElementById('aiWebsite').value,
            purpose: document.getElementById('aiPurpose').value,
            product_details: document.getElementById('aiProductDetails').value,
        };

        fetch('{{ route("marketing.ai-writer.generate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            btn.innerHTML = origText;
            btn.disabled = false;
            if (data.success) {
                document.getElementById('outSubject').value = data.subject;
                document.getElementById('outHtml').value = data.html;
                document.getElementById('livePreview').innerHTML = data.html;
                document.getElementById('tokenCount').innerText = data.tokens;
                document.getElementById('tokenBadge').style.display = 'inline-block';
            }
        })
        .catch(err => {
            btn.innerHTML = origText;
            btn.disabled = false;
            alert('Generation error: ' + err);
        });
    }

    function copyEmailContent() {
        const subj = document.getElementById('outSubject').value;
        const body = document.getElementById('outHtml').value;
        navigator.clipboard.writeText(subj + "\n\n" + body);
        alert('Email subject and body copied to clipboard!');
    }

    function openSaveTemplateModal() {
        const subj = document.getElementById('outSubject').value;
        const body = document.getElementById('outHtml').value;
        if (!subj || !body) {
            alert('Please generate or write email content first.');
            return;
        }
        document.getElementById('saveSubject').value = subj;
        document.getElementById('saveBodyHtml').value = body;
        openModal('saveTemplateModal');
    }

    document.getElementById('outHtml').addEventListener('input', function() {
        document.getElementById('livePreview').innerHTML = this.value;
    });
</script>
@endpush
@endsection
