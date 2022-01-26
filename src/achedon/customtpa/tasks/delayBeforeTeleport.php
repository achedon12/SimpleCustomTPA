<?php

namespace achedon\customtpa\tasks;

use achedon\customtpa\message\messageManager;
use achedon\customtpa\tpa;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;

class delayBeforeTeleport extends Task{

    private $player;
    private $time;
    private $target;
    private $method;

    public function __construct(Player $player, Player $target, int $time,string $method){
        $this->time = $time;
        $this->player = $player;
        $this->target = $target;
        $this->method = $method;
    }

    public function onRun(): void{
        $cfg = tpa::getConfigs();
        if($this->time == 0){
            $this->getHandler()->cancel();

            $this->player->sendMessage(messageManager::message(str_replace("{player}",$this->target->getName(),$cfg->getNested("message.teleport"))));
            if($this->method == "tpa"){
                $this->target->teleport($this->player->getPosition());
            }elseif($this->method == "tpahere"){
                $this->player->teleport($this->target->getPosition());
            }
        }
        $this->player->sendActionBarMessage(str_replace("{time}",$this->time,$cfg->getNested("delayBeforeTeleportation.message")));
        var_dump("");
        $this->time--;
    }
}