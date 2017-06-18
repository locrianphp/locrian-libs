<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 19.06.2017
     * Time: 01:18
     */

    namespace Locrian\tests\Http;

    use Locrian\Http\Flash;
    use Locrian\Http\Session\Session;
    use PHPUnit_Framework_TestCase;

    class FlashTest extends PHPUnit_Framework_TestCase{

        public function testFlash(){
            $s = new Session("id");
            $f = new Flash($s);
            $f->setAttribute("test", "val");
            self::assertEquals("val", $s->getAttribute("locrian_flash__test"));
            self::assertEquals("val", $f->getAttribute("test"));
            self::assertNull($f->getAttribute("test"));
            self::assertNull($s->getAttribute("locrian_flash__test"));
            $f->setAttribute("test", "val");
            self::assertTrue($f->hasAttribute("test"));
            $f->removeAttribute("test");
            self::assertNull($f->getAttribute("test"));
            self::assertNull($s->getAttribute("locrian_flash__test"));
        }

    }