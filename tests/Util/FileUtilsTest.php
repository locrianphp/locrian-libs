<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 18.06.2017
     * Time: 23:00
     */

    namespace Locrian\Tests\Util;

    use Locrian\IO\File;
    use Locrian\Util\FileUtils;
    use PHPUnit_Framework_TestCase;

    class FileUtilsTest extends PHPUnit_Framework_TestCase{

        private $content = "Hello World";

        /**
         * @var File
         */
        private static $file;

        public static function setUpBeforeClass(){
            self::$file = new File("tests/Util/test.txt");
        }

        public function testWriteRead(){
            self::assertFalse(file_exists(self::$file->getPath()));
            FileUtils::writeText(self::$file, $this->content);
            self::assertTrue(file_exists(self::$file->getPath()));
            self::assertEquals($this->content, FileUtils::readText(self::$file));
            unlink(self::$file->getPath());
        }

        public function testOverwrite(){
            FileUtils::writeText(self::$file, $this->content);
            self::assertEquals($this->content, FileUtils::readText(self::$file));
            FileUtils::writeText(self::$file, $this->content . "-----", FileUtils::OVERWRITE);
            self::assertEquals($this->content . "-----", FileUtils::readText(self::$file));
            unlink(self::$file->getPath());
        }

        public function testAppend(){
            FileUtils::writeText(self::$file, $this->content);
            self::assertEquals($this->content, FileUtils::readText(self::$file));
            FileUtils::writeText(self::$file, "-----", FileUtils::APPEND);
            self::assertEquals($this->content . "-----", FileUtils::readText(self::$file));
            unlink(self::$file->getPath());
        }

    }