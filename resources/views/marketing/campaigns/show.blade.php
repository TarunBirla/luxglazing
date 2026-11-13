@extends('marketing.layout')

@section('title', $campaign->name . ' – Report')
@section('page_title', 'Campaign Hub: ' . $campaign->name)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h2 style="font-family: 'Cinzel', serif; font-size: 20px; color: #FFF;">{{ $campaign->name }}</h2>
        <span style="font-size: 12px; color: var(--lux-muted);">Subject: {{ $campaign->subject }}</span>
    </div>

    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <a href="{{ route('marketing.campaigns.preview', $campaign->id) }}" class="btn btn-dark">
            <i class="fa-solid fa-eye"></i> Preview Email
        </a>

        <button type="button" class="btn btn-dark" onclick="openModal('testEmailModal')">
            <i class="fa-solid fa-paper-plane"></i> Send Test Email
        </button>

        @if($campaign->status === 'draft' || $campaign->status === 'scheduled')
            <form action="{{ route('marketing.campaigns.launch', $campaign->id) }}" method="POST" onsubmit="return confirm('Launch this campaign to all permitted contacts via Redis Queue?')">
                @csrf
                <button type="submit" class="btn btn-gold">
                    <i class="fa-solid fa-rocket"></i> Launch Campaign
                </button>
            </form>
        @elseif($campaign->status === 'sending' || $campaign->status === 'processing')
            <form action="{{ route('marketing.campaigns.pause', $campaign->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="fa-solid fa-pause"></i> Pause Delivery
                </button>
            </form>
        @elseif($campaign->status === 'paused')
            <form action="{{ route('marketing.campaigns.resume', $campaign->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-gold">
                    <i class="fa-solid fa-play"></i> Resume Delivery
                </button>
            </form>
        @endif

        <form action="{{ route('marketing.campaigns.duplicate', $campaign->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-dark" title="Duplicate Campaign">
                <i class="fa-solid fa-copy"></i>
            </button>
        </form>
    </div>
</div>

<!-- Metrics Row -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 14px; margin-bottom: 24px;">
    <div class="data-card" style="margin-bottom: 0; padding: 16px;">
        <div style="font-size: 11px; text-transform: uppercase; color: var(--lux-muted);">Recipients</div>
        <div style="font-size: 22px; font-weight: 700; color: #FFF;">{{ number_format($campaign->total_contacts) }}</div>
        <div style="font-size: 11px; color: var(--lux-gold);">Targeted Contacts</div>
    </div>

    <div class="data-card" style="margin-bottom: 0; padding: 16px;">
        <div style="font-size: 11px; text-transform: uppercase; color: var(--lux-muted);">Delivered (SES)</div>
        <div style="font-size: 22px; font-weight: 700; color: #6EE7B7;">{{ number_format($campaign->emails_delivered) }}</div>
        <div style="font-size: 11px; color: #9CA3AF;">{{ $campaign->emails_sent }} Sent</div>
    </div>

    <div class="data-card" style="margin-bottom: 0; padding: 16px;">
        <div style="font-size: 11px; text-transform: uppercase; color: var(--lux-muted);">Open Rate</div>
        <div style="font-size: 22px; font-weight: 700; color: #93C5FD;">{{ $campaign->open_rate }}%</div>
        <div style="font-size: 11px; color: #9CA3AF;">{{ number_format($campaign->emails_opened) }} Opens</div>
    </div>

    <div class="data-card" style="margin-bottom: 0; padding: 16px;">
        <div style="font-size: 11px; text-transform: uppercase; color: var(--lux-muted);">Click Rate</div>
        <div style="font-size: 22px; font-weight: 700; color: #D8B4FE;">{{ $campaign->click_rate }}%</div>
        <div style="font-size: 11px; color: #9CA3AF;">{{ number_format($campaign->emails_clicked) }} Clicks</div>
    </div>

    <div class="data-card" style="margin-bottom: 0; padding: 16px;">
        <div style="font-size: 11px; text-transform: uppercase; color: var(--lux-muted);">Bounced</div>
        <div style="font-size: 22px; font-weight: 700; color: #FCD34D;">{{ $campaign->bounce_rate }}%</div>
        <div style="font-size: 11px; color: #9CA3AF;">{{ $campaign->emails_bounced }} Bounced</div>
    </div>

    <div class="data-card" style="margin-bottom: 0; padding: 16px;">
        <div style="font-size: 11px; text-transform: uppercase; color: var(--lux-muted);">OpenAI Cost</div>
        <div style="font-size: 22px; font-weight: 700; color: var(--lux-gold);">${{ number_format($campaign->openai_cost_usd, 4) }}</div>
        <div style="font-size: 11px; color: #9CA3AF;">{{ number_format($campaign->openai_tokens_used) }} Tokens</div>
    </div>
</div>

<!-- Details & Breakdown -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 18px; margin-bottom: 24px;">
    <div class="data-card" style="margin-bottom: 0;">
        <h4 style="font-size: 13px; color: var(--lux-gold); margin-bottom: 12px;"><i class="fa-solid fa-circle-info"></i> Campaign Specifications</h4>
        <div style="font-size: 12px; line-height: 1.6; color: var(--lux-text);">
            <div><strong>Sending Mode:</strong> <span class="badge badge-gold">{{ strtoupper($campaign->sending_mode) }}</span></div>
            <div style="margin-top: 6px;"><strong>Sender:</strong> {{ $campaign->sender_name }} &lt;{{ $campaign->sender_email }}&gt;</div>
            <div style="margin-top: 6px;"><strong>Objective:</strong> {{ $campaign->purpose }}</div>
            <div style="margin-top: 6px;"><strong>Scheduled:</strong> {{ $campaign->scheduled_at ? $campaign->scheduled_at->format('d M Y H:i') : 'Manual Dispatch' }}</div>
            <div style="margin-top: 6px;"><strong>Status:</strong> <span class="badge badge-success">{{ strtoupper($campaign->status) }}</span></div>
        </div>
    </div>

    <div class="data-card" style="margin-bottom: 0;">
        <h4 style="font-size: 13px; color: var(--lux-gold); margin-bottom: 12px;"><i class="fa-solid fa-chart-pie"></i> Delivery Status Breakdown</h4>
        <div style="display: flex; flex-direction: column; gap: 8px; font-size: 12px;">
            <div style="display: flex; justify-content: space-between;">
                <span>Delivered Successfully:</span>
                <strong style="color: #6EE7B7;">{{ $statusBreakdown['delivered'] ?? 0 }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Dispatched / In-Transit:</span>
                <strong style="color: #93C5FD;">{{ $statusBreakdown['sent'] ?? 0 }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Queued in Redis:</span>
                <strong>{{ $statusBreakdown['queued'] ?? 0 }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Hard Bounced (Auto-Suppressed):</span>
                <strong style="color: #FCD34D;">{{ $statusBreakdown['bounced'] ?? 0 }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Spam Complaints:</span>
                <strong style="color: #FCA5A5;">{{ $statusBreakdown['complained'] ?? 0 }}</strong>
            </div>
        </div>
    </div>
</div>

<!-- Recipients Log Table -->
<div class="data-card">
    <h3 style="font-family: 'Cinzel', serif; font-size: 16px; color: var(--lux-gold); margin-bottom: 16px;">
        <i class="fa-solid fa-users"></i> Recent Dispatches & Recipient Status
    </h3>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Recipient</th>
                    <th>Status</th>
                    <th>Mode</th>
                    <th>SES Message ID</th>
                    <th>Delivered At</th>
                    <th>Opened At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaign->emails as $em)
                <tr>
                    <td>
                        <strong>{{ $em->recipient_name }}</strong><br>
                        <span style="font-size: 11px; color: var(--lux-muted);">{{ $em->recipient_email }}</span>
                    </td>
                    <td>
                        @if($em->status === 'delivered')
                            <span class="badge badge-success"><i class="fa-solid fa-check"></i> DELIVERED</span>
                        @elseif($em->status === 'opened')
                            <span class="badge" style="background: rgba(59, 130, 246, 0.15); color: #93C5FD;"><i class="fa-solid fa-envelope-open"></i> OPENED</span>
                        @elseif($em->status === 'clicked')
                            <span class="badge" style="background: rgba(168, 85, 247, 0.15); color: #D8B4FE;"><i class="fa-solid fa-mouse-pointer"></i> CLICKED</span>
                        @elseif($em->status === 'sent')
                            <span class="badge badge-gold">SENT</span>
                        @elseif($em->status === 'bounced')
                            <span class="badge badge-warning">BOUNCED</span>
                        @elseif($em->status === 'suppressed')
                            <span class="badge badge-danger">SUPPRESSED</span>
                        @else
                            <span class="badge badge-gold">{{ strtoupper($em->status) }}</span>
                        @endif
                    </td>
                    <td><span style="font-size: 11px; color: var(--lux-muted);">{{ strtoupper($em->generation_mode) }}</span></td>
                    <td><code style="font-size: 11px; color: var(--lux-gold);">{{ Str::limit($em->ses_message_id, 24) }}</code></td>
                    <td>{{ $em->delivered_at ? $em->delivered_at->format('d M H:i') : ($em->sent_at ? $em->sent_at->format('d M H:i') : '-') }}</td>
                    <td>{{ $em->opened_at ? $em->opened_at->format('d M H:i') : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align: center; color: var(--lux-muted);">No emails dispatched yet for this campaign.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Send Test Email -->
<div id="testEmailModal" class="modal">
    <div class="modal-content">
        <h3 style="font-family: 'Cinzel', serif; color: var(--lux-gold); margin-bottom: 16px;">
            <i class="fa-solid fa-paper-plane"></i> Send Test Verification Email
        </h3>
        <p style="font-size: 12px; color: var(--lux-muted); margin-bottom: 16px;">
            A live test copy with RFC 8058 headers and Amazon SES configuration set will be sent immediately to this address.
        </p>

        <form action="{{ route('marketing.campaigns.test-email', $campaign->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Destination Test Email</label>
                <input type="email" name="test_email" class="form-control" required value="{{ auth()->user()->email }}">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 18px;">
                <button type="button" class="btn btn-dark" onclick="closeModal('testEmailModal')">Cancel</button>
                <button type="submit" class="btn btn-gold"><i class="fa-solid fa-paper-plane"></i> Send via Amazon SES</button>
            </div>
        </form>
    </div>
</div>
@endsection
