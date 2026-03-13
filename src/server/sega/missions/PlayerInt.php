<?php

namespace server\sega\missions;

use server\sega\sopport\Console;

abstract class PlayerInt {

    protected $missions = [
        'chest' => 'Pon 7 cofres',
        'ore' => 'Pica %item% x%amount%'
    ];

    abstract public function setMissions(string $player);

    public function getMissionMessage(string $type) {
        return $this->missions[$type] ?? 'chest';
    }

    abstract public function sendMissionMessage($player);
}