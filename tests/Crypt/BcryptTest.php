<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 11.06.2017
     * Time: 23:48
     */

    namespace Locrian\tests\Crypt;

    use Locrian\Crypt\BCrypt;
    use PHPUnit_Framework_TestCase;

    class BcryptTest extends PHPUnit_Framework_TestCase{

        public function testCrypt(){
            $data = "Locrian";
            $hashed = BCrypt::hash($data);
            self::assertNotEquals(BCrypt::hash($data), $hashed);
            self::assertTrue(BCrypt::verify($data, $hashed));
        }

    }