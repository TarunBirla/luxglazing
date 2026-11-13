@extends('marketing.layout')

@section('title', 'Email Templates')
@section('page_title', 'Reusable Marketing Email Templates')

@section('content')
<div class="data-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h3 style="font-family: 'Cinzel', serif; font-size: 17px; color: var(--lux-gold);">
                <i class="fa-solid fa-layer-group"></i> Email Templates
            </h3>
            <span style="font-size: 12px; color: var(--lux-muted);">
                Support dynamic tags: <code>{{ '{{name}}' }}</code>, <code>{{ '{{company}}' }}</code>, <code>{{ '{{email}}' }}</code>, <code>{{ '{{website}}' }}</code>, <code>{{ '{{unsubscribe_url}}' }}</code>
            </span>
        </div>
        <button type="button" class="btn btn-gold" onclick="openModal('addTemplateModal')">
            <i class="fa-solid fa-plus-circle"></i> Create New Template
        </button>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Template Name</th>
                    <th>Category</th>
                    <th>Subject Line</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $tpl)
                <tr>
                    <td><strong>{{ $tpl->name }}</strong></td>
                    <td><span class="badge badge-gold">{{ $tpl->category }}</span></td>
                    <td>{{ Str::limit($tpl->subject, 45) }}</td>
                    <td>
                        @if($tpl->is_active)
                            <span class="badge badge-success">ACTIVE</span>
                        @else
                            <span class="badge badge-danger">INACTIVE</span>
                        @endif
                    </td>
                    <td>{{ $tpl->updated_at->format('d M Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <button class="btn btn-dark" style="padding: 4px 8px; font-size: 11px;" onclick="editTemplate({{ json_encode($tpl) }})">
                                <i class="fa-solid fa-pen"></i> Edit
                            </button>
                            <form action="{{ route('marketing.templates.destroy', $tpl->id) }}" method="POST" onsubmit="return confirm('Delete this template?')">
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
                <tr><td colspan="6" style="text-align: center; color: var(--lux-muted);">No templates created yet. Click "Create New Template" above.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 18px;">
        {{ $templates->links() }}
    </div>
</div>

<!-- Modal: Add Template -->
<div id="addTemplateModal" class="modal">
    <div class="modal-content" style="max-width: 650px;">
        <h3 style="font-family: 'Cinzel', serif; color: var(--lux-gold); margin-bottom: 16px;">
            <i class="fa-solid fa-plus-circle"></i> Create Reusable Template
        </h3>
        <form action="{{ route('marketing.templates.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Template Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Cortizo Minimalist Sliding Doors">
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="Architectural Showcase">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Default Subject Line</label>
                <input type="text" name="subject" class="form-control" required placeholder="Architectural Glazing Partnership – {{ '{{company}}' }}">
            </div>
            <div class="form-group">
                <label class="form-label">HTML Email Body Content</label>
                <textarea name="body_html" class="form-control" rows="8" required placeholder="<p>Dear {{ '{{name}}' }},</p><p>We would welcome the opportunity to support {{ '{{company}}' }}...</p>"></textarea>
                <span style="font-size: 11px; color: var(--lux-muted); display: block; margin-top: 4px;">
                    Available variables: <code>{{ '{{name}}' }}</code>, <code>{{ '{{company}}' }}</code>, <code>{{ '{{email}}' }}</code>, <code>{{ '{{website}}' }}</code>, <code>{{ '{{unsubscribe_url}}' }}</code>
                </span>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 18px;">
                <button type="button" class="btn btn-dark" onclick="closeModal('addTemplateModal')">Cancel</button>
                <button type="submit" class="btn btn-gold">Save Template</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Template -->
<div id="editTemplateModal" class="modal">
    <div class="modal-content" style="max-width: 650px;">
        <h3 style="font-family: 'Cinzel', serif; color: var(--lux-gold); margin-bottom: 16px;">
            <i class="fa-solid fa-pen"></i> Edit Template
        </h3>
        <form id="editTemplateForm" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Template Name</label>
                    <input type="text" name="name" id="tplName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" id="tplCategory" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Subject Line</label>
                <input type="text" name="subject" id="tplSubject" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">HTML Email Body</label>
                <textarea name="body_html" id="tplBodyHtml" class="form-control" rows="8" required></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="is_active" id="tplActive" class="form-control">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 18px;">
                <button type="button" class="btn btn-dark" onclick="closeModal('editTemplateModal')">Cancel</button>
                <button type="submit" class="btn btn-gold">Update Template</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function editTemplate(tpl) {
        document.getElementById('editTemplateForm').action = '/admin/marketing/templates/' + tpl.id;
        document.getElementById('tplName').value = tpl.name || '';
        document.getElementById('tplCategory').value = tpl.category || '';
        document.getElementById('tplSubject').value = tpl.subject || '';
        document.getElementById('tplBodyHtml').value = tpl.body_html || '';
        document.getElementById('tplActive').value = tpl.is_active ? '1' : '0';
        openModal('editTemplateModal');
    }
</script>
@endpush
@endsection
