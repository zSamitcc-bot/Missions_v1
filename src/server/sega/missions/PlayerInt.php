<?php

namespace server\sega\missions;

use server\sega\sopport\Console;

abstract class PlayerInt {

    protected $missions = [
        'chest' => 'Pon §b%amount% §fcofres §7(§e%progress%§7/§e%amount%§7)',
        'ore' => 'Pica §b%item% §fx%amount% §7(§e%progress%§7/§e%amount%§7)'
    ];

    abstract public function setMissions(string $player);

    public function getMissionMessage(string $type) {
        return $this->missions[$type] ?? 'chest';
    }

    abstract public function sendMissionMessage($player);
}