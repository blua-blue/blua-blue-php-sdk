<?php


use BluaBlue\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    function testGettersSetters()
    {
        $image = new Image(['id'=>'123','inserter_user_id'=>'456']);
        foreach (['Format','Path'] as $both){
            $setter = 'set'.$both;
            $getter = 'get'.$both;
            $this->assertSame($image->$setter('a')->$getter(),'a');
        }
        foreach (['getId','getInserterUserId'] as $getterOnly){
            $this->assertIsString($image->$getterOnly());
        }
    }
}
