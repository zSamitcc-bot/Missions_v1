<?php

namespace server\sega\listener;

use pocketmine\block\Block;

use pocketmine\event\Listener;

use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\block\BlockBreakEvent;

use server\sega\Loader;
use server\sega\sopport\Console;

class GameMissions implements Listener {

    public $owner;

    public function __construct(Loader $owner){

        $this->owner = $owner;

    }

    public function PlayerMissions(){

        return $this->owner->getMissionsPlayer();

    }

    public function Quit(PlayerQuitEvent $quit){
        $p = $quit->getPlayer();
        
        $user = $p->getName();

        
        $this->PlayerMissions()->removeMission($user);
    }

    public function MissionsBreak(BlockBreakEvent $break){

        $p = $break->getPlayer();
        
        $b = $break->getBlock();
        
        $user = $p->getName();

        if ($this->PlayerMissions()->getMission($user) === null){
            
            return;
        
        }

        switch($b->getId()){

            case Block::STONE:

                $p->sendMessage(Console::getPrefix(). 'Rompiste un stone');

            break;

            case Block::DIAMOND_ORE:

                $p->sendMessage(Console::getPrefix(). 'Rompiste un diamante');

            break;

        }
    }

}