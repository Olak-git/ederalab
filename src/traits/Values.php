<?php
namespace src\traits;

trait Values
{
    public function getValues()
    {
        $tab = [];
        $properties = $this->getProperties($this);
        foreach($properties as $property) {
            $methode = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
            if (is_callable(array($this, $methode))) {
                $value = $this->$methode();
                $tab[$property] = is_object($value) ? $value->getValues() : $value;
            }
        }
        return $tab;
    }
}