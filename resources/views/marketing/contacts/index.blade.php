@extends('marketing.layout')

@section('title', 'Marketing Contacts Management')
@section('page_title', 'Marketing Audience & Permission Directory')

@section('content')
<div class="data-card" style="padding: 16px 20px; margin-bottom: 20px;">
    <form method="GET" action="{{ route('marketing.contacts.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
        <div style="flex: 1; min-width: 220px;">
            <input type="text" name="search" class="form-control" placeholder="Search by name, company, email..." value="{{ request('search') }}">
        </div>

        <div style="width: 180px;">
            <select name="business_type" class="form-control" onchange="this.form.submit()">
                <option value="">All Business Types</option>
                @foreach($businessTypes as $type)
                    <option value="{{ $type }}" {{ request('business_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>

        <div style="width: 170px;">
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="">All Subscription Statuses</option>
                <option value="subscribed" {{ request('status') === 'subscribed' ? 'selected' : '' }}>Subscribed</option>
                <option value="unsubscribed" {{ request('status') === 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
                <option value="suppressed" {{ request('status') === 'suppressed' ? 'selected' : '' }}>Suppressed</option>
            </select>
        </div>

        <button type="submit" class="btn btn-dark"><i class="fa-solid fa-filter"></i> Filter</button>
        <button type="button" class="btn btn-gold" onclick="openModal('addContactModal')"><i class="fa-solid fa-user-plus"></i> Add Contact</button>
        <a href="{{ route('marketing.contacts.import') }}" class="btn btn-dark"><i class="fa-solid fa-file-csv"></i> Bulk CSV</a>
    </form>
</div>

<div class="data-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 style="font-family: 'Cinzel', serif; font-size: 16px; color: var(--lux-gold);">
            <i class="fa-solid fa-address-book"></i> Permitted Contacts ({{ $contacts->total() }})
        </h3>
        <span style="font-size: 11px; color: var(--lux-muted);"><i class="fa-solid fa-shield-halved"></i> GDPR & PECR Documented Consent Enforced</span>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Contact Details</th>
                    <th>Company & Website</th>
                    <th>Business Type</th>
                    <th>Documented Consent</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $c)
                <tr>
                    <td>
                        <strong>{{ $c->name }}</strong><br>
                        <span style="font-size: 11px; color: var(--lux-muted);"><i class="fa-solid fa-envelope"></i> {{ $c->email }}</span>
                        @if($c->phone)
                            <br><span style="font-size: 10px; color: #888;">{{ $c->phone }}</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $c->company ?? 'N/A' }}</strong><br>
                        @if($c->website)
                            <a href="{{ $c->website }}" target="_blank" style="font-size: 11px; color: var(--lux-gold); text-decoration: none;">
                                <i class="fa-solid fa-link"></i> {{ Str::limit($c->website, 24) }}
                            </a>
                        @endif
                    </td>
                    <td><span class="badge badge-gold">{{ $c->business_type ?? 'Trade Partner' }}</span></td>
                    <td>
                        @if($c->consent_status)
                            <span class="badge badge-success"><i class="fa-solid fa-check"></i> {{ $c->consent_source }}</span><br>
                            <span style="font-size: 10px; color: var(--lux-muted);">{{ $c->consent_date ? $c->consent_date->format('d M Y') : 'Logged' }}</span>
                        @else
                            <span class="badge badge-danger"><i class="fa-solid fa-xmark"></i> Consent Revoked</span>
                        @endif
                    </td>
                    <td>
                        @if($c->subscription_status === 'subscribed')
                            <span class="badge badge-success">SUBSCRIBED</span>
                        @elseif($c->subscription_status === 'unsubscribed')
                            <span class="badge badge-warning">UNSUBSCRIBED</span>
                        @else
                            <span class="badge badge-danger">{{ strtoupper($c->subscription_status) }}</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <button class="btn btn-dark" style="padding: 4px 8px; font-size: 11px;" onclick="editContact({{ json_encode($c) }})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('marketing.contacts.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Remove contact from marketing directory?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 4px 8px; font-size: 11px;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align: center; color: var(--lux-muted);">No contacts found matching filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 18px;">
        {{ $contacts->links() }}
    </div>
</div>

<!-- Modal: Add Contact -->
<div id="addContactModal" class="modal">
    <div class="modal-content">
        <h3 style="font-family: 'Cinzel', serif; color: var(--lux-gold); margin-bottom: 16px;"><i class="fa-solid fa-user-plus"></i> Add Permitted Contact</h3>
        <form action="{{ route('marketing.contacts.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required placeholder="e.g. Oliver Henderson">
            </div>
            <div class="form-group">
                <label class="form-label">Email Address (Unique)</label>
                <input type="email" name="email" class="form-control" required placeholder="e.g. oliver@hendersonarchitects.co.uk">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Company / Practice</label>
                    <input type="text" name="company" class="form-control" placeholder="Henderson Architects RIBA">
                </div>
                <div class="form-group">
                    <label class="form-label">Business Type</label>
                    <input type="text" name="business_type" class="form-control" placeholder="Architect / Builder">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Website</label>
                    <input type="url" name="website" class="form-control" placeholder="https://practice.co.uk">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" placeholder="+44 20 7946 0111">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Documented Consent Source</label>
                <select name="consent_source" class="form-control" required>
                    <option value="Direct Web Inquiry">Direct Web Inquiry (Opt-In)</option>
                    <option value="B2B Trade Application">B2B Trade Application</option>
                    <option value="Existing Client Relationship">Existing Client Relationship</option>
                    <option value="Event / Exhibition Attendee">Event / Exhibition Attendee</option>
                </select>
            </div>
            <input type="hidden" name="consent_status" value="1">
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 18px;">
                <button type="button" class="btn btn-dark" onclick="closeModal('addContactModal')">Cancel</button>
                <button type="submit" class="btn btn-gold"><i class="fa-solid fa-save"></i> Save Permitted Contact</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Contact -->
<div id="editContactModal" class="modal">
    <div class="modal-content">
        <h3 style="font-family: 'Cinzel', serif; color: var(--lux-gold); margin-bottom: 16px;"><i class="fa-solid fa-user-pen"></i> Edit Contact</h3>
        <form id="editContactForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" id="editName" class="form-control" required>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Company</label>
                    <input type="text" name="company" id="editCompany" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Business Type</label>
                    <input type="text" name="business_type" id="editBusinessType" class="form-control">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Website</label>
                    <input type="url" name="website" id="editWebsite" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" id="editPhone" class="form-control">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Subscription Status</label>
                    <select name="subscription_status" id="editStatus" class="form-control">
                        <option value="subscribed">Subscribed</option>
                        <option value="unsubscribed">Unsubscribed (Suppressed)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Consent Valid</label>
                    <select name="consent_status" id="editConsent" class="form-control">
                        <option value="1">Yes (Active Permission)</option>
                        <option value="0">No (Revoked)</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 18px;">
                <button type="button" class="btn btn-dark" onclick="closeModal('editContactModal')">Cancel</button>
                <button type="submit" class="btn btn-gold">Update Contact</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function editContact(contact) {
        document.getElementById('editContactForm').action = '/admin/marketing/contacts/' + contact.id;
        document.getElementById('editName').value = contact.name || '';
        document.getElementById('editCompany').value = contact.company || '';
        document.getElementById('editBusinessType').value = contact.business_type || '';
        document.getElementById('editWebsite').value = contact.website || '';
        document.getElementById('editPhone').value = contact.phone || '';
        document.getElementById('editStatus').value = contact.subscription_status || 'subscribed';
        document.getElementById('editConsent').value = contact.consent_status ? '1' : '0';
        openModal('editContactModal');
    }
</script>
@endpush
@endsection
