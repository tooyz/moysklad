<?php

namespace MoySklad\Components;

class FilterQuery{
    private
        $queryBuffer = [];

    public function eq($field, $value){
        $this->queryBuffer[] = "$field=$value";
        return $this;
    }

    public function neq($field, $value){
        $this->queryBuffer[] = "$field!=$value";
        return $this;
    }

    public function gt($field, $value){
        $this->queryBuffer[] = "$field>$value";
        return $this;
    }

    public function lt($field, $value){
        $this->queryBuffer[] = "$field<$value";
        return $this;
    }

    public function gte($field, $value){
        $this->queryBuffer[] = "$field>=$value";
        return $this;
    }

    public function lte($field, $value){
        $this->queryBuffer[] = "$field<=$value";
        return $this;
    }

    public function getBuffer(){
        return $this->queryBuffer;
    }

    public function getRaw(){
        return implode(";", $this->queryBuffer);
    }
}