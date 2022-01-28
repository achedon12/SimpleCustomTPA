<?php

namespace achedon\customtpa\commands;

use achedon\customtpa\message\messageManager;
use achedon\customtpa\tasks\delayBeforeTeleport;
use achedon\customtpa\tpa;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;

class tpadeny extends Command{

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []){
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        $cfg = tpa::getConfigs();
        if($sender instanceof Player){
            if(empty($args[0])){
                $sender->sendMessage(messageManager::message('/tpdeny <player>'));
            }else{
                $target = Server::getInstance()->getPlayerByPrefix($args[0]);
                if(!$target instanceof Player){
                    $sender->sendMessage(messageManager::message("$args[0] is not a player"));
                }else{
                    if(!isset(tpa::$REQUEST[$sender->getName()])){
                        $sender->sendMessage(messageManager::message($cfg->getNested("message.noRequest")));
                    }else{
                        $messageToSend = $cfg->getNested("message.refuse.send");
                        $messageConfirm = $cfg->getNested("message.refuse.confirm");
                        $target->sendMessage(str_replace("{player}",$sender->getName(),messageManager::message($messageToSend)));
                        $sender->sendMessage(str_replace("{player}",$target->getName(),messageManager::message($messageConfirm)));
                        unset(tpa::$REQUEST[$sender->getName()]);
                    }

                }
            }
        }
    }


}