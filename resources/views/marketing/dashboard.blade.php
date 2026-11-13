@extends('marketing.layout')

@section('title', 'Marketing Dashboard & Analytics')
@section('page_title', 'Marketing Engine & SES Analytics')

@section('content')
<style>
    .metrics-grid-marketing {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .m-card {
        background: var(--lux-card);
        border: 1px solid var(--lux-border-light);
        border-radius: 12px;
        padding: 18px 20px;
        position: relative;
        overflow: hidden;
        transition: all 0.2s;
    }
    .m-card:hover {
        border-color: var(--lux-border);
        transform: translateY(-2px);
    }
    .m-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 4px; height: 100%;
        background: var(--lux-gold);
    }
    .m-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--lux-muted);
        margin-bottom: 6px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
    }
    .m-val {
        font-size: 24px;
        font-weight: 700;
        color: #FFFFFF;
        margin-bottom: 4px;
    }
    .m-sub {
        font-size: 11px;
        color: var(--lux-gold);
    }
    .filter-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }
</style>

<div class="filter-bar">
    <div>
        <h2 style="font-family: 'Cinzel', serif; font-size: 18px; color: var(--lux-gold-light);">Performance Overview</h2>
        <span style="font-size: 12px; color: var(--lux-muted);">Multi-channel AWS SES delivery & OpenAI marketing attribution</span>
    </div>

    <!-- Date Filters -->
    <form method="GET" action="{{ route('marketing.dashboard') }}" style="display: flex; gap: 8px;">
        <select name="range" class="form-control" style="width: 160px; padding: 6px 12px; font-size: 12px;" onchange="this.form.submit()">
            <option value="7_days" {{ $range === '7_days' ? 'selected' : '' }}>Last 7 Days</option>
            <option value="30_days" {{ $range === '30_days' ? 'selected' : '' }}>Last 30 Days</option>
            <option value="90_days" {{ $range === '90_days' ? 'selected' : '' }}>Last 90 Days</option>
            <option value="all" {{ $range === 'all' ? 'selected' : '' }}>All Time</option>
        </select>
    </form>
</div>

<!-- 1. Top Row: Contact & Campaign Metrics -->
<div class="metrics-grid-marketing">
    <div class="m-card">
        <div class="m-label">
            <span>Total Contacts</span>
            <i class="fa-solid fa-address-book" style="color: var(--lux-gold);"></i>
        </div>
        <div class="m-val">{{ number_format($totalContacts) }}</div>
        <div class="m-sub">{{ number_format($subscribedContacts) }} Subscribed ({{ $totalContacts > 0 ? round(($subscribedContacts / $totalContacts) * 100, 1) : 0 }}%)</div>
    </div>

    <div class="m-card">
        <div class="m-label">
            <span>Active Campaigns</span>
            <i class="fa-solid fa-bullhorn" style="color: var(--lux-gold);"></i>
        </div>
        <div class="m-val">{{ number_format($totalCampaigns) }}</div>
        <div class="m-sub">Redis Queued Processing</div>
    </div>

    <div class="m-card">
        <div class="m-label">
            <span>Emails Generated (AI)</span>
            <i class="fa-solid fa-wand-magic-sparkles" style="color: var(--lux-gold);"></i>
        </div>
        <div class="m-val">{{ number_format($emailsGenerated) }}</div>
        <div class="m-sub">{{ number_format($emailsQueued) }} Queued in Redis</div>
    </div>

    <div class="m-card">
        <div class="m-label">
            <span>Emails Sent (SES)</span>
            <i class="fa-solid fa-paper-plane" style="color: var(--lux-gold);"></i>
        </div>
        <div class="m-val">{{ number_format($emailsSent) }}</div>
        <div class="m-sub">{{ number_format($emailsDelivered) }} Delivered to Inbox</div>
    </div>
</div>

<!-- 2. Second Row: Delivery & Engagement Rates -->
<div class="metrics-grid-marketing">
    <div class="m-card">
        <div class="m-label">
            <span>Open Rate</span>
            <i class="fa-solid fa-envelope-open" style="color: #10B981;"></i>
        </div>
        <div class="m-val" style="color: #6EE7B7;">{{ $openRate }}%</div>
        <div class="m-sub" style="color: #9CA3AF;">{{ number_format($emailsOpened) }} Unique Opens</div>
    </div>

    <div class="m-card">
        <div class="m-label">
            <span>Click Rate (CTR)</span>
            <i class="fa-solid fa-arrow-pointer" style="color: #3B82F6;"></i>
        </div>
        <div class="m-val" style="color: #93C5FD;">{{ $clickRate }}%</div>
        <div class="m-sub" style="color: #9CA3AF;">{{ number_format($emailsClicked) }} Website Visits</div>
    </div>

    <div class="m-card">
        <div class="m-label">
            <span>Bounce Rate</span>
            <i class="fa-solid fa-triangle-exclamation" style="color: #F59E0B;"></i>
        </div>
        <div class="m-val" style="color: #FCD34D;">{{ $bounceRate }}%</div>
        <div class="m-sub" style="color: #9CA3AF;">{{ number_format($emailsBounced) }} Hard/Soft Bounces</div>
    </div>

    <div class="m-card">
        <div class="m-label">
            <span>Spam Complaints</span>
            <i class="fa-solid fa-ban" style="color: #EF4444;"></i>
        </div>
        <div class="m-val" style="color: #FCA5A5;">{{ $complaintRate }}%</div>
        <div class="m-sub" style="color: #9CA3AF;">{{ number_format($emailsComplained) }} Complaints (Suppressed)</div>
    </div>
</div>

<!-- 3. Third Row: Cost, Quota & Domain Health -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 18px; margin-bottom: 24px;">
    <!-- OpenAI Card -->
    <div class="data-card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <strong style="color: var(--lux-gold); font-size: 14px;"><i class="fa-solid fa-brain"></i> OpenAI API Consumption</strong>
            <span class="badge badge-gold">{{ config('services.openai.model', 'gpt-4o') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px;">
            <span style="color: var(--lux-muted);">Total Tokens Billed:</span>
            <strong>{{ number_format($openaiTokens) }} tokens</strong>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px;">
            <span style="color: var(--lux-muted);">Estimated API Cost:</span>
            <strong style="color: var(--lux-gold); font-size: 15px;">${{ number_format($openaiCostUsd, 4) }} USD</strong>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13px;">
            <span style="color: var(--lux-muted);">Avg. Generation Speed:</span>
            <span>~1.8s / personalized copy</span>
        </div>
    </div>

    <!-- AWS SES Card -->
    <div class="data-card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <strong style="color: var(--lux-gold); font-size: 14px;"><i class="fa-brands fa-aws"></i> Amazon SES Infrastructure</strong>
            <span class="badge badge-success">{{ $sesStats['status'] }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px;">
            <span style="color: var(--lux-muted);">AWS Region:</span>
            <span><code>{{ $sesStats['region'] }}</code></span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px;">
            <span style="color: var(--lux-muted);">Sending Domain:</span>
            <span><code>{{ $sesStats['sending_domain'] }}</code></span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px;">
            <span style="color: var(--lux-muted);">Configuration Set:</span>
            <span><code>{{ $sesStats['configuration_set'] }}</code></span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13px;">
            <span style="color: var(--lux-muted);">Daily & Per-Sec Quota:</span>
            <span>{{ $sesStats['daily_quota'] }} • {{ $sesStats['rate_limit'] }}</span>
        </div>
    </div>
</div>

<!-- 4. Campaign-wise Performance Reports -->
<div class="data-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 style="font-family: 'Cinzel', serif; font-size: 16px; color: var(--lux-gold);"><i class="fa-solid fa-list-check"></i> Campaign Performance Reports</h3>
        <a href="{{ route('marketing.campaigns.index') }}" class="btn btn-dark" style="font-size: 11px;">View All Campaigns →</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Campaign Name</th>
                    <th>Mode</th>
                    <th>Status</th>
                    <th>Recipients</th>
                    <th>Delivered</th>
                    <th>Open Rate</th>
                    <th>CTR</th>
                    <th>Bounced</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $camp)
                <tr>
                    <td>
                        <strong><a href="{{ route('marketing.campaigns.show', $camp->id) }}" style="color: #FFF; text-decoration: none;">{{ $camp->name }}</a></strong><br>
                        <span style="font-size: 11px; color: var(--lux-muted);">Subject: {{ Str::limit($camp->subject, 38) }}</span>
                    </td>
                    <td>
                        <span class="badge badge-gold" style="font-size: 10px;">{{ strtoupper($camp->sending_mode) }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $camp->status === 'completed' ? 'badge-success' : ($camp->status === 'sending' ? 'badge-warning' : 'badge-gold') }}">
                            {{ strtoupper($camp->status) }}
                        </span>
                    </td>
                    <td>{{ number_format($camp->total_contacts) }}</td>
                    <td>{{ number_format($camp->emails_delivered) }}</td>
                    <td><strong style="color: #6EE7B7;">{{ $camp->open_rate }}%</strong></td>
                    <td><strong style="color: #93C5FD;">{{ $camp->click_rate }}%</strong></td>
                    <td>{{ $camp->emails_bounced }}</td>
                    <td>
                        <a href="{{ route('marketing.campaigns.show', $camp->id) }}" class="btn btn-dark" style="padding: 4px 8px; font-size: 11px;">
                            <i class="fa-solid fa-chart-column"></i> Report
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align: center; color: var(--lux-muted);">No campaigns executed yet. Click "+ New Campaign" to launch.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- 5. Recent SES Event Activity Log -->
<div class="data-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 style="font-family: 'Cinzel', serif; font-size: 16px; color: var(--lux-gold);"><i class="fa-solid fa-bolt"></i> Live Amazon SES Event Stream</h3>
        <a href="{{ route('marketing.logs.index') }}" class="btn btn-dark" style="font-size: 11px;">View Complete Logs →</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Event Type</th>
                    <th>Recipient</th>
                    <th>SES Message ID</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentEvents as $event)
                <tr>
                    <td>
                        @if($event->event_type === 'delivery')
                            <span class="badge badge-success"><i class="fa-solid fa-check"></i> DELIVERED</span>
                        @elseif($event->event_type === 'open')
                            <span class="badge" style="background: rgba(59, 130, 246, 0.15); color: #93C5FD; border: 1px solid rgba(59, 130, 246, 0.3);"><i class="fa-solid fa-envelope-open"></i> OPENED</span>
                        @elseif($event->event_type === 'click')
                            <span class="badge" style="background: rgba(168, 85, 247, 0.15); color: #D8B4FE; border: 1px solid rgba(168, 85, 247, 0.3);"><i class="fa-solid fa-mouse-pointer"></i> CLICKED</span>
                        @elseif($event->event_type === 'bounce')
                            <span class="badge badge-warning"><i class="fa-solid fa-rotate-left"></i> BOUNCED</span>
                        @elseif($event->event_type === 'complaint')
                            <span class="badge badge-danger"><i class="fa-solid fa-flag"></i> COMPLAINT</span>
                        @else
                            <span class="badge badge-gold">{{ strtoupper($event->event_type) }}</span>
                        @endif
                    </td>
                    <td>{{ $event->recipient_email }}</td>
                    <td><code style="font-size: 11px; color: var(--lux-muted);">{{ Str::limit($event->ses_message_id, 32) }}</code></td>
                    <td>{{ $event->event_timestamp->diffForHumans() }}</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align: center; color: var(--lux-muted);">No SES event logs recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
