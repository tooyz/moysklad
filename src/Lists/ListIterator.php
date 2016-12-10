<?php

namespace MoySklad\Lists;

class ListIterator{
    public $id;
    private
        $items = [],
        $cursor = 0,
        $length;

    public function __construct(&$items)
    {
        $this->items = $items;
        $this->length = count($items);
        $this->id = rand(1, 0xFFFFFFFF);
    }

    public function next(){
        if ( $this->hasNext() ){
            $this->cursor++;
            return $this->items[$this->cursor];
        }
        return null;
    }

    public function hasNext(){
        return $this->cursor < $this->length - 1;
    }
}

