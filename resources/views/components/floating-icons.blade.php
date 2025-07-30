@php
    $icons = collect(range(1, 80))->map(function ($i) {
        return [
            'top' => rand(0, 95),
            'left' => rand(0, 95),
            'fill' => $i <= 40 ? ($i <= 26 ? 'none' : 'currentColor') : ($i <= 38 ? 'none' : 'currentColor'),
            'color' => $i <= 40 ? 'text-yellow-500' : 'text-green-500',
            'animation' => 'float-' . rand(1, 8), // Random animation class
        ];
    });
@endphp

@foreach ($icons as $icon)
    <div class="floating-icon absolute {{ $icon['color'] }} {{ $icon['animation'] }}" style="top: {{ $icon['top'] }}%; left: {{ $icon['left'] }}%;">
        <svg class="w-5 h-5 opacity-20" fill="{{ $icon['fill'] }}" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
    </div>
@endforeach

<style>
    .floating-icon {
        position: absolute;
    }

    @keyframes float-1 {
        0%   { transform: translate(0, 0) rotate(0deg); }
        25%  { transform: translate(15px, 30px) rotate(90deg); }
        50%  { transform: translate(30px, 0) rotate(180deg); }
        75%  { transform: translate(15px, -30px) rotate(270deg); }
        100% { transform: translate(0, 0) rotate(360deg); }
    }

    @keyframes float-2 {
        0%   { transform: translate(0, 0) rotate(0deg); }
        25%  { transform: translate(-20px, 20px) rotate(-90deg); }
        50%  { transform: translate(-40px, 0) rotate(-180deg); }
        75%  { transform: translate(-20px, -20px) rotate(-270deg); }
        100% { transform: translate(0, 0) rotate(-360deg); }
    }

    @keyframes float-3 {
        0%   { transform: translate(0, 0); }
        25%  { transform: translate(10px, -20px); }
        50%  { transform: translate(-10px, 20px); }
        75%  { transform: translate(20px, -10px); }
        100% { transform: translate(0, 0); }
    }

    @keyframes float-4 {
        0%   { transform: translate(0, 0) scale(1); }
        25%  { transform: translate(10px, 10px) scale(1.1); }
        50%  { transform: translate(20px, 0) scale(1); }
        75%  { transform: translate(10px, -10px) scale(0.9); }
        100% { transform: translate(0, 0) scale(1); }
    }

    .float-1 { animation: float-1 18s infinite linear; }
    .float-2 { animation: float-2 22s infinite linear; }
    .float-3 { animation: float-3 26s infinite ease-in-out; }
    .float-4 { animation: float-4 30s infinite ease-in-out; }
</style>
