<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 18.06.2017
     * Time: 20:23
     */

    namespace Locrian\Tests\Http\Session;

    use Locrian\Collections\ArrayList;
    use Locrian\Http\Session\Driver\FileDriver;
    use Locrian\Http\Session\Session;
    use PHPUnit_Framework_TestCase;

    class FileDriverTest extends PHPUnit_Framework_TestCase{

        private $lifeTime = 60*60; // 1 hour

        private $sessionDir = "tests/Http/Session/";

        /**
         * @var Session
         */
        private $testSession;

        protected function setUp(){
            $s = new Session("dummyId");
            $s->setAttribute("n1", "v1");
            $s->setAttribute("n2", 2);
            $s->setAttribute("n3", 3.8);
            $s->setAttribute("n4", true);
            $s->setAttribute("n5", new ArrayList([1,2,3]));
            $this->testSession = $s;
        }

        public function testSave(){
            $fd = new FileDriver($this->lifeTime, $this->sessionDir);
            $fd->save($this->testSession);
            $path = "tests/Http/Session/" . $this->testSession->getId() . "_" . $this->testSession->getCreationTime() . ".session";
            self::assertTrue(file_exists($path));
            unlink($path);
        }

        public function testFind(){
            $fd = new FileDriver($this->lifeTime, $this->sessionDir);
            $fd->save($this->testSession);
            $path = "tests/Http/Session/" . $this->testSession->getId() . "_" . $this->testSession->getCreationTime() . ".session";
            $sess = $fd->find($this->testSession->getId());
            self::assertNotNull($sess);
            unlink($path);
        }

        public function testExpiredFind(){
            $fd = new FileDriver(0, $this->sessionDir); // 0 seconds
            $fd->save($this->testSession);
            $sess = $fd->find($this->testSession->getId());
            self::assertNull($sess);
        }

        public function testSaveSession(){
            $fd = new FileDriver($this->lifeTime, $this->sessionDir);
            $fd->save($this->testSession);
            $this->testSession->setAttribute("n1", "v2");
            $fd->save($this->testSession);
            $sess = $fd->find($this->testSession->getId());
            self::assertEquals("v2", $sess->getAttribute("n1"));
            $fd->remove("n1");
        }

        public function testFindValues(){
            $fd = new FileDriver($this->lifeTime, $this->sessionDir);
            $fd->save($this->testSession);
            $path = "tests/Http/Session/" . $this->testSession->getId() . "_" . $this->testSession->getCreationTime() . ".session";
            $sess = $fd->find($this->testSession->getId());
            self::assertEquals("v1", $sess->getAttribute("n1"));
            self::assertEquals(2, $sess->getAttribute("n2"));
            self::assertEquals(3.8, $sess->getAttribute("n3"));
            self::assertEquals(true, $sess->getAttribute("n4"));
            self::assertEquals(new ArrayList([1,2,3]), $sess->getAttribute("n5"));
            unlink($path);
        }

        public function testRemove(){
            $fd = new FileDriver($this->lifeTime, $this->sessionDir);
            $fd->save($this->testSession);
            $path = "tests/Http/Session/" . $this->testSession->getId() . "_" . $this->testSession->getCreationTime() . ".session";
            $fd->remove($this->testSession->getId());
            self::assertNull($fd->find($this->testSession->getId()));
            self::assertFalse(file_exists($path));
        }

        public function testCount(){
            $fd = new FileDriver($this->lifeTime, $this->sessionDir);
            self::assertEquals(0, $fd->count());
            $fd->save($this->testSession);
            $fd->save(new Session("dummyId2"));
            self::assertEquals(2, $fd->count());
            $fd->remove($this->testSession->getId());
            $fd->remove("dummyId2");
        }

        public function testGC(){
            $fd = new FileDriver(0, $this->sessionDir); // 0 seconds
            $fd->save($this->testSession);
            self::assertEquals(1, $fd->count());
            $fd->destroyExpiredSessions();
            self::assertEquals(0, $fd->count());
        }

    }