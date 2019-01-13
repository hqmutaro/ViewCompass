<?php

namespace nitf\pmmp\viewcompass;

use pocketmine\plugin\Plugin;
use pocketmine\scheduler\Task;

class CompassTask extends Task{

    private $plugin;

    public function __construct(Plugin $plugin){
        $this->plugin = $plugin;
    }

    public function onRun(int $tick): void{
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player){
            $compass = ViewCompassPlugin::getCompass($player->getYaw());
            $player->sendTip($compass);
        }
    }
}