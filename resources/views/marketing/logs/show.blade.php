@extends('marketing.layout')

@section('title', 'Email Dispatch #' . $email->id . ' - AI Marketing')
@section('header_title', 'Email Dispatch Audit')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('marketing.logs.index') }}" class="p-2 rounded-lg bg-[#121824] border border-[#222E42] text-[#94A3B8] hover:text-[#E5D2A5] hover:border-[#E5D2A5]/30 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <span>Dispatch #{{ $email->id }}</span>
                <span class="text-xs px-2.5 py-1 rounded-full uppercase tracking-wider font-semibold
                    @if($email->status === 'delivered') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                    @elseif(in_array($email->status, ['opened', 'clicked'])) bg-amber-500/10 text-[#E5D2A5] border border-[#E5D2A5]/30
                    @elseif($email->status === 'sent') bg-blue-500/10 text-blue-400 border border-blue-500/20
                    @elseif($email->status === 'queued') bg-purple-500/10 text-purple-400 border border-purple-500/20
                    @elseif(in_array($email->status, ['bounced', 'failed'])) bg-rose-500/10 text-rose-400 border border-rose-500/20
                    @elseif($email->status === 'complained') bg-red-600/20 text-red-400 border border-red-500/40
                    @elseif($email->status === 'suppressed') bg-amber-900/30 text-amber-300 border border-amber-600/30
                    @else bg-slate-700/30 text-slate-400 border border-slate-700
                    @endif">
                    {{ ucfirst($email->status) }}
                </span>
            </h1>
            <p class="text-xs text-[#94A3B8]">SES Message ID & Event Delivery Timeline</p>
        </div>
    </div>

    @if($email->campaign)
        <a href="{{ route('marketing.campaigns.show', $email->campaign_id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#121824] border border-[#222E42] text-xs font-medium text-[#E5D2A5] rounded-xl hover:border-[#E5D2A5]/40 transition">
            <i class="fa-solid fa-bullhorn"></i> View Campaign: {{ Str::limit($email->campaign->name, 25) }}
        </a>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Details & Event Timeline -->
    <div class="space-y-6">
        <!-- Delivery Meta Card -->
        <div class="bg-[#121824] border border-[#222E42] rounded-2xl p-5">
            <h3 class="text-xs font-semibold text-[#94A3B8] uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-[#E5D2A5]"></i> Dispatch Metadata
            </h3>
            
            <dl class="space-y-3 text-xs">
                <div>
                    <dt class="text-[#64748B]">Recipient Name</dt>
                    <dd class="text-white font-medium mt-0.5">{{ $email->recipient_name ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Recipient Email</dt>
                    <dd class="text-white font-mono mt-0.5 break-all">{{ $email->recipient_email }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Associated Contact</dt>
                    <dd class="mt-0.5">
                        @if($email->contact)
                            <span class="text-white">{{ $email->contact->company ?? 'No Company' }}</span>
                            <span class="text-[10px] text-[#64748B]">({{ $email->contact->marketing_status }})</span>
                        @else
                            <span class="text-[#64748B]">Direct / Unlinked</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">AWS SES Message ID</dt>
                    <dd class="text-amber-300 font-mono text-[11px] mt-0.5 break-all bg-[#0B0E14] p-2 rounded-lg border border-[#222E42]">
                        {{ $email->ses_message_id ?? 'Not assigned / Pre-SES' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Retry Attempts</dt>
                    <dd class="text-white mt-0.5 font-medium">{{ $email->retry_count ?? 0 }} / 3 max</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Created At</dt>
                    <dd class="text-[#94A3B8] mt-0.5">{{ $email->created_at->format('d M Y, H:i:s') }}</dd>
                </div>
                @if($email->sent_at)
                    <div>
                        <dt class="text-[#64748B]">Sent via SES</dt>
                        <dd class="text-blue-400 mt-0.5">{{ \Carbon\Carbon::parse($email->sent_at)->format('d M Y, H:i:s') }}</dd>
                    </div>
                @endif
                @if($email->delivered_at)
                    <div>
                        <dt class="text-[#64748B]">Delivered (Inbox Placement confirmed)</dt>
                        <dd class="text-emerald-400 mt-0.5">{{ \Carbon\Carbon::parse($email->delivered_at)->format('d M Y, H:i:s') }}</dd>
                    </div>
                @endif
                @if($email->opened_at)
                    <div>
                        <dt class="text-[#64748B]">First Opened</dt>
                        <dd class="text-[#E5D2A5] mt-0.5">{{ \Carbon\Carbon::parse($email->opened_at)->format('d M Y, H:i:s') }}</dd>
                    </div>
                @endif
                @if($email->clicked_at)
                    <div>
                        <dt class="text-[#64748B]">Link Clicked</dt>
                        <dd class="text-cyan-400 mt-0.5">{{ \Carbon\Carbon::parse($email->clicked_at)->format('d M Y, H:i:s') }}</dd>
                    </div>
                @endif
                @if($email->error_message)
                    <div class="pt-2 border-t border-[#222E42]">
                        <dt class="text-rose-400 font-semibold">Error Message / Reason</dt>
                        <dd class="text-rose-300 text-[11px] font-mono mt-1 bg-rose-500/10 p-2.5 rounded-lg border border-rose-500/20 break-all">
                            {{ $email->error_message }}
                        </dd>
                    </div>
                @endif
            </dl>
        </div>

        <!-- AI Generation Metadata (if any) -->
        @if($email->openai_tokens_used || $email->campaign?->sending_mode === 'ai')
            <div class="bg-[#121824] border border-[#222E42] rounded-2xl p-5">
                <h3 class="text-xs font-semibold text-[#94A3B8] uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-brain text-purple-400"></i> AI Generation Metrics
                </h3>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between py-1 border-b border-[#222E42]/50">
                        <span class="text-[#64748B]">Tokens Consumed</span>
                        <span class="text-white font-mono font-medium">{{ number_format($email->openai_tokens_used ?? 180) }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-[#222E42]/50">
                        <span class="text-[#64748B]">Estimated API Cost</span>
                        <span class="text-emerald-400 font-mono font-medium">${{ number_format(($email->openai_tokens_used ?? 180) * 0.0000003, 6) }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-[#64748B]">Content Cached</span>
                        <span class="text-white">Yes (no retry cost)</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Timeline of Events -->
        <div class="bg-[#121824] border border-[#222E42] rounded-2xl p-5">
            <h3 class="text-xs font-semibold text-[#94A3B8] uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-[#E5D2A5]"></i> Delivery Event Log ({{ $email->events->count() }})
            </h3>

            @if($email->events->isEmpty())
                <p class="text-xs text-[#64748B] italic">No real-time SES webhook events logged yet for this dispatch.</p>
            @else
                <ol class="relative border-l border-[#222E42] ml-2 space-y-4">
                    @foreach($email->events as $event)
                        <li class="ml-4">
                            <span class="absolute -left-1.5 mt-1.5 h-3 w-3 rounded-full border border-[#0B0E14]
                                @if(in_array($event->event_type, ['Delivery', 'delivered'])) bg-emerald-500
                                @elseif(in_array($event->event_type, ['Open', 'opened'])) bg-amber-400
                                @elseif(in_array($event->event_type, ['Click', 'clicked'])) bg-cyan-400
                                @elseif(in_array($event->event_type, ['Send', 'sent'])) bg-blue-500
                                @elseif(in_array($event->event_type, ['Bounce', 'bounced'])) bg-rose-500
                                @elseif(in_array($event->event_type, ['Complaint', 'complained'])) bg-red-600
                                @else bg-slate-500
                                @endif">
                            </span>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-white uppercase">{{ $event->event_type }}</span>
                                <time class="text-[10px] text-[#64748B]">{{ $event->created_at->format('d M, H:i:s') }}</time>
                            </div>
                            @if($event->event_metadata)
                                <div class="mt-1 text-[11px] text-[#94A3B8] bg-[#0B0E14] p-2 rounded-lg border border-[#222E42] font-mono break-all">
                                    {{ is_array($event->event_metadata) ? json_encode($event->event_metadata, JSON_PRETTY_PRINT) : $event->event_metadata }}
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>

    <!-- Right Column: Subject & Email Preview -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Subject Banner -->
        <div class="bg-[#121824] border border-[#222E42] rounded-2xl p-5">
            <span class="text-[10px] font-semibold text-[#64748B] uppercase tracking-wider block mb-1">Email Subject Line</span>
            <h2 class="text-lg font-bold text-white">{{ $email->subject }}</h2>
            <div class="mt-3 pt-3 border-t border-[#222E42] flex flex-wrap items-center gap-4 text-xs text-[#94A3B8]">
                <div><span class="text-[#64748B]">To:</span> <span class="text-white">{{ $email->recipient_name }} &lt;{{ $email->recipient_email }}&gt;</span></div>
                <div><span class="text-[#64748B]">From:</span> <span class="text-white">{{ config('mail.from.name') }} &lt;{{ config('mail.from.address') }}&gt;</span></div>
                <div><span class="text-[#64748B]">Config Set:</span> <span class="text-amber-300 font-mono">marketing-tracking</span></div>
            </div>
        </div>

        <!-- Rendered Email Content -->
        <div class="bg-[#121824] border border-[#222E42] rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-semibold text-[#94A3B8] uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-eye text-[#E5D2A5]"></i> Rendered Dispatch Preview
                </span>
                <span class="text-[11px] text-[#64748B]">RFC 8058 List-Unsubscribe injected</span>
            </div>

            <div class="bg-[#0B0E14] rounded-xl p-6 border border-[#222E42] text-slate-200 prose prose-invert max-w-none text-sm leading-relaxed">
                {!! $email->content !!}
            </div>

            <!-- RFC 8058 Compliance Details -->
            <div class="mt-4 p-4 rounded-xl bg-[#0B0E14] border border-[#222E42] text-xs space-y-2">
                <div class="text-[#E5D2A5] font-semibold flex items-center gap-1.5">
                    <i class="fa-solid fa-shield-halved"></i> RFC 8058 & GDPR Compliance Headers
                </div>
                <div class="font-mono text-[11px] text-[#94A3B8] space-y-1">
                    <div><span class="text-[#64748B]">List-Unsubscribe:</span> &lt;{{ $email->contact ? $email->contact->unsubscribe_url : url('/unsubscribe/' . md5($email->recipient_email)) }}&gt;, &lt;mailto:unsubscribe@marketing.nimadtech.in?subject=unsubscribe:{{ $email->recipient_email }}&gt;</div>
                    <div><span class="text-[#64748B]">List-Unsubscribe-Post:</span> List-Unsubscribe=One-Click</div>
                    <div><span class="text-[#64748B]">X-SES-CONFIGURATION-SET:</span> marketing-tracking</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
