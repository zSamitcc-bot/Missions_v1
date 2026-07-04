<?php

namespace server\sega\missions;

use pocketmine\block\Block;
use server\sega\sopport\Console;

class PlayerMission extends PlayerInt {

    protected $cache = [];

    protected $types = [
        "chest",
        "ore",
    ];

    // blockId => [nombre, cantidad minima, cantidad maxima, recompensa en niveles de xp]
    protected $oreBlocks = [
        Block::COAL_ORE     => ['name' => 'Carbón',       'min' => 8, 'max' => 20, 'reward' => 1],
        Block::IRON_ORE     => ['name' => 'Hierro',       'min' => 6, 'max' => 15, 'reward' => 2],
        Block::GOLD_ORE     => ['name' => 'Oro',          'min' => 5, 'max' => 12, 'reward' => 3],
        Block::LAPIS_ORE    => ['name' => 'Lapislázuli',  'min' => 5, 'max' => 12, 'reward' => 2],
        Block::REDSTONE_ORE => ['name' => 'Redstone',     'min' => 5, 'max' => 12, 'reward' => 2],
        Block::DIAMOND_ORE  => ['name' => 'Diamante',     'min' => 2, 'max' => 6,  'reward' => 5],
        Block::EMERALD_ORE  => ['name' => 'Esmeralda',    'min' => 2, 'max' => 6,  'reward' => 5],
    ];

    protected $chestReward = 3;
    protected $chestTarget = 7;

    /**
     * Genera una mision nueva y aleatoria para el jugador.
     */
    public function setMissions(string $player)
    {
        $type = $this->types[array_rand($this->types)];

        if ($type === 'chest') {

            $this->cache[$player] = [
                'type'     => 'chest',
                'target'   => $this->chestTarget,
                'progress' => 0,
                'reward'   => $this->chestReward,
            ];

            return;
        }

        $blockId = array_rand($this->oreBlocks);
        $data = $this->oreBlocks[$blockId];
        $amount = mt_rand($data['min'], $data['max']);

        $this->cache[$player] = [
            'type'     => 'ore',
            'block'    => $blockId,
            'item'     => $data['name'],
            'target'   => $amount,
            'progress' => 0,
            'reward'   => $data['reward'],
        ];
    }

    /**
     * Envia al jugador el estado actual de su mision.
     */
    public function sendMissionMessage($player)
    {
        $user = $player->getName();

        $text = $this->getMissionText($user);

        if ($text === null) {
            return;
        }

        $player->sendMessage(Console::getPrefix() . $text);
    }

    /**
     * Construye el texto de la mision reemplazando los placeholders.
     */
    public function getMissionText(string $player)
    {
        $data = $this->cache[$player] ?? null;

        if ($data === null) {
            return null;
        }

        $template = $this->getMissionMessage($data['type']);

        $template = str_replace('%amount%', (string) $data['target'], $template);
        $template = str_replace('%progress%', (string) $data['progress'], $template);

        if ($data['type'] === 'ore') {
            $template = str_replace('%item%', $data['item'], $template);
        }

        return $template;
    }

    /**
     * Devuelve el tipo de mision actual del jugador ('chest', 'ore' o null).
     * Se mantiene el nombre original getMissions() por compatibilidad.
     */
    public function getMissions(string $player)
    {
        return $this->cache[$player]['type'] ?? null;
    }

    public function getMissionType(string $player)
    {
        return $this->cache[$player]['type'] ?? null;
    }

    public function getMissionTargetBlock(string $player)
    {
        return $this->cache[$player]['block'] ?? null;
    }

    public function getMissionReward(string $player): int
    {
        return $this->cache[$player]['reward'] ?? 0;
    }

    /**
     * Suma progreso a la mision del jugador.
     * Devuelve true si la mision quedo completada.
     */
    public function addProgress(string $player, int $amount = 1): bool
    {
        if (!isset($this->cache[$player])) {
            return false;
        }

        $this->cache[$player]['progress'] += $amount;

        return $this->cache[$player]['progress'] >= $this->cache[$player]['target'];
    }

    public function asMission(string $player): bool
    {
        return isset($this->cache[$player]);
    }

    public function unsetPlayer(string $player)
    {
        if (isset($this->cache[$player])) {
            unset($this->cache[$player]);
        }
    }

    /**
     * Alias retrocompatible: el listener original llamaba a removeMission().
     */
    public function removeMission(string $player)
    {
        $this->unsetPlayer($player);
    }

}
