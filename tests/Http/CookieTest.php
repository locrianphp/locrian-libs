<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 18.06.2017
     * Time: 14:22
     */

    namespace Locrian\Tests\Http;

    use Locrian\Crypt\HashHMAC;
    use Locrian\Http\Cookie;
    use PHPUnit_Framework_TestCase;

    class CookieTest extends PHPUnit_Framework_TestCase{

        /**
         * @var HashHMAC
         */
        private $hash;

        protected function setUp(){
            $this->hash = new HashHMAC("secret");
            $_COOKIE = [
                "c1" => "v1",
                "c2" => "v2",
                $this->hash->sha1("c3") => "v3",
                $this->hash->sha1("c4") => "v4"
            ];
        }

        public function testHas(){
            $cookie = new Cookie();
            self::assertTrue($cookie->has("c1"));
            self::assertTrue($cookie->has("c2"));
            self::assertFalse($cookie->has("c3"));
            self::assertFalse($cookie->has("c4"));
        }

        public function testHasWithHash(){
            $cookie = new Cookie($this->hash);
            self::assertTrue($cookie->has("c4"));
            self::assertTrue($cookie->has("c3"));
            self::assertFalse($cookie->has("c2"));
            self::assertFalse($cookie->has("c1"));
        }

        public function testGet(){
            $cookie = new Cookie();
            self::assertEquals($_COOKIE['c1'], $cookie->get("c1"));
            self::assertEquals($_COOKIE['c2'], $cookie->get("c2"));
        }

        public function testGetWithHash(){
            $cookie = new Cookie($this->hash);
            self::assertEquals($_COOKIE[$this->hash->sha1('c4')], $cookie->get("c4"));
            self::assertEquals($_COOKIE[$this->hash->sha1('c3')], $cookie->get("c3"));
        }

        public function testGetAll(){
            $cookie = new Cookie();
            self::assertEquals($_COOKIE, $cookie->getAll());
        }

    }