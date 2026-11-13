@extends('marketing.layout')

@section('title', 'Create Marketing Campaign')
@section('page_title', 'Configure New Marketing Campaign')

@section('content')
<div style="max-width: 880px; margin: 0 auto;">
    <div class="data-card">
        <h3 style="font-family: 'Cinzel', serif; font-size: 18px; color: var(--lux-gold); margin-bottom: 20px;">
            <i class="fa-solid fa-plus-circle"></i> Create Marketing Campaign
        </h3>

        <form action="{{ route('marketing.campaigns.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Campaign Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Autumn 2026 Architect Showcase" value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Sending Mode</label>
                    <select name="sending_mode" id="sendingMode" class="form-control" required onchange="toggleModeFields()">
                        <option value="ai_generated" {{ old('sending_mode') === 'ai_generated' ? 'selected' : '' }}>AI Mode (100% Unique Personalized Copy via OpenAI)</option>
                        <option value="template" {{ old('sending_mode') === 'template' ? 'selected' : '' }}>Template Mode (Static Template with Variables)</option>
                        <option value="both" {{ old('sending_mode') === 'both' ? 'selected' : '' }}>Both (AI Draft Based on Template Structure)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Campaign Purpose / Objective</label>
                <input type="text" name="purpose" class="form-control" required placeholder="e.g. Introduce Cortizo Cor Vision Plus to London RIBA practices" value="{{ old('purpose') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Email Subject Line</label>
                <input type="text" name="subject" class="form-control" required placeholder="e.g. Architectural Specification: Bespoke Slimline Sliding Doors" value="{{ old('subject') }}">
            </div>

            <div class="form-group" id="templateSelectGroup" style="display: none;">
                <label class="form-label">Select Reusable Template</label>
                <select name="template_id" class="form-control">
                    <option value="">-- Choose Template --</option>
                    @foreach($templates as $tpl)
                        <option value="{{ $tpl->id }}">{{ $tpl->name }} ({{ $tpl->category }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Product / Service Guidance & Key Value Points</label>
                <textarea name="content" class="form-control" rows="5" placeholder="Highlight key product specs: 20mm sightlines, Part L compliant U-values, acoustic laminated glass, turnkey in-house installation and FENSA warranty.">{{ old('content') }}</textarea>
                <span style="font-size: 11px; color: var(--lux-muted);">
                    In AI Mode, OpenAI uses these points to generate concise, tailored 100-word business letters for each contact.
                </span>
            </div>

            <div style="background: rgba(18, 24, 36, 0.6); border: 1px solid var(--lux-border-light); padding: 18px; border-radius: 8px; margin: 20px 0;">
                <h4 style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.8px; color: var(--lux-gold); margin-bottom: 12px;">
                    <i class="fa-solid fa-bullseye"></i> Audience Filtering & Senders
                </h4>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Target Business Type</label>
                        <select name="target_business_type" class="form-control">
                            <option value="">All Permitted Contacts ({{ $eligibleContactsCount }})</option>
                            @foreach($businessTypes as $bt)
                                <option value="{{ $bt }}">{{ $bt }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Sender Name</label>
                        <input type="text" name="sender_name" class="form-control" value="Lux Glazing Architectural Team">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Sender Email (Verified Domain)</label>
                        <input type="email" name="sender_email" class="form-control" value="marketing@{{ config('services.ses.sending_domain', 'marketing.nimadtech.in') }}">
                    </div>
                </div>
            </div>

            <div style="background: rgba(18, 24, 36, 0.6); border: 1px solid var(--lux-border-light); padding: 18px; border-radius: 8px; margin: 20px 0;">
                <h4 style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.8px; color: var(--lux-gold); margin-bottom: 12px;">
                    <i class="fa-solid fa-clock"></i> Schedule Sending (Optional)
                </h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Sending Date</label>
                        <input type="date" name="scheduled_date" class="form-control" min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Sending Time (UK / Server Time)</label>
                        <input type="time" name="scheduled_time" class="form-control" value="09:30">
                    </div>
                </div>
                <span style="font-size: 11px; color: var(--lux-muted); display: block; margin-top: 6px;">
                    Leave blank to save as a draft or trigger manual immediate batch queueing.
                </span>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px;">
                <a href="{{ route('marketing.campaigns.index') }}" class="btn btn-dark">
                    <i class="fa-solid fa-arrow-left"></i> Back to Campaigns
                </a>
                <button type="submit" class="btn btn-gold" style="padding: 12px 28px;">
                    <i class="fa-solid fa-floppy-disk"></i> Save & Continue to Campaign Hub →
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleModeFields() {
        const mode = document.getElementById('sendingMode').value;
        const group = document.getElementById('templateSelectGroup');
        if (mode === 'template' || mode === 'both') {
            group.style.display = 'block';
        } else {
            group.style.display = 'none';
        }
    }
    toggleModeFields();
</script>
@endpush
@endsection
