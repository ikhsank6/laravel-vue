// Muted flat tones for typographic covers. They stand in for content imagery, so they stay
// in the brand-blue family. Flat fill, no gradient (DESIGN.md). Every tone keeps white text
// above 8:1.
const TONES = [
    '#12365f',
    '#1d4d8a',
    '#0f4c75',
    '#2a4d69',
    '#1b3a5c',
    '#26567a',
];

export function coverTone(seed: string): string {
    let hash = 7;

    for (let i = 0; i < seed.length; i++) {
        hash = (hash * 31 + seed.charCodeAt(i)) >>> 0;
    }

    return TONES[hash % TONES.length];
}
