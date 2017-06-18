<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 18.06.2017
     * Time: 23:29
     */

    namespace Locrian\Tests\Util;

    use Locrian\IO\File;
    use Locrian\Util\Properties;
    use PHPUnit_Framework_TestCase;

    class PropertiesTest extends PHPUnit_Framework_TestCase{

        /**
         * @var \Locrian\Util\Properties
         */
        private $props;

        /**
         * @var File
         */
        private static $file;

        public static function setUpBeforeClass(){
            self::$file = new File("tests/Util/test.properties");
        }

        protected function setUp(){
            $this->props = new Properties(self::$file);
        }

        public function fillProps(){
            $this->props->setProperty("p1", "v1");
            $this->props->setProperty("p3", 8);
            $this->props->setProperty("p4", true);
            $this->props->setProperty("p5", 8.8);
        }

        public function testGetProperty(){
            $this->fillProps();
            self::assertEquals("v1", $this->props->getString("p1"));
            self::assertEquals(8, $this->props->getInt("p3"));
            self::assertEquals(true, $this->props->getBoolean("p4"));
            self::assertEquals(8.8, $this->props->getDouble("p5"));
        }

        public function testDefaultProperty(){
            self::assertEquals("v1", $this->props->getString("dummyName", "v1"));
            self::assertEquals(15, $this->props->getInt("dummyName", 15));
            self::assertEquals(true, $this->props->getBoolean("dummyName", true));
            self::assertEquals(15.8, $this->props->getDouble("dummyName", 15.8));
        }

        public function testCommit(){
            $this->fillProps();
            self::assertFalse(file_exists(self::$file->getPath()));
            $this->props->commit();
            self::assertTrue(file_exists(self::$file->getPath()));
            unlink(self::$file->getPath());
        }

        public function testClear(){
            $this->fillProps();
            $this->props->clear();
            self::assertEquals(0, $this->props->getInt("dummyName", 0));
        }

        public function testRemoveProperty(){
            $this->fillProps();
            $this->props->removeProperty("p1");
            self::assertEquals(10, $this->props->getInt("p1", 10));
        }

        public function testLoad(){
            $this->fillProps();
            $this->props->commit();
            $p = new Properties(self::$file);
            $p->load();
            self::assertEquals("v1", $p->getString("p1"));
            self::assertEquals(8, $p->getInt("p3"));
            self::assertEquals(true, $p->getBoolean("p4"));
            self::assertEquals(8.8, $p->getDouble("p5"));
            unlink(self::$file->getPath());
        }

    }