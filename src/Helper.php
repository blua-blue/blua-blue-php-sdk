<?php

namespace BluaBlue;

trait Helper
{
    function __construct(array $generate = null)
    {
        if ($generate) {
            $this->generate($generate);
        }
        return $this;
    }
    private function generate($staticModel)
    {
        foreach ($staticModel as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
    public function toArray(): array
    {
        $properties =  get_object_vars($this);
        unset($properties['databaseTransactionMode']);
        return $properties;
    }
}