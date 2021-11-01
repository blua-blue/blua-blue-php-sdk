<?php

namespace BluaBlue;

class Category
{
    private string $name;
    private string $id;
    use Helper;

    function getName(): string
    {
        return $this->name;
    }
    function getId(): string
    {
        return $this->id;
    }
}