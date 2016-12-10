<?php

namespace MoySklad\Utils;

abstract class AbstractSingleton{
    protected static $instance = null;
    static public function instance() {
        if (is_null(self::$instance)) {
            $class = get_called_class();
            self::$instance = new $class();
        }
        return self::$instance;
    }
}