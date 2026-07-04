<?php

namespace server\sega\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use server\sega\Loader;
use server\sega\sopport\Console;

class MainCommand extends Command {

    public static $owner;

    public function __construct(Loader $owner)
    {
        parent::__construct('missions', 'mission server', 'use: /missions');

        self::$owner = $owner;

    }

    public static function PlayerMissions() {

        return MainCommand::$owner->getMissionsPlayer();

    }

    public function execute(CommandSender $sender, $commandLabel, array $args)
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage(Console::getPrefix(). 'NO GAME');
            return;
        }

        $user = $sender->getName();

        $missions = MainCommand::PlayerMissions();

        if ($missions->asMission($user)) {

            $sender->sendMessage(Console::getPrefix(). '§cYa tienes una mision activa:');

            $missions->sendMissionMessage($sender);

            return;
        }

        $missions->setMissions($user);

        $sender->sendMessage(Console::getPrefix(). '§aTu mision nueva es:');

        $missions->sendMissionMessage($sender);
    }
}