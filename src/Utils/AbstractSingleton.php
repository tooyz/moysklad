<?php

namespace MoySklad\Utils;

abstract class AbstractSingleton{
    protected static $instance = null;

    /**
     * @return AbstractSingleton|null
     */
    final public static function instance() {
        if (is_null(static::$instance)) {
            $class = get_called_class();
            static::$instance = new $class();
        }
        return static::$instance;
    }
}