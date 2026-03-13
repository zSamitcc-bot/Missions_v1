<?php

namespace server\sega\sopport;

use server\sega\Loader;

use server\sega\listener\GameMissions;

use server\sega\command\MainCommand;

class Console {

    public function tolower(string $message): string {
        return strtolower($message);
    }

    public function colors(string $message): string {
        return str_replace('&' ,'§', $message);
    }

    public static function getPrefix() {

        $prefix = '§l§eMissions §7|§r§f ';

        if ($prefix === null) {
            return $prefix;
        }
        if ($prefix === '') {
            return $prefix;
        }
        return $prefix;
    }

    public static function init(Loader $plugin) {

        $server = $plugin->getServer();

        $server->getPluginManager()->registerEvents(
            new GameMissions($plugin),
            $plugin
        );

        $server->getCommandMap()->register(
            "missions",
            new MainCommand($plugin)
        );
    }
}