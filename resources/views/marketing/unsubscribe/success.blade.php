<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribed Successfully - Lux Glazing</title>
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
    <div class="max-w-md w-full bg-[#121824] border border-[#222E42] rounded-3xl p-8 shadow-2xl text-center relative overflow-hidden">
        <!-- Success Glow -->
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-3xl shadow-lg mb-5">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <h1 class="text-xl font-bold text-white mb-2">You Have Been Unsubscribed</h1>
        <p class="text-xs text-[#94A3B8] leading-relaxed mb-6">
            <strong class="text-white">{{ $contact->email }}</strong> has been permanently removed from our active marketing lists and entered into our suppression registry.
        </p>

        <div class="bg-[#0B0E14] border border-[#222E42] rounded-2xl p-4 mb-6 text-left space-y-2 text-xs">
            <div class="flex items-center gap-2 text-emerald-400 font-semibold">
                <i class="fa-solid fa-shield-check"></i> Central Suppression Active
            </div>
            <p class="text-[11px] text-[#94A3B8]">
                Even if your email is re-uploaded via future CSV contact lists, our central suppression layer will automatically block all outgoing marketing campaigns to your address.
            </p>
        </div>

        <a href="https://luxglazing.co.uk" class="inline-flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-[#121824] border border-[#E5D2A5]/30 text-[#E5D2A5] hover:bg-[#E5D2A5]/10 font-bold text-xs transition">
            <i class="fa-solid fa-arrow-left"></i> Back to luxglazing.co.uk
        </a>
    </div>
</body>
</html>
