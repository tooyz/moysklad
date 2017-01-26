<?php

namespace MoySklad\Utils;

class CommonDate{
    private $input;

    public function __construct($time){
        $this->input = $time;
    }

    public function format(){
        return date("Y-m-d H:i:s", strtotime($this->input));
    }
}