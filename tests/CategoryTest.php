<?php


use BluaBlue\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{

    public function testGetters()
    {
        $category = new Category(['id'=>'123','name'=>'blog']);
        $this->assertSame($category->getName(), 'blog');
        $this->assertSame($category->getId(), '123');
    }

}
