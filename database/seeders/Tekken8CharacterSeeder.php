<?php

namespace Database\Seeders;

use App\Models\Character;
use Illuminate\Database\Seeder;

class Tekken8CharacterSeeder extends Seeder
{
    public function run()
    {
        $characters = [
            ['name' => 'Alisa Bosconovitch', 'notes' => 'Alisa offers strong zoning and mix-up potential, but lacks consistent pressure and can be vulnerable when cornered.'],
            ['name' => 'Asuka Kazama', 'notes' => 'Asuka excels at punishing mistakes with her defensive tools but struggles against aggressive opponents due to limited offensive options.'],
            ['name' => 'Azucena', 'notes' => 'Azucena brings unique sidestepping stances and powerful kicks, making her unpredictable in high-level play.'],
            ['name' => 'Bryan Fury', 'notes' => 'Bryan delivers high damage output but requires precise execution and timing, making him challenging for beginners.'],
            ['name' => 'Claudio Serafino', 'notes' => 'Claudio boasts a balanced toolkit with strong pokes and zoning, suitable for players seeking versatility.'],
            ['name' => 'Devil Jin', 'notes' => 'Devil Jin combines speed with powerful attacks but has a high skill ceiling, demanding mastery for optimal performance.'],
            ['name' => 'Feng Wei', 'notes' => 'Feng offers excellent counter-hit tools and evasive movements, making him effective against aggressive playstyles.'],
            ['name' => 'Hwoarang', 'notes' => 'Hwoarang provides dynamic stance transitions and pressure but requires deep understanding to master his complexity.'],
            ['name' => 'Jack-8', 'notes' => 'Jack-8 delivers devastating damage and strong zoning but lacks mobility, making him susceptible to fast opponents.'],
            ['name' => 'Jin Kazama', 'notes' => 'Jin is a well-rounded character with fast pokes and powerful punishment tools, making him a tournament favorite.'],
            ['name' => 'Jun Kazama', 'notes' => 'Jun offers strong defensive tools and healing abilities but lacks offensive pressure, requiring strategic play.'],
            ['name' => 'Kazuya Mishima', 'notes' => 'Kazuya delivers high damage and strong mix-ups but has a high skill ceiling, demanding precision in execution.'],
            ['name' => 'King', 'notes' => 'King provides powerful throws and mix-ups but requires precise timing and execution to maximize effectiveness.'],
            ['name' => 'Kuma', 'notes' => 'Kuma offers unique movement and mix-ups but lacks traditional tools, making him unconventional in high-level play.'],
            ['name' => 'Lars Alexandersson', 'notes' => 'Lars combines speed with versatile attacks but lacks consistent pressure, requiring tactical play.'],
            ['name' => 'Lee Chaolan', 'notes' => 'Lee offers deceptive mix-ups and fast pokes but requires precise execution and timing to be effective.'],
            ['name' => 'Leo Kliesen', 'notes' => 'Leo provides strong pokes and counter-hit tools but lacks high damage output, requiring strategic play.'],
            ['name' => 'Leroy Smith', 'notes' => 'Leroy delivers strong zoning and counter-hit tools but lacks consistent pressure, making him susceptible to aggressive opponents.'],
            ['name' => 'Lili', 'notes' => 'Lili offers aerial mobility and mix-ups but lacks traditional tools, making her unconventional in high-level play.'],
            ['name' => 'Ling Xiaoyu', 'notes' => 'Ling provides fast movements and mix-ups but requires precise execution and timing to be effective.'],
            ['name' => 'Marshall Law', 'notes' => 'Marshall delivers fast pokes and strong pressure but lacks high damage output, requiring strategic play.'],
            ['name' => 'Miary Zo', 'notes' => 'Miary offers unique movement and mix-ups but lacks traditional tools, making her unconventional in high-level play.'],
            ['name' => 'Nina Williams', 'notes' => 'Nina provides strong mix-ups and pressure but requires precise execution and timing to maximize effectiveness.'],
            ['name' => 'Panda', 'notes' => 'Panda offers unique movement and mix-ups but lacks traditional tools, making her unconventional in high-level play.'],
            ['name' => 'Paul Phoenix', 'notes' => 'Paul delivers high damage and strong mix-ups but lacks consistent pressure, requiring strategic play.'],
            ['name' => 'Reina', 'notes' => 'Reina provides strong zoning and counter-hit tools but lacks consistent pressure, making her susceptible to aggressive opponents.'],
            ['name' => 'Raven', 'notes' => 'Raven offers evasive movements and mix-ups but requires precise execution and timing to be effective.'],
            ['name' => 'Shaheen', 'notes' => 'Shaheen delivers strong pokes and zoning but lacks high damage output, requiring strategic play.'],
            ['name' => 'Steve Fox', 'notes' => 'Steve combines speed with strong counter-hit tools but requires precise execution and timing to maximize effectiveness.'],
            ['name' => 'Victor Chevalier', 'notes' => 'Victor offers unique movement and mix-ups but lacks traditional tools, making him unconventional in high-level play.'],
            ['name' => 'Yoshimitsu', 'notes' => 'Yoshimitsu provides unpredictable movements and mix-ups but requires deep understanding to master his complexity.'],
            ['name' => 'Zafina', 'notes' => 'Zafina offers strong defensive tools and evasive movements but lacks consistent pressure, requiring strategic play.'],

            // DLC characters
            ['name' => 'Heihachi Mishima', 'notes' => 'Heihachi has powerful pokes and lethal punishes; great in neutral and for takedowns. Predictable at times and can be punished if you respect his slower strings—watch sidesteps and evasive tools.', 'filename' => 'heihachi_mishima.png'],
            ['name' => 'Eddy Gordo', 'notes' => 'Eddy excels at stance-based mixups and unorthodox movement; great for confusing opponents and creating low/throw mixups. Damage can be inconsistent — struggles vs patient players who punish stance transitions.', 'filename' => 'eddy_gordo.png'],
            ['name' => 'Anna Williams', 'notes' => 'Anna brings fast, deceptive pokes and good pressure strings with clutch mixups. Fragile defensive options; she needs to keep momentum and punish mistakes to stay ahead.'],
            ['name' => 'Fahkumram', 'notes' => 'Fahkumram (heavy hitter) deals massive damage and has excellent range, but many moves are slow and punishable. Strong stage control; exploit openings and avoid reckless aggression.'],
            ['name' => 'Miary Zo', 'notes' => 'Miary Zo uses unusual movement and mixups that disrupt conventional defense — effective in surprise setups and oki. Lower conventional toolkit; requires creative play and matchup knowledge.'],
            ['name' => 'Clive Rosfield', 'notes' => 'Clive Rosfield combines explosive offense and precise spacing, making him strong in mid-range duels. Balanced but requires confident timing and patience — overextending can cost rounds fast.'],
            ['name' => 'Armor King', 'notes' => 'Armor King is a heavy grappler with strong throws and crushing lows; good at frame traps and pressure resets. Movement and pokes are niche — avoid predictable approaches and capitalise on throw mixups.'],
            ['name' => 'Sergei Dragunov', 'notes' => 'Dragunov is a relentless pressure character with excellent counter-hit tools and strong okizeme. Limited lows and linear approaches can be exploited by sidestep-heavy opponents.'],
        ];

        foreach ($characters as $character) {
            Character::create([
                'name' => $character['name'],
                'image' => 'characters/' . str_replace(' ', '_', strtolower($character['name'])) . '.WebP', // relative path only
                'short_name' => null,
                'notes' => $character['notes'],
            ]);
        }
    }
}
