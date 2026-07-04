<?php

namespace server\sega\listener;

use pocketmine\block\Block;

use pocketmine\event\Listener;

use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;

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

        $this->PlayerMissions()->unsetPlayer($user);
    }

    public function MissionsBreak(BlockBreakEvent $break){

        $p = $break->getPlayer();

        $b = $break->getBlock();

        $user = $p->getName();

        $missions = $this->PlayerMissions();

        if (!$missions->asMission($user)) {
            return;
        }

        if ($missions->getMissionType($user) !== 'ore') {
            return;
        }

        if ($b->getId() !== $missions->getMissionTargetBlock($user)) {
            return;
        }

        $this->handleProgress($p, $missions);
    }

    public function MissionsPlace(BlockPlaceEvent $place){

        $p = $place->getPlayer();

        $b = $place->getBlock();

        $user = $p->getName();

        $missions = $this->PlayerMissions();

        if (!$missions->asMission($user)) {
            return;
        }

        if ($missions->getMissionType($user) !== 'chest') {
            return;
        }

        if ($b->getId() !== Block::CHEST) {
            return;
        }

        $this->handleProgress($p, $missions);
    }

    private function handleProgress($player, $missions){

        $user = $player->getName();

        $completed = $missions->addProgress($user, 1);

        if ($completed) {

            $reward = $missions->getMissionReward($user);

            $player->sendMessage(Console::getPrefix() . '§a¡Mision completada! §fRecibiste §e' . $reward . ' §fniveles de experiencia.');

            if (method_exists($player, 'addXpLevels')) {
                $player->addXpLevels($reward);
            }

            $missions->unsetPlayer($user);

            return;
        }

        $missions->sendMissionMessage($player);
    }

}
