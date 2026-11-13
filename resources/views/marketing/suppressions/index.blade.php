@extends('marketing.layout')

@section('title', 'Suppression List')
@section('page_title', 'Central Email Suppression & Compliance')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 20px;">
    <div class="data-card" style="margin-bottom: 0; padding: 16px;">
        <div style="font-size: 11px; text-transform: uppercase; color: var(--lux-muted);">Total Suppressed</div>
        <div style="font-size: 24px; font-weight: 700; color: #FFF;">{{ number_format($stats['total']) }}</div>
        <div style="font-size: 11px; color: var(--lux-gold);">Active Protections</div>
    </div>
    <div class="data-card" style="margin-bottom: 0; padding: 16px;">
        <div style="font-size: 11px; text-transform: uppercase; color: var(--lux-muted);">Unsubscribes</div>
        <div style="font-size: 24px; font-weight: 700; color: #93C5FD;">{{ number_format($stats['unsubscribes']) }}</div>
        <div style="font-size: 11px; color: var(--lux-muted);">User Opt-Outs</div>
    </div>
    <div class="data-card" style="margin-bottom: 0; padding: 16px;">
        <div style="font-size: 11px; text-transform: uppercase; color: var(--lux-muted);">Hard Bounces</div>
        <div style="font-size: 24px; font-weight: 700; color: #FCD34D;">{{ number_format($stats['hard_bounces']) }}</div>
        <div style="font-size: 11px; color: var(--lux-muted);">SES Invalid Addresses</div>
    </div>
    <div class="data-card" style="margin-bottom: 0; padding: 16px;">
        <div style="font-size: 11px; text-transform: uppercase; color: var(--lux-muted);">Spam Complaints</div>
        <div style="font-size: 24px; font-weight: 700; color: #FCA5A5;">{{ number_format($stats['complaints']) }}</div>
        <div style="font-size: 11px; color: var(--lux-muted);">Reputation Safeguards</div>
    </div>
</div>

<div class="data-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h3 style="font-family: 'Cinzel', serif; font-size: 17px; color: var(--lux-gold);">
                <i class="fa-solid fa-ban"></i> Suppressed Addresses
            </h3>
            <span style="font-size: 12px; color: var(--lux-muted);">
                Central check verifies every recipient before send. Suppressed contacts are never emailed.
            </span>
        </div>
        <button type="button" class="btn btn-gold" onclick="openModal('addSuppressionModal')">
            <i class="fa-solid fa-plus-circle"></i> Add Suppression Manually
        </button>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Email Address</th>
                    <th>Suppression Reason</th>
                    <th>Detection Source</th>
                    <th>Logged At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppressions as $s)
                <tr>
                    <td><strong>{{ $s->email }}</strong></td>
                    <td>
                        @if($s->reason === 'unsubscribe')
                            <span class="badge" style="background: rgba(59, 130, 246, 0.15); color: #93C5FD;"><i class="fa-solid fa-user-xmark"></i> UNSUBSCRIBE</span>
                        @elseif($s->reason === 'hard_bounce')
                            <span class="badge badge-warning"><i class="fa-solid fa-triangle-exclamation"></i> HARD BOUNCE</span>
                        @elseif($s->reason === 'complaint')
                            <span class="badge badge-danger"><i class="fa-solid fa-flag"></i> SPAM COMPLAINT</span>
                        @else
                            <span class="badge badge-gold">{{ strtoupper($s->reason) }}</span>
                        @endif
                    </td>
                    <td><span style="font-size: 11px; color: var(--lux-muted);">{{ $s->source ?? 'System' }}</span></td>
                    <td>{{ $s->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <form action="{{ route('marketing.suppressions.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Remove suppression for {{ $s->email }}? Only do this if recipient confirmed re-opt-in.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-dark" style="padding: 4px 8px; font-size: 11px;">
                                <i class="fa-solid fa-trash-can"></i> Remove
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align: center; color: var(--lux-muted);">No suppressed email addresses.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 18px;">
        {{ $suppressions->links() }}
    </div>
</div>

<!-- Modal: Add Suppression -->
<div id="addSuppressionModal" class="modal">
    <div class="modal-content">
        <h3 style="font-family: 'Cinzel', serif; color: var(--lux-gold); margin-bottom: 16px;">
            <i class="fa-solid fa-ban"></i> Add Email to Suppression List
        </h3>
        <form action="{{ route('marketing.suppressions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address to Suppress</label>
                <input type="email" name="email" class="form-control" required placeholder="contact@example.co.uk">
            </div>
            <div class="form-group">
                <label class="form-label">Suppression Reason</label>
                <select name="reason" class="form-control" required>
                    <option value="manual">Manual Suppression (Admin Directive)</option>
                    <option value="unsubscribe">User Opt-Out / Unsubscribe</option>
                    <option value="hard_bounce">Hard Bounce (Undeliverable / Mailbox Not Found)</option>
                    <option value="complaint">Spam Complaint</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Reason Notes</label>
                <input type="text" name="notes" class="form-control" placeholder="Optional audit explanation">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 18px;">
                <button type="button" class="btn btn-dark" onclick="closeModal('addSuppressionModal')">Cancel</button>
                <button type="submit" class="btn btn-gold">Confirm Suppression</button>
            </div>
        </form>
    </div>
</div>
@endsection
