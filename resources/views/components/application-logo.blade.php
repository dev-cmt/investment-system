@php
    $brandName = $setting->company_name ?? 'InvestHub';
    $brandLogo = $setting->primary_logo_url ?? null;
@endphp

<div {{ $attributes->merge(['class' => 'd-flex align-items-center gap-2']) }}>
    @if($brandLogo)
        <img src="{{ $brandLogo }}" alt="{{ $brandName }}" class="h-10 w-auto max-w-[140px] object-contain rounded-md shadow-sm" />
    @else
        <div style="width: 45px; height: 45px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                <polyline points="16 7 22 7 22 13"></polyline>
            </svg>
        </div>
        <span style="font-size: 1.6rem; font-weight: 800; color: #ffffff; tracking: -0.5px;">{{ $brandName }}</span>
    @endif
</div>
