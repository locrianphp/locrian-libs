<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 26.11.2016
     * Time: 08:42
     */

    namespace Locrian\Tests\Util;

    use PHPUnit_Framework_TestCase;
    use Locrian\Util\Path;

    class PathTest extends PHPUnit_Framework_TestCase{

        public function testAbsolute(){
            self::assertTrue(Path::isAbsolute("/var/www"));
            self::assertTrue(Path::isAbsolute("C:\\users"));
            self::assertTrue(Path::isAbsolute("http://www.google.com"));
            self::assertTrue(Path::isAbsolute("file://c:/dummy/path"));
            self::assertFalse(Path::isAbsolute("relative/path/to/file"));
            self::assertFalse(Path::isAbsolute("relative\\path\\to\\file"));
        }

        public function testJoin(){
            self::assertEquals("/etc/apache2/sites-available/000-default.conf", Path::join("/etc", "apache2", "sites-available", "000-default.conf"));
            self::assertEquals("/etc/apache2/sites-available/000-default.conf", Path::join("/etc/apache2", "sites-available/000-default.conf"));
        }

        public function testNormalize(){
            self::assertEquals("/foo/bar/baz/", Path::normalize("/foo/bar//baz//"));
            self::assertEquals("C:/temp/foo/bar/", Path::normalize("C:\\temp\\\\\\foo\\bar\\")); // "C:/temp/foo/bar/" this is because my os is linux but it proves that it works correctly
            self::assertEquals("/foo/bar/baz/", Path::normalize("/foo\\/bar//baz//"));
        }

    }
