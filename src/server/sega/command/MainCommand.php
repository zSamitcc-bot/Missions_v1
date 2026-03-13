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
        }
        $user = $sender->getName();

        if (MainCommand::PlayerMissions()->getMissions($user) === 'chest' || MainCommand::PlayerMissions()->getMissions($user) === 'ore') {

            $sender->sendMessage(Console::getPrefix(). 'Estas en una mission');

            return;
        }

        MainCommand::PlayerMissions()->setMissions($user);
        
        MainCommand::PlayerMissions()->sendMissionMessage($sender);

        $sender->sendMessage(Console::getPrefix(). 'tu mission nueva es &7: &b'. MainCommand::PlayerMissions()->getMissions($user));
        
        
    }
}