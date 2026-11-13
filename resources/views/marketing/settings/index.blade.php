@extends('marketing.layout')

@section('title', 'Marketing & SES Settings - AI Marketing')
@section('header_title', 'SES & OpenAI Integration Settings')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-sliders text-[#E5D2A5]"></i> Engine & Integration Settings
            </h1>
            <p class="text-xs text-[#94A3B8]">Configure Amazon SES (ap-south-1), OpenAI LLM model, domain verification and sending thresholds.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="testOpenAiConnection()" id="testAiBtn" class="px-4 py-2 bg-[#121824] border border-purple-500/30 text-purple-300 hover:text-purple-200 hover:border-purple-500 text-xs font-medium rounded-xl transition flex items-center gap-2">
                <i class="fa-solid fa-brain"></i> Test OpenAI Connection
            </button>
        </div>
    </div>

    <!-- Alert / Toast for AJAX test -->
    <div id="ajaxAlert" class="hidden p-4 rounded-xl text-xs font-medium border transition-all"></div>

    <form action="{{ route('marketing.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left: Amazon SES Configuration -->
            <div class="bg-[#121824] border border-[#222E42] rounded-2xl p-6 space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-[#222E42]">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 border border-[#E5D2A5]/30 flex items-center justify-center text-[#E5D2A5]">
                            <i class="fa-brands fa-aws"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-white">Amazon SES Settings</h2>
                            <p class="text-[11px] text-[#94A3B8]">AWS Region: ap-south-1 (Mumbai)</p>
                        </div>
                    </div>
                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Production Ready</span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#94A3B8] uppercase tracking-wider mb-1.5">AWS Region</label>
                        <select name="aws_region" class="w-full bg-[#0B0E14] border border-[#222E42] rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-[#E5D2A5]">
                            <option value="ap-south-1" {{ ($settings['aws_region'] ?? '') === 'ap-south-1' ? 'selected' : '' }}>ap-south-1 (Asia Pacific - Mumbai)</option>
                            <option value="eu-west-1" {{ ($settings['aws_region'] ?? '') === 'eu-west-1' ? 'selected' : '' }}>eu-west-1 (Europe - Ireland)</option>
                            <option value="eu-west-2" {{ ($settings['aws_region'] ?? '') === 'eu-west-2' ? 'selected' : '' }}>eu-west-2 (Europe - London)</option>
                            <option value="us-east-1" {{ ($settings['aws_region'] ?? '') === 'us-east-1' ? 'selected' : '' }}>us-east-1 (US East - N. Virginia)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#94A3B8] uppercase tracking-wider mb-1.5">SES Configuration Set</label>
                        <input type="text" name="aws_configuration_set" value="{{ $settings['aws_configuration_set'] ?? 'marketing-tracking' }}" required
                            class="w-full bg-[#0B0E14] border border-[#222E42] rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-[#E5D2A5] font-mono">
                        <span class="text-[10px] text-[#64748B] block mt-1">Config set attaches open/click tracking & directs SNS events to webhook.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#94A3B8] uppercase tracking-wider mb-1.5">Sending Subdomain / Domain</label>
                        <input type="text" name="sending_domain" value="{{ $settings['sending_domain'] ?? 'marketing.nimadtech.in' }}" required
                            class="w-full bg-[#0B0E14] border border-[#222E42] rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-[#E5D2A5] font-mono">
                        <span class="text-[10px] text-[#64748B] block mt-1">Must be verified with SPF, DKIM and DMARC on GoDaddy DNS.</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[#94A3B8] uppercase tracking-wider mb-1.5">Max Rate / Sec</label>
                            <input type="number" name="rate_per_second" value="{{ $settings['rate_per_second'] ?? 14 }}" min="1" max="100" required
                                class="w-full bg-[#0B0E14] border border-[#222E42] rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-[#E5D2A5]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#94A3B8] uppercase tracking-wider mb-1.5">Daily Quota</label>
                            <input type="number" name="daily_quota" value="{{ $settings['daily_quota'] ?? 50000 }}" min="1" required
                                class="w-full bg-[#0B0E14] border border-[#222E42] rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-[#E5D2A5]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#94A3B8] uppercase tracking-wider mb-1.5">From Email Address</label>
                        <input type="email" name="sender_email" value="{{ $settings['sender_email'] ?? 'marketing@marketing.nimadtech.in' }}" required
                            class="w-full bg-[#0B0E14] border border-[#222E42] rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-[#E5D2A5]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#94A3B8] uppercase tracking-wider mb-1.5">Sender Display Name</label>
                        <input type="text" name="sender_name" value="{{ $settings['sender_name'] ?? 'Lux Glazing Architectural' }}" required
                            class="w-full bg-[#0B0E14] border border-[#222E42] rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-[#E5D2A5]">
                    </div>
                </div>
            </div>

            <!-- Right: OpenAI Configuration -->
            <div class="space-y-6">
                <div class="bg-[#121824] border border-[#222E42] rounded-2xl p-6 space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-[#222E42]">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-purple-400">
                                <i class="fa-solid fa-robot"></i>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-white">OpenAI Engine Settings</h2>
                                <p class="text-[11px] text-[#94A3B8]">Personalized B2B Cold Outreach Generator</p>
                            </div>
                        </div>
                        <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-purple-500/10 text-purple-300 border border-purple-500/20">GPT-4o</span>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#94A3B8] uppercase tracking-wider mb-1.5">OpenAI API Key</label>
                            <div class="relative">
                                <input type="password" id="openaiKeyInput" name="openai_api_key" value="{{ $settings['openai_api_key'] ?? '' }}" placeholder="sk-proj-..."
                                    class="w-full bg-[#0B0E14] border border-[#222E42] rounded-xl pl-3.5 pr-10 py-2.5 text-xs text-white focus:outline-none focus:border-[#E5D2A5] font-mono">
                                <button type="button" onclick="toggleKeyVisibility()" class="absolute right-3 top-2.5 text-[#64748B] hover:text-[#E5D2A5]">
                                    <i id="keyEyeIcon" class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <span class="text-[10px] text-[#64748B] block mt-1">If blank, defaults to OPENAI_API_KEY from your server's .env file.</span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#94A3B8] uppercase tracking-wider mb-1.5">OpenAI Model</label>
                            <select name="openai_model" class="w-full bg-[#0B0E14] border border-[#222E42] rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-[#E5D2A5]">
                                <option value="gpt-4o" {{ ($settings['openai_model'] ?? '') === 'gpt-4o' ? 'selected' : '' }}>gpt-4o (High precision, professional B2B phrasing)</option>
                                <option value="gpt-4o-mini" {{ ($settings['openai_model'] ?? '') === 'gpt-4o-mini' ? 'selected' : '' }}>gpt-4o-mini (Fast & economical)</option>
                                <option value="gpt-4-turbo" {{ ($settings['openai_model'] ?? '') === 'gpt-4-turbo' ? 'selected' : '' }}>gpt-4-turbo</option>
                            </select>
                        </div>

                        <div class="p-3.5 rounded-xl bg-[#0B0E14] border border-[#222E42] space-y-1.5 text-xs">
                            <div class="text-[#E5D2A5] font-semibold flex items-center gap-1.5">
                                <i class="fa-solid fa-lightbulb"></i> Cost & Caching Guardrails
                            </div>
                            <p class="text-[11px] text-[#94A3B8] leading-relaxed">
                                Generated personalized content is permanently cached in <code class="text-white">marketing_emails.content</code> prior to dispatch. If a delivery retries after an exponential backoff, OpenAI API will <strong>never</strong> be re-queried, preventing duplicate token costs.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Webhook & Endpoint URLs -->
                <div class="bg-[#121824] border border-[#222E42] rounded-2xl p-6 space-y-4">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-satellite-dish text-[#E5D2A5]"></i> System Endpoints for AWS SES
                    </h3>

                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="text-[#64748B] block text-[10px] uppercase font-semibold">AWS SES Event Webhook (SNS Topic HTTP/HTTPS Destination)</span>
                            <div class="flex items-center gap-2 mt-1">
                                <input type="text" readonly value="{{ route('marketing.webhooks.ses') }}" class="w-full bg-[#0B0E14] border border-[#222E42] rounded-lg px-2.5 py-1.5 text-[11px] font-mono text-emerald-400 select-all">
                                <button type="button" onclick="navigator.clipboard.writeText('{{ route('marketing.webhooks.ses') }}')" class="p-1.5 rounded-lg bg-[#0B0E14] border border-[#222E42] text-[#94A3B8] hover:text-white" title="Copy URL">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <span class="text-[#64748B] block text-[10px] uppercase font-semibold">RFC 8058 One-Click Unsubscribe Endpoint</span>
                            <div class="flex items-center gap-2 mt-1">
                                <input type="text" readonly value="{{ url('/unsubscribe/one-click') }}" class="w-full bg-[#0B0E14] border border-[#222E42] rounded-lg px-2.5 py-1.5 text-[11px] font-mono text-amber-300 select-all">
                                <button type="button" onclick="navigator.clipboard.writeText('{{ url('/unsubscribe/one-click') }}')" class="p-1.5 rounded-lg bg-[#0B0E14] border border-[#222E42] text-[#94A3B8] hover:text-white" title="Copy URL">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#E5D2A5] to-[#c7b280] text-slate-950 font-bold text-xs shadow-lg shadow-[#E5D2A5]/10 hover:opacity-95 transition flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Save Integration Settings
            </button>
        </div>
    </form>

    <!-- GoDaddy DNS Records Reference Table -->
    <div class="bg-[#121824] border border-[#222E42] rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-network-wired text-[#E5D2A5]"></i> GoDaddy DNS Setup Guide for marketing.nimadtech.in
                </h3>
                <p class="text-xs text-[#94A3B8]">Add these DNS records in your GoDaddy DNS Manager to achieve 10/10 deliverability and avoid spam filters.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-[#222E42] text-[#94A3B8] uppercase text-[10px]">
                        <th class="py-2.5 px-3">Type</th>
                        <th class="py-2.5 px-3">Host / Name</th>
                        <th class="py-2.5 px-3">Value / Target</th>
                        <th class="py-2.5 px-3">TTL</th>
                        <th class="py-2.5 px-3">Purpose</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#222E42] text-slate-300 font-mono text-[11px]">
                    <tr>
                        <td class="py-2.5 px-3 text-purple-400 font-bold">TXT</td>
                        <td class="py-2.5 px-3">marketing</td>
                        <td class="py-2.5 px-3 text-white break-all">v=spf1 include:amazonses.com ~all</td>
                        <td class="py-2.5 px-3 text-[#64748B]">1 Hour</td>
                        <td class="py-2.5 px-3 text-[#94A3B8] font-sans">SPF Authentication</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 text-cyan-400 font-bold">CNAME</td>
                        <td class="py-2.5 px-3">[token1]._domainkey.marketing</td>
                        <td class="py-2.5 px-3 text-white break-all">[token1].dkim.amazonses.com</td>
                        <td class="py-2.5 px-3 text-[#64748B]">1 Hour</td>
                        <td class="py-2.5 px-3 text-[#94A3B8] font-sans">Easy-DKIM Key 1</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 text-cyan-400 font-bold">CNAME</td>
                        <td class="py-2.5 px-3">[token2]._domainkey.marketing</td>
                        <td class="py-2.5 px-3 text-white break-all">[token2].dkim.amazonses.com</td>
                        <td class="py-2.5 px-3 text-[#64748B]">1 Hour</td>
                        <td class="py-2.5 px-3 text-[#94A3B8] font-sans">Easy-DKIM Key 2</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 text-cyan-400 font-bold">CNAME</td>
                        <td class="py-2.5 px-3">[token3]._domainkey.marketing</td>
                        <td class="py-2.5 px-3 text-white break-all">[token3].dkim.amazonses.com</td>
                        <td class="py-2.5 px-3 text-[#64748B]">1 Hour</td>
                        <td class="py-2.5 px-3 text-[#94A3B8] font-sans">Easy-DKIM Key 3</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 text-purple-400 font-bold">TXT</td>
                        <td class="py-2.5 px-3">_dmarc.marketing</td>
                        <td class="py-2.5 px-3 text-white break-all">v=DMARC1; p=quarantine; rua=mailto:dmarc@marketing.nimadtech.in; pct=100; adkim=r; aspf=r;</td>
                        <td class="py-2.5 px-3 text-[#64748B]">1 Hour</td>
                        <td class="py-2.5 px-3 text-[#94A3B8] font-sans">DMARC Strict Policy</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 text-amber-400 font-bold">MX</td>
                        <td class="py-2.5 px-3">marketing</td>
                        <td class="py-2.5 px-3 text-white break-all">10 feedback-smtp.ap-south-1.amazonses.com</td>
                        <td class="py-2.5 px-3 text-[#64748B]">1 Hour</td>
                        <td class="py-2.5 px-3 text-[#94A3B8] font-sans">Custom MAIL FROM Bounce MX</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleKeyVisibility() {
    const input = document.getElementById('openaiKeyInput');
    const icon = document.getElementById('keyEyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

async function testOpenAiConnection() {
    const btn = document.getElementById('testAiBtn');
    const alertBox = document.getElementById('ajaxAlert');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Testing Connection...';

    alertBox.className = 'hidden';

    try {
        const res = await fetch("{{ route('marketing.settings.test-openai') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        const data = await res.json();
        
        alertBox.classList.remove('hidden');
        if (data.success) {
            alertBox.className = 'p-4 rounded-xl text-xs font-medium border bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
            alertBox.innerHTML = '<i class="fa-solid fa-circle-check mr-2"></i>' + data.message;
        } else {
            alertBox.className = 'p-4 rounded-xl text-xs font-medium border bg-rose-500/10 text-rose-400 border-rose-500/20';
            alertBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation mr-2"></i>' + data.message;
        }
    } catch (err) {
        alertBox.classList.remove('hidden');
        alertBox.className = 'p-4 rounded-xl text-xs font-medium border bg-rose-500/10 text-rose-400 border-rose-500/20';
        alertBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation mr-2"></i> Error contacting server: ' + err.message;
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-brain"></i> Test OpenAI Connection';
    }
}
</script>
@endpush
@endsection
