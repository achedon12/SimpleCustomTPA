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

class tpahere extends Command{

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []){
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        $cfg = tpa::getConfigs();
        if($sender instanceof Player){
           if(empty($args[0])){
               $sender->sendMessage(messageManager::message("/tpahere <player>"));
           }else{
               $target = Server::getInstance()->getPlayerByPrefix($args[0]);
               if(!$target instanceof Player){
                   $sender->sendMessage(messageManager::message("$args[0] is not a player"));
               }else{
                   tpa::getInstance()->getScheduler()->scheduleRepeatingTask(new delay($sender,(int)$cfg->get("timeBeforeDeleteRequest")),20);
                   $messageToSend = $cfg->getNested("message.tpahere.send");
                   $messageConfirm = $cfg->getNested("message.tpahere.confirm");
                   $sender->sendMessage(str_replace("{player}",$target->getName(),messageManager::message($messageConfirm)));
                   $target->sendMessage(str_replace("{player}",$sender->getName(),messageManager::message($messageToSend)));
                   tpa::$REQUEST[$target->getName()] = "tpahere";
               }
           }
        }
    }
}