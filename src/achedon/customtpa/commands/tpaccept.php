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

class tpaccept extends Command{


    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []){
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        $cfg = tpa::getConfigs();
        if($sender instanceof Player){
            if(empty($args[0])){
                $sender->sendMessage(messageManager::message('/tpaccept <player>'));
            }else{
                $target = Server::getInstance()->getPlayerByPrefix($args[0]);
                if(!$target instanceof Player){
                    $sender->sendMessage(messageManager::message("$args[0] is not a player"));
                }else{
                    if(!isset(tpa::$REQUEST[$sender->getName()])){
                        $sender->sendMessage(messageManager::message($cfg->getNested("message.noRequest")));
                    }else{
                        (string)$method = tpa::$REQUEST[$sender->getName()];
                        $messageToSend = $cfg->getNested("message.accept.send");
                        $messageConfirm = $cfg->getNested("message.accept.confirm");
                        $target->sendMessage(str_replace("{player}",$sender->getName(),messageManager::message($messageToSend)));
                        $sender->sendMessage(str_replace("{player}",$target->getName(),messageManager::message($messageConfirm)));
                        if($cfg->getNested("delayBeforeTeleportation.set") == "true"){
                            tpa::getInstance()->getScheduler()->scheduleRepeatingTask(new delayBeforeTeleport($sender,$target,$cfg->getNested("delayBeforeTeleportation.time"),$method),20);
                        }else{
                            if($method == "tpa"){
                                $sender->teleport($target->getPosition());
                            }else{
                                $target->teleport($sender->getPosition());
                            }
                        }
                        unset(tpa::$REQUEST[$sender->getName()]);
                    }

                }
            }
        }
    }


}