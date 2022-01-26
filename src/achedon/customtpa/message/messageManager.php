<?php

namespace achedon\customtpa\message;

use achedon\customtpa\tpa;

class messageManager{

    public static function message(string $message){
        $cfg = tpa::getConfigs();
        $prefix = $cfg->get("prefix");

        return "$prefix $message";
    }
}