<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 25.11.2016
     * Time: 20:33
     */

    namespace Locrian\Tests\Util;

    use Locrian\Util\StringUtils;
    use PHPUnit_Framework_TestCase;

    class StringUtilsTest extends PHPUnit_Framework_TestCase{

        public function testStringUtils(){
            $hello = "Hello, World";
            self::assertEquals(",", StringUtils::charAt(5, $hello));
            self::assertEquals(true, StringUtils::endWith("World", $hello));
            self::assertEquals(true, StringUtils::startsWith("Hello", $hello));
            self::assertEquals(true, StringUtils::contains("Hello", $hello));
            self::assertEquals(1, StringUtils::indexOf("ello", $hello));
            self::assertEquals(true, StringUtils::isNumeric("65464189461"));
            self::assertEquals(false, StringUtils::isNumeric($hello));
            self::assertEquals(true, StringUtils::equals($hello, "Hello, World"));
            self::assertEquals(true, StringUtils::isEmpty(""));
            self::assertEquals(true, StringUtils::isBlank("\n\r\t"));
            self::assertEquals("HELLO, WORLD", StringUtils::upper($hello));
            self::assertEquals("hello, world", StringUtils::lower($hello));
            self::assertEquals("Hllo, World", StringUtils::remove(1, $hello));
            self::assertEquals("Hell, World", StringUtils::remove("o", $hello));
            self::assertEquals("Hell, Wrld", StringUtils::removeAll("o", $hello));
            self::assertEquals("dlroW ,olleH", StringUtils::reverse($hello));
        }

    }