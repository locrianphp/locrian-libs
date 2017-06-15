<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 15.06.2017
     * Time: 18:41
     */

    namespace Locrian\Tests\Validation;

    use PHPUnit_Framework_TestCase;
    use Locrian\Validation\Validator;

    class RuleTest extends PHPUnit_Framework_TestCase{

        /**
         * @var \Locrian\Validation\Validator
         */
        private $validator;

        protected function setUp(){
            $this->validator = new Validator();
        }

        public function testAlnumRule(){
            $r = $this->validator->validate([
                "a" => [ "value", "alnum" ],
                "b" => [ "value123", "alnum" ]
            ]);
            self::assertTrue($r->passed());
            $r = $this->validator->validate([
                "a" => [ "value[!", "alnum" ]
            ]);
            self::assertFalse($r->passed());
            $r = $this->validator->validate([
                "a" => [ "valueşğĞüÜçÇıİ", "alnum" ]
            ]);
            self::assertFalse($r->passed());
        }

        public function testAlnumsRule(){
            $r = $this->validator->validate([
                "a" => [ "value", "alnums" ],
                "b" => [ "value123", "alnums" ]
            ]);
            self::assertTrue($r->passed());
            $r = $this->validator->validate([
                "a" => [ "value[!", "alnums" ]
            ]);
            self::assertFalse($r->passed());
            $r = $this->validator->validate([
                "a" => [ "valueşğĞüÜçÇıİ", "alnums" ]
            ]);
            self::assertTrue($r->passed());
        }

        public function testAlphaRule(){
            $r = $this->validator->validate([
                "a" => [ "value", "alpha" ]
            ]);
            self::assertTrue($r->passed());
            $r = $this->validator->validate([
                "a" => [ "value[!", "alpha" ]
            ]);
            self::assertFalse($r->passed());
            $r = $this->validator->validate([
                "a" => [ "valueşğĞüÜçÇıİ", "alpha" ]
            ]);
            self::assertFalse($r->passed());
        }

        public function testAlphasRule(){
            $r = $this->validator->validate([
                "a" => [ "value", "alphas" ]
            ]);
            self::assertTrue($r->passed());
            $r = $this->validator->validate([
                "a" => [ "value[!", "alphas" ]
            ]);
            self::assertFalse($r->passed());
            $r = $this->validator->validate([
                "a" => [ "valueşğĞüÜçÇıİ", "alphas" ]
            ]);
            self::assertTrue($r->passed());
        }

        public function testBetweenRule(){
            $r = $this->validator->validate([
                "a" => [ "value", "between(4,6)" ]
            ]);
            self::assertTrue($r->passed());
            $r = $this->validator->validate([
                "a" => [ "value", "between(5,6)" ]
            ]);
            self::assertFalse($r->passed());
            $r = $this->validator->validate([
                "a" => [ "value", "between(4,5)" ]
            ]);
            self::assertFalse($r->passed());
            $r = $this->validator->validate([
                "a" => [ 5, "between(4,6)" ]
            ]);
            self::assertTrue($r->passed());
            $r = $this->validator->validate([
                "a" => [ 5, "between(5,6)" ]
            ]);
            self::assertFalse($r->passed());
            $r = $this->validator->validate([
                "a" => [ 5, "between(4,5)" ]
            ]);
            self::assertFalse($r->passed());
        }

        public function testEmailRule(){
            $r = $this->validator->validate([
               "a" => [ "ozgursenekci@gmail.com", "email" ]
            ]);
            self::assertTrue($r->passed());
            $r = $this->validator->validate([
                "a" => [ "ozgursenekcigmail.com", "email" ]
            ]);
            self::assertFalse($r->passed());
            $r = $this->validator->validate([
                "a" => [ "ozgursenekci@gmailcom", "email" ]
            ]);
            self::assertFalse($r->passed());
        }

        public function testMinMaxRule(){
            $r = $this->validator->validate([
                "a" => [ "value", "min(5)" ],
                "b" => [ 5, "min(5)" ]
            ]);
            self::assertTrue($r->passed());
            $r = $this->validator->validate([
                "a" => [ "value", "min(6)" ]
            ]);
            self::assertFalse($r->passed());
            $r = $this->validator->validate([
                "a" => [ 5, "min(6)" ]
            ]);
            self::assertFalse($r->passed());
            $r = $this->validator->validate([
                "a" => [ "value", "max(5)" ],
                "b" => [ 5, "max(5)" ]
            ]);
            self::assertTrue($r->passed());
            $r = $this->validator->validate([
                "a" => [ "value", "max(4)" ]
            ]);
            self::assertFalse($r->passed());
            $r = $this->validator->validate([
                "a" => [ 5, "max(4)" ]
            ]);
            self::assertFalse($r->passed());
        }

        public function testNumberRule(){
            $r = $this->validator->validate([
                "a" => [ "78459", "number" ],
                "b" => [ 78, "number" ]
            ]);
            self::assertTrue($r->passed());
            $r = $this->validator->validate([
                "a" => [ "7854a", "number" ]
            ]);
            self::assertFalse($r->passed());
        }

        public function testRequiredRule(){
            $r = $this->validator->validate([
                "a" => [ "", "required" ]
            ]);
            self::assertFalse($r->passed());
            $r = $this->validator->validate([
                "a" => [ "    ", "required" ]
            ]);
            self::assertFalse($r->passed());
            $r = $this->validator->validate([
                "a" => [ "1", "required" ]
            ]);
            self::assertTrue($r->passed());
        }

        public function testUrlRule(){
            $r = $this->validator->validate([
                "a" => [ "http://google.com", "url" ],
                "b" => [ "ftp://google.com", "url" ],
                "c" => [ "https://google.com", "url" ],
                "d" => [ "ws://google.com", "url" ],
                "e" => [ "http://www.google.com", "url" ]
            ]);
            self::assertTrue($r->passed());
            $r = $this->validator->validate([
                "a" => [ "www.google.com", "url" ]
            ]);
            self::assertFalse($r->passed());
        }

    }