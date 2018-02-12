<?php

namespace MoySklad\Components;

/**
 * Filter query is used with ListQuery::filter()
 * Class FilterQuery
 * @package MoySklad\Components
 */
class FilterQuery{
    private
        $queryBuffer = [];

    /**
     * Field = Value
     * @param $field
     * @param $value
     * @return $this
     */
    public function eq($field, $value){
        $this->queryBuffer[] = "$field=$value";
        return $this;
    }

    /**
     * Field != Value
     * @param $field
     * @param $value
     * @return $this
     */
    public function neq($field, $value){
        $this->queryBuffer[] = "$field!=$value";
        return $this;
    }

    /**
     * Field  > Value
     * @param $field
     * @param $value
     * @return $this
     */
    public function gt($field, $value){
        $this->queryBuffer[] = "$field>$value";
        return $this;
    }

    /**
     * Field < Value
     * @param $field
     * @param $value
     * @return $this
     */
    public function lt($field, $value){
        $this->queryBuffer[] = "$field<$value";
        return $this;
    }

    /**
     * Field >= Value
     * @param $field
     * @param $value
     * @return $this
     */
    public function gte($field, $value){
        $this->queryBuffer[] = "$field>=$value";
        return $this;
    }

    /**
     * Field <= Value
     * @param $field
     * @param $value
     * @return $this
     */
    public function lte($field, $value){
        $this->queryBuffer[] = "$field<=$value";
        return $this;
    }

    /**
     * Get internal query buffer
     * @return array
     */
    public function getBuffer(){
        return $this->queryBuffer;
    }

    /**
     * Convert itself to string
     * @return string
     */
    public function getRaw(){
        return implode(";", $this->queryBuffer);
    }
}
