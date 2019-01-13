<?php

namespace nitf\pmmp\viewcompass;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class ViewCompassPlugin extends PluginBase{

    public function onEnable(): void{
        $this->getScheduler()->scheduleRepeatingTask(new CompassTask($this), 1);
    }

    public static function wrap(float $angle): float{
        return (float) ($angle + ceil(-$angle / 360) * 360);
    }

    public static function getCompass(float $direction): string{
        $direction = self::wrap($direction);
        $direction = $direction * 2 / 10;

        $direction += 72;
        
        $width = 25;

        $compass = str_split(str_repeat('|', 72));
        $compass[0] = 'S';

        $compass[9] = 'S';
        $compass[9 + 1] = 'W';

        $compass[(18)] = 'W';

        $compass[(18 + 9)] = 'N';
        $compass[(18 + 9 + 1)] = 'W';

        $compass[36] = 'N';

        $compass[36 + 9] = 'N';
        $compass[36 + 9 + 1] = 'E';

        $compass[54] = 'E';

        $compass[54 + 9] = 'S';
        $compass[54 + 9 + 1] = 'E';

        $compass = (array) array_merge($compass, $compass);
        
        $needles = [
            "|" => "| ",
            "| N|" => TextFormat::BOLD . TextFormat::RED . "N" . TextFormat::RESET . "|",
            "| NE|" => TextFormat::BOLD . TextFormat::YELLOW . "NE" . TextFormat::RESET . "|",
            "| E|" => TextFormat::BOLD . TextFormat::GREEN . "E" . TextFormat::RESET . "|",
            "| SE|" => TextFormat::BOLD . TextFormat::GREEN . "SE" . TextFormat::RESET . "|",
            "| S|" => TextFormat::BOLD . TextFormat::AQUA . "S" . TextFormat::RESET . "|",
            "| SW|" => TextFormat::BOLD . TextFormat::BLUE . "SW" . TextFormat::RESET . "|",
            "| W|" => TextFormat::BOLD . TextFormat::DARK_PURPLE . "W" . TextFormat::RESET . "|",
            "| NW|" => TextFormat::BOLD . TextFormat::LIGHT_PURPLE . "NW" . TextFormat::RESET . "|"
        ];

        $compass = array_slice(array_slice($compass, (int) ($direction - floor((double) $width / 2))), 0, $width);
        foreach ($compass as $key => $value){
            if ($key === 0){
                $strCompass = $value;
            }
            else{
                $strCompass .= $value;
            }
        }
        foreach ($needles as $subject => $replace){
            $strCompass = str_replace($subject, $replace, $strCompass);
            if ($subject === "| NE|"){
                $strCompass = trim(trim($strCompass, 'NWSE'), 'NWSE');
            }
        }
        return $strCompass;
    }
}