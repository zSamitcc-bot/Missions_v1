<?php

namespace server\sega;

use pocketmine\plugin\PluginBase;

use server\sega\sopport\Console;

class Loader extends PluginBase {

    public function onEnable()
    {
        $this->getLogger()->notice('

        Missions Plugin | v2

        DC AUTHOR: ru._instance

        Update: 23/07/26
        
        ');

        $this->getLogger()->info('Plugin Enable');

        Console::init();
    }

}
