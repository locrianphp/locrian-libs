<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 18.06.2017
     * Time: 21:29
     */

    namespace Locrian\Tests\Http\Session;

    use Locrian\Crypt\HashHMAC;
    use Locrian\Http\Session\Driver\FileDriver;
    use Locrian\Http\Session\Session;
    use Locrian\Http\Session\SessionManager;
    use PHPUnit_Framework_TestCase;

    class SessionManagerTest extends PHPUnit_Framework_TestCase{

        /**
         * @var \Locrian\Http\Session\Driver\FileDriver
         */
        private $driver;

        /**
         * @var \Locrian\Crypt\HashHMAC
         */
        private $hash;

        protected function setUp(){
            $this->driver = new FileDriver(3600, "tests/Http/Session/");
            $this->hash = new HashHMAC("secret");
        }

        public function testProperties(){
            new SessionManager($this->driver, $this->hash, "tests/Http/Session/", 10);
            self::assertTrue(file_exists("tests/Http/Session/session_manager.properties"));
        }

        public function testCreate(){
            $sm = new SessionManager($this->driver, $this->hash, "tests/Http/Session/", 10);
            $s = $sm->createSession();
            self::assertInstanceOf(Session::class, $s);
        }

        public function testGC(){
            $d = new FileDriver(0, "tests/Http/Session/");
            $d->save(new Session("1"));
            $d->save(new Session("2"));
            $d->save(new Session("3"));
            self::assertEquals(3, $d->count());
            $sm = new SessionManager($d, $this->hash, "tests/Http/Session/", 0);
            self::assertEquals(0, $sm->getSessionCount());
        }

        protected function tearDown(){
            $path = "tests/Http/Session/session_manager.properties";
            if( file_exists($path) ){
                unlink($path);
            }
        }

    }