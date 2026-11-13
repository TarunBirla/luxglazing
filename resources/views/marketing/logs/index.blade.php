@extends('marketing.layout')

@section('title', 'Email Delivery Logs')
@section('page_title', 'Email Delivery & SES Event Audit Logs')

@section('content')
<div class="data-card" style="padding: 16px 20px; margin-bottom: 20px;">
    <form method="GET" action="{{ route('marketing.logs.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
        <div style="flex: 1; min-width: 220px;">
            <input type="text" name="search" class="form-control" placeholder="Search by recipient email, subject, SES Message ID..." value="{{ request('search') }}">
        </div>

        <div style="width: 200px;">
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="">All Delivery Statuses</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="opened" {{ request('status') === 'opened' ? 'selected' : '' }}>Opened</option>
                <option value="clicked" {{ request('status') === 'clicked' ? 'selected' : '' }}>Clicked</option>
                <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                <option value="queued" {{ request('status') === 'queued' ? 'selected' : '' }}>Queued</option>
                <option value="bounced" {{ request('status') === 'bounced' ? 'selected' : '' }}>Bounced</option>
                <option value="complained" {{ request('status') === 'complained' ? 'selected' : '' }}>Complained</option>
                <option value="suppressed" {{ request('status') === 'suppressed' ? 'selected' : '' }}>Suppressed</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>

        <div style="width: 200px;">
            <select name="campaign_id" class="form-control" onchange="this.form.submit()">
                <option value="">All Campaigns</option>
                @foreach($campaigns as $camp)
                    <option value="{{ $camp->id }}" {{ request('campaign_id') == $camp->id ? 'selected' : '' }}>{{ Str::limit($camp->name, 24) }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-dark"><i class="fa-solid fa-filter"></i> Filter</button>
        <a href="{{ route('marketing.logs.index') }}" class="btn btn-dark"><i class="fa-solid fa-rotate-left"></i> Reset</a>
    </form>
</div>

<div class="data-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 style="font-family: 'Cinzel', serif; font-size: 16px; color: var(--lux-gold);">
            <i class="fa-solid fa-receipt"></i> Dispatched Emails Log ({{ $emails->total() }})
        </h3>
        <span style="font-size: 11px; color: var(--lux-muted);"><i class="fa-brands fa-aws"></i> Verified SES Webhook Feedback Loop</span>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Recipient</th>
                    <th>Campaign</th>
                    <th>Status</th>
                    <th>SES Message ID</th>
                    <th>Sent Timestamp</th>
                    <th>Delivered</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($emails as $em)
                <tr>
                    <td>
                        <strong>{{ $em->recipient_name }}</strong><br>
                        <span style="font-size: 11px; color: var(--lux-muted);"><i class="fa-solid fa-envelope"></i> {{ $em->recipient_email }}</span>
                    </td>
                    <td>
                        <span style="font-size: 12px; font-weight: 600; color: #FFF;">{{ Str::limit($em->campaign->name ?? 'Direct', 24) }}</span><br>
                        <span style="font-size: 10px; color: var(--lux-gold);">{{ strtoupper($em->generation_mode) }} Mode</span>
                    </td>
                    <td>
                        @if($em->status === 'delivered')
                            <span class="badge badge-success"><i class="fa-solid fa-check"></i> DELIVERED</span>
                        @elseif($em->status === 'opened')
                            <span class="badge" style="background: rgba(59, 130, 246, 0.15); color: #93C5FD;"><i class="fa-solid fa-envelope-open"></i> OPENED</span>
                        @elseif($em->status === 'clicked')
                            <span class="badge" style="background: rgba(168, 85, 247, 0.15); color: #D8B4FE;"><i class="fa-solid fa-mouse-pointer"></i> CLICKED</span>
                        @elseif($em->status === 'bounced')
                            <span class="badge badge-warning"><i class="fa-solid fa-rotate-left"></i> BOUNCED</span>
                        @elseif($em->status === 'complained')
                            <span class="badge badge-danger"><i class="fa-solid fa-flag"></i> COMPLAINT</span>
                        @elseif($em->status === 'suppressed')
                            <span class="badge badge-danger">SUPPRESSED</span>
                        @elseif($em->status === 'sent')
                            <span class="badge badge-gold">SENT</span>
                        @elseif($em->status === 'queued')
                            <span class="badge" style="background: rgba(156, 163, 175, 0.2); color: #D1D5DB;">QUEUED</span>
                        @else
                            <span class="badge badge-gold">{{ strtoupper($em->status) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($em->ses_message_id)
                            <code style="font-size: 11px; color: var(--lux-gold);">{{ Str::limit($em->ses_message_id, 26) }}</code>
                        @else
                            <span style="font-size: 11px; color: var(--lux-muted);">-</span>
                        @endif
                    </td>
                    <td>{{ $em->sent_at ? $em->sent_at->format('d M H:i') : '-' }}</td>
                    <td>{{ $em->delivered_at ? $em->delivered_at->format('d M H:i') : '-' }}</td>
                    <td>
                        <a href="{{ route('marketing.logs.show', $em->id) }}" class="btn btn-dark" style="padding: 4px 8px; font-size: 11px;">
                            <i class="fa-solid fa-eye"></i> Audit
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align: center; color: var(--lux-muted);">No email delivery logs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 18px;">
        {{ $emails->links() }}
    </div>
</div>
@endsection
