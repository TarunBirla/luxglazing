@extends('marketing.layout')

@section('title', 'Email Preview – ' . $campaign->name)
@section('page_title', 'Live Rendered Email Preview')

@section('content')
<div style="max-width: 780px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h3 style="font-family: 'Cinzel', serif; font-size: 18px; color: var(--lux-gold);">
                <i class="fa-solid fa-eye"></i> Email Render Preview
            </h3>
            <span style="font-size: 12px; color: var(--lux-muted);">
                Simulating dynamic variable replacement for sample contact: <strong>{{ $sampleContact->name }} ({{ $sampleContact->company }})</strong>
            </span>
        </div>
        <a href="{{ route('marketing.campaigns.show', $campaign->id) }}" class="btn btn-dark">
            <i class="fa-solid fa-arrow-left"></i> Back to Campaign
        </a>
    </div>

    <!-- Client Email Client Simulator Card -->
    <div style="background: #FFFFFF; color: #111827; border-radius: 12px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.4); border: 1px solid #E5E7EB;">
        <!-- Email Client Top Header -->
        <div style="background: #F3F4F6; padding: 18px 24px; border-bottom: 1px solid #E5E7EB; font-size: 13px;">
            <div style="margin-bottom: 6px;">
                <span style="color: #6B7280; font-weight: 600; width: 80px; display: inline-block;">From:</span>
                <strong>{{ $campaign->sender_name ?: 'Lux Glazing Architectural Team' }}</strong> &lt;{{ $campaign->sender_email ?: 'marketing@marketing.nimadtech.in' }}&gt;
            </div>
            <div style="margin-bottom: 6px;">
                <span style="color: #6B7280; font-weight: 600; width: 80px; display: inline-block;">To:</span>
                <span>{{ $sampleContact->name }} &lt;{{ $sampleContact->email }}&gt;</span>
            </div>
            <div>
                <span style="color: #6B7280; font-weight: 600; width: 80px; display: inline-block;">Subject:</span>
                <strong style="color: #111827; font-size: 14px;">{{ $subject }}</strong>
            </div>
        </div>

        <!-- Email Body -->
        <div style="padding: 32px 30px; font-size: 14px; line-height: 1.6; color: #1F2937;">
            {!! $html !!}

            <!-- Compliance Footer -->
            <div style="margin-top: 36px; padding-top: 18px; border-top: 1px solid #E5D2A5; font-size: 11px; color: #6B7280; line-height: 1.5;">
                <p style="margin: 0 0 6px 0;">
                    You received this commercial update from <strong>Lux Glazing UK</strong> because of your documented trade or architectural interest.
                </p>
                <p style="margin: 0 0 8px 0;">
                    Registered UK Office: 45 Curzon Street, Mayfair, London W1J 7UZ | FENSA Registered No. 38492
                </p>
                <p style="margin: 0;">
                    If you no longer wish to receive our architectural updates and project showcases, you can 
                    <a href="{{ $sampleContact->unsubscribe_url }}" style="color: #B89025; text-decoration: underline;">unsubscribe instantly</a>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
