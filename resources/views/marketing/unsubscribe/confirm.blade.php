<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribe Confirmation - Lux Glazing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#0B0E14] text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-[#121824] border border-[#222E42] rounded-3xl p-8 shadow-2xl relative overflow-hidden">
        <!-- Accent Glow -->
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#E5D2A5]/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="text-center mb-6">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-[#0B0E14] border border-[#E5D2A5]/30 flex items-center justify-center text-[#E5D2A5] text-2xl shadow-lg mb-4">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
            <h1 class="text-xl font-bold text-white">Unsubscribe from Communications</h1>
            <p class="text-xs text-[#94A3B8] mt-1">Lux Glazing Architectural Glass & Bifold Systems</p>
        </div>

        <div class="bg-[#0B0E14] border border-[#222E42] rounded-2xl p-4 mb-6">
            <div class="text-[11px] text-[#64748B] uppercase tracking-wider font-semibold">Recipient Email</div>
            <div class="text-sm font-semibold text-white mt-0.5 break-all">{{ $contact->email }}</div>
            @if($contact->name)
                <div class="text-xs text-[#94A3B8] mt-1">{{ $contact->name }} @if($contact->company) • {{ $contact->company }} @endif</div>
            @endif
        </div>

        <form action="{{ route('marketing.unsubscribe.process', $contact->unsubscribe_token) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-[#94A3B8] mb-1.5">Optional: Help us improve (Reason)</label>
                <select name="reason" class="w-full bg-[#0B0E14] border border-[#222E42] rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-[#E5D2A5]">
                    <option value="No longer interested">No longer interested in glazing products</option>
                    <option value="Too many emails">Too frequent emails</option>
                    <option value="Never signed up">I did not sign up or give consent</option>
                    <option value="Project completed">Our project is already completed</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <p class="text-[11px] text-[#64748B] leading-relaxed">
                By clicking below, you will be permanently removed from all Lux Glazing commercial and marketing broadcasts. Your email will be added to our central suppression list in compliance with GDPR and PECR.
            </p>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-user-slash"></i> Confirm Unsubscribe
                </button>
            </div>
        </form>

        <div class="mt-6 pt-6 border-t border-[#222E42] text-center">
            <a href="https://luxglazing.co.uk" class="text-xs text-[#94A3B8] hover:text-[#E5D2A5] transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Return to Lux Glazing Website
            </a>
        </div>
    </div>
</body>
</html>
