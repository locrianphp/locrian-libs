<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 18.06.2017
     * Time: 20:29
     */

    namespace Locrian\Tests\Http\Session;
    use Locrian\Collections\ArrayList;
    use Locrian\Http\Session\Session;
    use PHPUnit_Framework_TestCase;

    class SessionTest extends PHPUnit_Framework_TestCase{

        /**
         * @var Session
         */
        private $session;

        protected function setUp(){
            $this->session = new Session("dummyId");
            $this->session->setAttribute("n1", "v1");
            $this->session->setAttribute("n2", 2);
            $this->session->setAttribute("n3", 3.8);
            $this->session->setAttribute("n4", true);
            $this->session->setAttribute("n5", new ArrayList([1,2,3]));
        }

        public function testGetId(){
            self::assertEquals("dummyId", $this->session->getId());
        }

        public function testHas(){
            self::assertTrue($this->session->hasAttribute("n1"));
            self::assertTrue($this->session->hasAttribute("n2"));
            self::assertTrue($this->session->hasAttribute("n3"));
            self::assertTrue($this->session->hasAttribute("n4"));
            self::assertTrue($this->session->hasAttribute("n5"));
        }

        public function testGet(){
            self::assertEquals("v1", $this->session->getAttribute("n1"));
            self::assertEquals(2, $this->session->getAttribute("n2"));
            self::assertEquals(3.8, $this->session->getAttribute("n3"));
            self::assertEquals(true, $this->session->getAttribute("n4"));
            self::assertEquals(new ArrayList([1,2,3]), $this->session->getAttribute("n5"));
        }

        public function testGetAttributeNames(){
            $arr = [ "n1", "n2", "n3", "n4", "n5" ];
            self::assertEquals($arr, $this->session->getAttributeNames());
        }

        public function testSetAttribute(){
            $this->session->setAttribute("n1", "v2");
            self::assertEquals("v2", $this->session->getAttribute("n1"));
        }

        public function removeAttribute(){
            $this->session->removeAttribute("n1");
            self::assertFalse($this->session->hasAttribute("n1"));
        }

    }