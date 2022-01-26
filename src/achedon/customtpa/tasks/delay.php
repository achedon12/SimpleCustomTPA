<?php

namespace achedon\customtpa\tasks;

use achedon\customtpa\message\messageManager;
use achedon\customtpa\tpa;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;

class delay extends Task{

    private int $time;
    private $player;

    public function __construct(Player $player, int $time){
        $this->player = $player;
        $this->time = $time;
        var_dump($this->time);
    }

    public function onRun(): void{
        if($this->time == 0){
            if(isset(tpa::$REQUEST[$this->player->getName()])){
                $this->player->sendMessage(messageManager::message("You request have been deleted"));
                var_dump($this->time);
                unset(tpa::$REQUEST[$this->player->getName()]);
                $this->getHandler()->cancel();
            }
        }
        if(!isset(tpa::$REQUEST[$this->player->getName()])){
            $this->getHandler()->cancel();
        }
        $this->time--;
    }
}
