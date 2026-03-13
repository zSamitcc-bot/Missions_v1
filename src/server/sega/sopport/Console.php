<?php

namespace server\sega\sopport;

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

    public function init() {
    }

}
