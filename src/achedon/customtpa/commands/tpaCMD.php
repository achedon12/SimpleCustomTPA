<?php

namespace achedon\customtpa\commands;

use achedon\customtpa\message\messageManager;
use achedon\customtpa\tasks\delay;
use achedon\customtpa\tpa;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;

class tpaCMD extends Command{

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []){
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        $cfg = tpa::getConfigs();
        if($sender instanceof Player){
            if(count($args) != 1){
                $sender->sendMessage(messageManager::message("/tpa <player>"));
            }else{
                $player = Server::getInstance()->getPlayerByPrefix($args[0]);
                if(!$player instanceof Player){
                    $sender->sendMessage(messageManager::message("$player is not a player"));
                }else{
                    tpa::getInstance()->getScheduler()->scheduleRepeatingTask(new delay($player,$cfg->get("timeBeforeDeleteRequest")),20);
                    $messageToSend = $cfg->getNested("message.tpa.send");
                    $messageConfirm = $cfg->getNested("message.tpa.confirm");
                    $sender->sendMessage(str_replace("{player}",$player->getName(),messageManager::message($messageConfirm)));
                    $player->sendMessage(str_replace("{player}",$sender->getName(),messageManager::message($messageToSend)));
                    tpa::$REQUEST[$player->getName()] = "tpa";
                }

            }
        }
    }
}