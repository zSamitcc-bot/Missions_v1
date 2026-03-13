<?php

namespace server\sega\missions;

use server\sega\sopport\Console;

class PlayerMission extends PlayerInt {

    protected $cache = [];

    protected $missions = [
        "chest",
        "ore",
    ];

    public function setMissions(string $player){

        $mission = $this->missions[array_rand($this->missions)];

        $this->cache[$player] = $mission;
    }

    public function sendMissionMessage($player){

        $user = $player->getName();

        $type = $this->getMissions($user);

        if($type === null){
            return;
        }

        $msg = $this->getMissionMessage($type);

        if ($msg !== null){
            $player->sendMessage(Console::getPrefix() . $msg);
        }
    }

    public function getMissions(string $player){
        return $this->cache[$player] ?? null;
    }

    public function asMission(string $player): bool{
        return isset($this->cache[$player]);
    }

    public function unsetPlayer(string $player){

        if(isset($this->cache[$player])){
            unset($this->cache[$player]);
        }
    }

}