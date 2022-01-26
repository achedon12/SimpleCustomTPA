<?php

namespace achedon\customtpa;

use achedon\customtpa\commands\tpaccept;
use achedon\customtpa\commands\tpaCMD;
use achedon\customtpa\commands\tpadeny;
use achedon\customtpa\commands\tpahere;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class tpa extends PluginBase{

    /** @var tpa $instance*/
    private static $instance;

    /**@var Player[] $REQUEST */
    public static array $REQUEST = [];

    protected function onEnable(): void{
        self::$instance = $this;
        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");

        $this->getServer()->getCommandMap()->register('Commands', new tpaCMD("tpa","send a teleport request","/tpa"));
        $this->getServer()->getCommandMap()->register('Commands', new tpahere("tpahere","send a teleport request","/tpahere"));
        $this->getServer()->getCommandMap()->register('Commands', new tpadeny("tpdeny","refuse a teleport request","/tpadeny"));
        $this->getServer()->getCommandMap()->register('Commands', new tpaccept("tpaccept","accept a teleport request","/tpaccept"));
    }

    protected function onDisable(): void{
        $this->saveResource("config.yml");
    }

    public static function getConfigs() : Config{
        return new Config(self::$instance->getDataFolder() . "config.yml", Config::YAML);
    }

    public static function getInstance(): self{
        return self::$instance;
    }

}