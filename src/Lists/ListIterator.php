<?php

namespace MoySklad\Lists;

/**
 * Iterator for EntityList
 * Class ListIterator
 * @package MoySklad\Lists
 */
final class ListIterator implements \Iterator{
    private
        $items = [],
        $cursor = 0;

    public function __construct(&$items)
    {
        $this->items = $items;
    }

    public function rewind()
    {
        $this->cursor = 0;
    }

    public function valid()
    {
        return array_key_exists($this->cursor, $this->items);
    }

    public function key()
    {
        return $this->cursor;
    }

    public function current()
    {
        return $this->items[$this->cursor];
    }

    public function next()
    {
        ++$this->cursor;
    }
}

