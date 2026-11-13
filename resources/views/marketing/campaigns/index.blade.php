@extends('marketing.layout')

@section('title', 'Marketing Campaigns')
@section('page_title', 'Email Marketing Campaigns (AWS SES + Redis)')

@section('content')
<div class="data-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h3 style="font-family: 'Cinzel', serif; font-size: 17px; color: var(--lux-gold);">
                <i class="fa-solid fa-bullhorn"></i> All Marketing Campaigns
            </h3>
            <span style="font-size: 12px; color: var(--lux-muted);">Multi-batch queue dispatching with Amazon SES delivery tracking</span>
        </div>
        <a href="{{ route('marketing.campaigns.create') }}" class="btn btn-gold">
            <i class="fa-solid fa-plus-circle"></i> Create New Campaign
        </a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Campaign & Subject</th>
                    <th>Mode</th>
                    <th>Status</th>
                    <th>Audience</th>
                    <th>Delivered</th>
                    <th>Open Rate</th>
                    <th>CTR</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $camp)
                <tr>
                    <td>
                        <strong><a href="{{ route('marketing.campaigns.show', $camp->id) }}" style="color: #FFF; text-decoration: none;">{{ $camp->name }}</a></strong><br>
                        <span style="font-size: 11px; color: var(--lux-muted);"><i class="fa-solid fa-envelope"></i> {{ Str::limit($camp->subject, 40) }}</span>
                    </td>
                    <td>
                        <span class="badge badge-gold" style="font-size: 10px;">{{ strtoupper(str_replace('_', ' ', $camp->sending_mode)) }}</span>
                    </td>
                    <td>
                        @if($camp->status === 'completed')
                            <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> COMPLETED</span>
                        @elseif($camp->status === 'sending' || $camp->status === 'processing')
                            <span class="badge badge-warning"><i class="fa-solid fa-spinner fa-spin"></i> SENDING</span>
                        @elseif($camp->status === 'paused')
                            <span class="badge" style="background: rgba(156, 163, 175, 0.2); color: #D1D5DB;"><i class="fa-solid fa-pause"></i> PAUSED</span>
                        @elseif($camp->status === 'scheduled')
                            <span class="badge" style="background: rgba(59, 130, 246, 0.15); color: #93C5FD;"><i class="fa-solid fa-clock"></i> SCHEDULED</span>
                        @else
                            <span class="badge badge-gold">{{ strtoupper($camp->status) }}</span>
                        @endif
                    </td>
                    <td>{{ number_format($camp->total_contacts) }}</td>
                    <td>{{ number_format($camp->emails_delivered) }}</td>
                    <td><strong style="color: #6EE7B7;">{{ $camp->open_rate }}%</strong></td>
                    <td><strong style="color: #93C5FD;">{{ $camp->click_rate }}%</strong></td>
                    <td><span style="font-size: 11px; color: var(--lux-muted);">{{ $camp->created_at->format('d M Y') }}</span></td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('marketing.campaigns.show', $camp->id) }}" class="btn btn-dark" style="padding: 4px 8px; font-size: 11px;" title="View Report">
                                <i class="fa-solid fa-chart-column"></i>
                            </a>
                            <a href="{{ route('marketing.campaigns.preview', $camp->id) }}" class="btn btn-dark" style="padding: 4px 8px; font-size: 11px;" title="Preview Email">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <form action="{{ route('marketing.campaigns.duplicate', $camp->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn btn-dark" style="padding: 4px 8px; font-size: 11px;" title="Duplicate Campaign">
                                    <i class="fa-solid fa-copy"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align: center; color: var(--lux-muted);">No marketing campaigns found. Click "Create New Campaign" to start.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 18px;">
        {{ $campaigns->links() }}
    </div>
</div>
@endsection
