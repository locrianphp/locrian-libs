<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 20.06.2017
     * Time: 14:37
     */

    namespace Locrian\Tests\Http;

    use Locrian\Http\Uri;
    use PHPUnit_Framework_TestCase;

    class UriTest extends PHPUnit_Framework_TestCase{

        public function testParse(){
            $uri = Uri::parse("http://www.hostname.com/search?q=locrian#test");
            self::assertEquals("http", $uri->getScheme());
            self::assertEquals("/search", $uri->getPath());
            self::assertEquals("test", $uri->getFragment());
            self::assertEquals("www.hostname.com", $uri->getHost());
            self::assertNull($uri->getPassword());
            self::assertNull($uri->getUser());
            self::assertEquals(80, $uri->getPort());
            self::assertEquals("q=locrian", $uri->getQuery());
            self::assertEquals("http://www.hostname.com/search?q=locrian#test", $uri->__toString());
        }

        public function testParse2(){
            $uri = Uri::parse("https://www.hostname.com");
            self::assertEquals("https", $uri->getScheme());
            self::assertNull($uri->getPath());
            self::assertNull($uri->getFragment());
            self::assertEquals("www.hostname.com", $uri->getHost());
            self::assertNull($uri->getPassword());
            self::assertNull($uri->getUser());
            self::assertEquals(443, $uri->getPort());
            self::assertNull($uri->getQuery());
            self::assertEquals("https://www.hostname.com", $uri->__toString());
        }

        public function testParse3(){
            $uri = Uri::parse("ftp://username:password@hostname.com");
            self::assertEquals("ftp", $uri->getScheme());
            self::assertNull($uri->getPath());
            self::assertNull($uri->getFragment());
            self::assertEquals("hostname.com", $uri->getHost());
            self::assertEquals("password", $uri->getPassword());
            self::assertEquals("username", $uri->getUser());
            self::assertEquals(-1, $uri->getPort());
            self::assertNull($uri->getQuery());
            self::assertEquals("ftp://username:password@hostname.com", $uri->__toString());
        }

        public function testSetters(){
            $uri = Uri::parse("https://www.hostname.com");
            $uri->setPort(9090)
                ->setFragment("fr")
                ->setQuery("q=locrian")
                ->setScheme("ws");
            self::assertEquals("ws://www.hostname.com:9090/?q=locrian#fr", $uri->__toString());
        }

        public function testClone(){
            $uri = Uri::parse("https://www.hostname.com");
            $uri2 = $uri->makeClone()
                        ->setPort(9090)
                        ->setFragment("fr")
                        ->setQuery("q=locrian")
                        ->setScheme("ws");
            self::assertEquals("https://www.hostname.com", $uri->__toString());
            self::assertEquals("ws://www.hostname.com:9090/?q=locrian#fr", $uri2->__toString());
        }

    }