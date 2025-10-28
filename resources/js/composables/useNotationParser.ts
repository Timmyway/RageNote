import { computed, ComputedRef, Ref } from 'vue';

export interface ParsedToken {
    type: 'input' | 'property' | 'symbol' | 'separator';
    value: string;
    icon?: string;
}

const ATOMIC_MAP: Record<string, string> = {
    u: 'u.png',
    uf: 'uf.png',
    f: 'f.png',
    df: 'df.png',
    d: 'd.png',
    db: 'db.png',
    b: 'b.png',
    ub: 'ub.png',
    n: 'n.png',

    U: 'up_hold.png',
    UF: 'up_forward_hold.png',
    F: 'forward_hold.png',
    DF: 'down_forward_hold.png',
    D: 'down_hold.png',
    DB: 'down_back_hold.png',
    B: 'back_hold.png',
    UB: 'up_back_hold.png',
    dash: 'dash.png',

    '1': '1.png',
    '2': '2.png',
    '3': '3.png',
    '4': '4.png',

    '+': 'plus.png',
    ',': 'comma.png',
};

const SPECIAL_INPUT_MAP: Record<string, string> = {
    ws: 'while_standing.png',
    wr: 'while_running.png',
    ssr: 'ssr.png',
    ssl: 'ssl.png',
    qcf: 'qcf.png',
    qcb: 'qcb.png',
    hcf: 'hcf.png',
    hcb: 'hcb.png',
    dp: 'dp.png',
    '1+2': '1+2.png',
    '1+3': '1+3.png',
    '1+4': '1+4.png',
    '2+3': '2+3.png',
    '2+4': '2+4.png',
    '3+4': '3+4.png',
    '1+2+3+4': '1+2+3+4.png',
    ra: 'rage_art.png',
};

const PROPERTY_MAP: Record<string, string> = {
    TND: 'tornado.jpg',
    WSP: 'wallsplat.jpg',
    WBR: 'wall_break.jpg',
    FBR: 'floor_break.WebP',
    BBR: 'balcony_break.png',
    HB: 'heat_burst.jpg',
    HD: 'heat_dash.jpg',
    CH: 'ch.png',
    STB: 'stb.WebP',
    DVK: 'dvk.WebP',
};

export function useNotationParser(input: Ref<string> | ComputedRef<string>) {
    const raw = input;

    const tokens = computed<ParsedToken[]>(() => {
        if (!raw.value) return [];

        const str = raw.value
            .trim()
            .replace(/\s+/g, ' ')
            .replace(/\s*\+\s*/g, '+')
            .replace(/\s*,\s*/g, ',');

        const parts = str.split(' ');
        const out: ParsedToken[] = [];

        for (const part of parts) {
            if (out.length > 0) {
                out.push({ type: 'separator', value: ' ' });
            }

            const propKey = part.toUpperCase();
            if (PROPERTY_MAP[propKey]) {
                out.push({
                    type: 'property',
                    value: propKey,
                    icon: PROPERTY_MAP[propKey],
                });
                continue;
            }

            if (SPECIAL_INPUT_MAP[part]) {
                out.push({
                    type: 'input',
                    value: part,
                    icon: SPECIAL_INPUT_MAP[part],
                });
                continue;
            }

            // Handle direction chains + hold + optional button (ffF, df+3)
            const dirChainMatch = part.match(
                /^([udfb]{1,})([UDFB]?)(?:\+(\d))?$/i,
            );
            if (dirChainMatch) {
                const chain = dirChainMatch[1];
                const hold = dirChainMatch[2];
                const button = dirChainMatch[3];

                for (let i = 0; i < chain.length; i++) {
                    let c = chain[i];
                    if (i === chain.length - 1 && hold) {
                        c = hold; // last direction uses hold icon if uppercase
                    }
                    const icon = ATOMIC_MAP[c] || ATOMIC_MAP[c.toUpperCase()];
                    out.push({ type: 'input', value: c, icon });
                }

                if (button) {
                    const btnIcon = ATOMIC_MAP[button];
                    out.push({ type: 'input', value: button, icon: btnIcon });
                }

                continue;
            }

            const dirPrefixMatch = part.match(
                /^([udfb]{1,2}|ws|wr|ssr|ssl)\+(.+)$/i,
            );
            if (dirPrefixMatch) {
                const dirRaw = dirPrefixMatch[1]; // original input, case-sensitive
                const remainder = dirPrefixMatch[2];

                // Use longest-match mapping
                const DIR_KEYS = ['uf', 'df', 'db', 'ub', 'u', 'd', 'f', 'b'];
                let dirToken =
                    DIR_KEYS.find(
                        (k) => k.toLowerCase() === dirRaw.toLowerCase(),
                    ) || dirRaw;

                // Hold icon only if user typed uppercase for standard directions
                const isHold =
                    ['U', 'D', 'F', 'B', 'UF', 'DF', 'DB', 'UB'].includes(
                        dirRaw.toUpperCase(),
                    ) && dirRaw === dirRaw.toUpperCase();
                const dirIcon = isHold
                    ? ATOMIC_MAP[dirToken.toUpperCase()]
                    : ATOMIC_MAP[dirToken.toLowerCase()] ||
                      SPECIAL_INPUT_MAP[dirToken.toLowerCase()];

                out.push({ type: 'input', value: dirRaw, icon: dirIcon });

                // Handle remainder (multi-button)
                if (SPECIAL_INPUT_MAP[remainder]) {
                    out.push({
                        type: 'input',
                        value: remainder,
                        icon: SPECIAL_INPUT_MAP[remainder],
                    });
                } else {
                    const pieces = remainder.split(/([,+])/g).filter(Boolean);
                    let i = 0;
                    while (i < pieces.length) {
                        if (
                            i + 2 < pieces.length &&
                            pieces[i + 1] === '+' &&
                            /^\d$/.test(pieces[i]) &&
                            /^\d$/.test(pieces[i + 2])
                        ) {
                            const candidate = `${pieces[i]}+${pieces[i + 2]}`;
                            if (SPECIAL_INPUT_MAP[candidate]) {
                                out.push({
                                    type: 'input',
                                    value: candidate,
                                    icon: SPECIAL_INPUT_MAP[candidate],
                                });
                                i += 3;
                                continue;
                            }
                        }
                        const p = pieces[i];
                        const icon =
                            ATOMIC_MAP[p] || ATOMIC_MAP[p.toUpperCase()];
                        out.push({
                            type: icon ? 'input' : 'symbol',
                            value: p,
                            icon,
                        });
                        i++;
                    }
                }

                continue;
            }

            // Direction + motion + button (f+hcf+2)
            const dirMotionMatch = part.match(
                /^([udfb]{1,2})\+([a-z]+)?\+?(\d)?$/i,
            );
            if (dirMotionMatch) {
                const dir = dirMotionMatch[1];
                const motion = dirMotionMatch[2];
                const button = dirMotionMatch[3];

                const dirIcon =
                    ATOMIC_MAP[dir] || ATOMIC_MAP[dir.toUpperCase()];
                out.push({
                    type: dirIcon ? 'input' : 'symbol',
                    value: dir,
                    icon: dirIcon,
                });

                if (motion && SPECIAL_INPUT_MAP[motion]) {
                    out.push({
                        type: 'input',
                        value: motion,
                        icon: SPECIAL_INPUT_MAP[motion],
                    });
                }

                if (button) {
                    const btnIcon = ATOMIC_MAP[button];
                    out.push({ type: 'input', value: button, icon: btnIcon });
                }

                continue;
            }

            // fallback: atomic pieces
            const normalized = part
                .replace(/([udfb]+)(\d)/gi, '$1+$2')
                .replace(/(\d)(\d)/g, '$1,$2');
            const pieces = normalized.split(/([,+])/g).filter(Boolean);
            for (const p of pieces) {
                const icon = ATOMIC_MAP[p] || ATOMIC_MAP[p.toUpperCase()];
                out.push({ type: icon ? 'input' : 'symbol', value: p, icon });
            }
        }

        return out;
    });

    return { raw, tokens };
}
