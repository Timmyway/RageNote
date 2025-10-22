<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name'=>'Heat Engager','slug'=>'heat-engager','description'=>'Moves that engage Heat System','icon_path'=>'storage/icons/heat-engager.png'],
            ['name'=>'Combo Ender','slug'=>'combo-ender','description'=>'Moves that finish combos efficiently','icon_path'=>'storage/icons/combo-ender.png'],
            ['name'=>'Combo Filler','slug'=>'combo-filler','description'=>'Moves to extend combos','icon_path'=>'storage/icons/combo-filler.png'],
            ['name'=>'Launcher','slug'=>'launcher','description'=>'Launch opponent for juggle','icon_path'=>'storage/icons/launcher.png'],
            ['name'=>'Punish','slug'=>'punish','description'=>'Moves that punish unsafe attacks','icon_path'=>'storage/icons/punish.png'],
            ['name'=>'Poke','slug'=>'poke','description'=>'Quick safe moves for space control','icon_path'=>'storage/icons/poke.png'],
            ['name'=>'Power Crush','slug'=>'power-crush','description'=>'Moves that absorb hits','icon_path'=>'storage/icons/power-crush.png'],
            ['name'=>'Finisher','slug'=>'finisher','description'=>'Strong move to finish round','icon_path'=>'storage/icons/finisher.png'],
            ['name'=>'Wallsplat','slug'=>'wallsplat','description'=>'Push opponent to wall for follow-ups','icon_path'=>'storage/icons/wallsplat.png'],
            ['name'=>'Wall Carry','slug'=>'wallcarry','description'=>'Carry opponent to wall for setups','icon_path'=>'storage/icons/wallcarry.png'],
            ['name'=>'Floorbreak','slug'=>'floorbreak','description'=>'Moves that break floor stage','icon_path'=>'storage/icons/floorbreak.png'],
            ['name'=>'Floorbounce','slug'=>'floorbounce','description'=>'Moves that bounce opponent for combos','icon_path'=>'storage/icons/floorbounce.png'],
            ['name'=>'Throw','slug'=>'throw','description'=>'Grapples or command throws','icon_path'=>'storage/icons/throw.png'],
            ['name'=>'Sidestep','slug'=>'sidestep','description'=>'Moves useful for sidestepping','icon_path'=>'storage/icons/sidestep.png'],
            ['name'=>'Whiff Punish','slug'=>'whiff-punish','description'=>'Moves that punish whiffs','icon_path'=>'storage/icons/whiff-punish.png'],
            ['name'=>'Counter Hit','slug'=>'counter-hit','description'=>'Moves with counter-hit effect','icon_path'=>'storage/icons/counter-hit.png'],
            ['name'=>'Pressure','slug'=>'pressure','description'=>'Moves for block strings/mixups','icon_path'=>'storage/icons/pressure.png'],
            ['name'=>'Safe','slug'=>'safe','description'=>'Safe on block','icon_path'=>'storage/icons/safe.png'],
            ['name'=>'Unsafe','slug'=>'unsafe','description'=>'Unsafe if blocked','icon_path'=>'storage/icons/unsafe.png'],
            ['name'=>'Tracking','slug'=>'tracking','description'=>'Tracks sidesteps/rolls','icon_path'=>'storage/icons/tracking.png'],
            ['name'=>'Launcher Setup','slug'=>'launcher-setup','description'=>'Prepares launcher follow-ups','icon_path'=>'storage/icons/launcher-setup.png'],
            ['name'=>'Oki','slug'=>'oki','description'=>'Ground pressure setups','icon_path'=>'storage/icons/oki.png'],
            ['name'=>'Wall Oki','slug'=>'wall-oki','description'=>'Wall okizeme setups','icon_path'=>'storage/icons/wall-oki.png'],
            ['name'=>'Panic','slug'=>'panic','description'=>'Defensive or escape moves','icon_path'=>'storage/icons/panic.png'],
            ['name'=>'Combo Starter','slug'=>'combo-starter','description'=>'Moves that start combos efficiently','icon_path'=>'storage/icons/combo-starter.png'],
            ['name'=>'Filler Tornado','slug'=>'filler-tornado','description'=>'Tornado or filler combo moves','icon_path'=>'storage/icons/filler-tornado.png'],
            ['name'=>'Filler Special','slug'=>'filler-special','description'=>'Situational filler combo links','icon_path'=>'storage/icons/filler-special.png'],
            ['name'=>'Ranged','slug'=>'ranged','description'=>'Moves with good range','icon_path'=>'storage/icons/ranged.png'],
            ['name'=>'Hopkick','slug'=>'hopkick','description'=>'Hopkick setups or pressure','icon_path'=>'storage/icons/hopkick.png'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate($tag);
        }
    }
}
