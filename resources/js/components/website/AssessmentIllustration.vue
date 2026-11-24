<script setup lang="ts">
// Original illustration: a participant filling in a competency test. It shows the one thing this
// institution does (assess people), uses only brand colors, and carries no text. The checks on the
// sheet are drawn one by one, and the pencil moves a few pixels as if writing. Both stop under
// reduced motion (see app.css).
const ticks = [
    { y: 8, delay: 600 },
    { y: 30, delay: 1000 },
    { y: 52, delay: 1400 },
];
</script>

<template>
    <svg
        viewBox="0 0 480 340"
        role="img"
        aria-label="Ilustrasi peserta mengerjakan uji kompetensi"
        class="w-full"
    >
        <!-- Wall: a framed certificate -->
        <g>
            <rect
                x="338"
                y="34"
                width="104"
                height="74"
                rx="4"
                fill="#eaf2fb"
            />
            <rect
                x="344"
                y="40"
                width="92"
                height="62"
                rx="2"
                fill="none"
                stroke="#f2b01e"
                stroke-width="1.5"
            />
            <rect x="360" y="52" width="60" height="4" rx="2" fill="#2563a8" />
            <rect
                x="368"
                y="62"
                width="44"
                height="3"
                rx="1.5"
                fill="#9db6d1"
            />
            <rect
                x="364"
                y="70"
                width="52"
                height="3"
                rx="1.5"
                fill="#9db6d1"
            />
            <circle cx="390" cy="88" r="8" fill="#f2b01e" />
            <path
                d="M386 88l3 3 5-6"
                fill="none"
                stroke="#0f2a4a"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
        </g>

        <!-- Floor shadow -->
        <ellipse
            cx="250"
            cy="326"
            rx="200"
            ry="10"
            fill="#081526"
            opacity="0.35"
        />

        <!-- Chair -->
        <g fill="#5b7ba6">
            <rect x="140" y="138" width="14" height="112" rx="7" />
            <rect x="150" y="238" width="104" height="14" rx="6" />
            <rect x="198" y="250" width="9" height="62" />
            <rect x="160" y="310" width="90" height="8" rx="4" />
        </g>

        <!-- Desk -->
        <rect x="236" y="236" width="228" height="12" rx="3" fill="#4a86c5" />
        <rect x="248" y="248" width="10" height="76" fill="#2f6fb3" />
        <rect x="442" y="248" width="10" height="76" fill="#2f6fb3" />

        <!-- Test sheet lying on the desk -->
        <g transform="translate(298 204) skewX(-28)">
            <rect width="124" height="32" rx="2" fill="#ffffff" />
            <g stroke="#9db6d1" stroke-width="2" stroke-linecap="round">
                <path d="M30 9h82M30 17h82M30 25h58" />
            </g>
            <g fill="none" stroke="#2563a8" stroke-width="1.4">
                <rect x="9" y="5" width="9" height="8" rx="1.5" />
                <rect x="9" y="15" width="9" height="8" rx="1.5" />
            </g>
            <path
                class="cert-tick"
                style="--d: 600ms"
                d="M10.5 9l2.4 2.6 4.4-5"
                fill="none"
                stroke="#2563a8"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
            <path
                class="cert-tick"
                style="--d: 1100ms"
                d="M10.5 19l2.4 2.6 4.4-5"
                fill="none"
                stroke="#2563a8"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
        </g>

        <!-- Person: legs, torso, head -->
        <g>
            <path
                d="M188 242H282"
                stroke="#3a4a63"
                stroke-width="24"
                stroke-linecap="round"
            />
            <path
                d="M282 242V312"
                stroke="#3a4a63"
                stroke-width="20"
                stroke-linecap="round"
            />
            <rect
                x="270"
                y="306"
                width="36"
                height="13"
                rx="6"
                fill="#eaf2fb"
            />

            <rect
                x="172"
                y="146"
                width="66"
                height="104"
                rx="28"
                fill="#2563a8"
                transform="rotate(10 205 198)"
            />
            <rect
                x="214"
                y="132"
                width="16"
                height="22"
                rx="6"
                fill="#e2b48c"
            />
            <circle cx="224" cy="116" r="25" fill="#f0c8a0" />
            <path
                d="M199 114c-1-17 12-27 28-26 14 1 24 11 22 24-8-10-20-13-30-9-7 2-13 6-20 11z"
                fill="#0f2a4a"
            />
            <circle cx="236" cy="120" r="2.2" fill="#0f2a4a" />
            <path
                d="M232 130q5 3 9 0"
                fill="none"
                stroke="#b9805a"
                stroke-width="2"
                stroke-linecap="round"
            />
        </g>

        <!-- Arm and pencil: the writing motion -->
        <g class="write-motion">
            <path
                d="M222 170L250 212L304 218"
                fill="none"
                stroke="#2563a8"
                stroke-width="17"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
            <circle cx="310" cy="219" r="9" fill="#f0c8a0" />
            <path
                d="M310 219L338 232"
                stroke="#f2b01e"
                stroke-width="5"
                stroke-linecap="round"
            />
            <path
                d="M336 231l6 3"
                stroke="#0f2a4a"
                stroke-width="3"
                stroke-linecap="round"
            />
        </g>

        <!-- Check bubble: the decision the test leads to -->
        <g class="cert-seal" style="--d: 1800ms">
            <circle cx="395" cy="170" r="22" fill="#f2b01e" />
            <circle
                cx="395"
                cy="170"
                r="17"
                fill="none"
                stroke="#0f2a4a"
                stroke-width="1.2"
            />
            <path
                d="M386 170l7 7 13-15"
                fill="none"
                stroke="#0f2a4a"
                stroke-width="4"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
        </g>
    </svg>
</template>
