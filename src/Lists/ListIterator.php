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
            $i = $this->cursor;
            $this->cursor++;
            return $this->items[$i];
        }
        return null;
    }

    public function hasNext(){
        return $this->cursor < $this->length;
    }

    public function each(callable $cb){
        while ( $item = $this->next() ){
            $cb($item, $this->cursor);
        }
        return $this;
    }
}

