<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 17.06.2017
     * Time: 02:15
     */

    namespace Locrian\Tests\Conf;

    use Locrian\Conf\Conf;
    use Locrian\IO\File;
    use PHPUnit_Framework_TestCase;

    class ConfTest extends PHPUnit_Framework_TestCase{

        /**
         * @var \Locrian\Conf\Conf
         */
        private $conf;

        protected function setUp(){
            $this->conf = new Conf("tests/Conf/files/tk2.conf", true, "tests/Conf/files/");
        }

        protected function tearDown(){
            $file = new File($this->conf->getCacheFilePath());
            self::assertTrue($file->remove());
        }

        public function testCacheFile(){
            $file = new File($this->conf->getCacheFilePath());
            self::assertTrue($file->exists());
        }

        public function testCacheFilePath(){
            self::assertEquals("tests/Conf/files/" . md5("tk2.conf") . ".cache", $this->conf->getCacheFilePath());
        }

        public function testFind(){
            self::assertEquals("development", $this->conf->find("Application.type"));
            self::assertEquals("en-us", $this->conf->find("Application.lang"));
            self::assertEquals("development or development the rest", $this->conf->find("Application.varUsage"));
            self::assertTrue($this->conf->find("Application.Http.Cookie.crypt"));
            self::assertEquals([1, 2, 3], $this->conf->find("Application.Http.Cookie.carr"));
            self::assertEquals("Locrian\Conf\Conf::class", $this->conf->find("Application.Http.Cookie.cls"));
            self::assertEquals(["value1", "value2"], $this->conf->find("Application.Array.Arr"));
            self::assertEquals(["val1"], $this->conf->find("Application.Array.arr2"));
        }

        public function testFindAll(){
            $arr = [
                "Cookie" => [
                    "crypt" => true,
                    "carr" => [ 1, 2, 3 ],
                    "cls" => "Locrian\\Conf\\Conf::class"
                ]
            ];
            self::assertEquals($arr, $this->conf->findAll("Application.Http"));
        }

    }