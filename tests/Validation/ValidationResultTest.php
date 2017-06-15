<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 15.06.2017
     * Time: 18:14
     */

    namespace Locrian\Tests\Validation;

    use Locrian\Validation\FieldError;
    use PHPUnit_Framework_TestCase;
    use Locrian\Validation\Validator;

    class ValidationResultTest extends PHPUnit_Framework_TestCase{

        /**
         * @var Validator
         */
        private $validator;

        protected function setUp(){
            $this->validator = new Validator();
        }

        public function testValidateFail(){
            $result = $this->validator->validate([
                "country" => [ "Netherlands", "alnum|required|between(7,9)" ]
            ]);
            self::assertTrue($result->failed());
        }

        public function testErrorCount(){
            $result = $this->validator->validate([
                "country" => [ "Netherlands[]", "alnum|required|between(7,9)" ]
            ]);
            self::assertEquals(1, $result->getErrors()->size());
            self::assertEquals(2, $result->getErrors()->get("country")->size());
            self::assertEquals(2, $result->getErrorsByField("country")->size());
        }

        public function testErrorMessages(){
            $result = $this->validator->validate([
                "country" => [ "Netherlands[]", "alnum|required|between(7,9)" ]
            ]);
            $alnumMessage = "Only alphanumeric characters are allowed";
            $betweenMessage = "Only sizes between 7 and 9 are allowed";
            $errors = $result->getErrorsByField("country");
            $errors->each(function($i, FieldError $error) use($alnumMessage, $betweenMessage){
                if( $error->getRuleName() == "alnum" ){
                    self::assertEquals($alnumMessage, $error->getMessage());
                }
                else{ // between
                    self::assertEquals($betweenMessage, $error->getMessage());
                }
            });
        }

        public function testOverrideFieldMessage(){
            $result = $this->validator->validate([
                "country" => [ "Netherlands[]", "alnum|required|between(7,9)" ],
                "name" => [ "Özgür", "alnum|required" ]
            ]);
            $result->overrideFieldMessage("alnum", "country", "\$field must contain alnum characters");
            $result->overrideFieldMessage("between", "country", "\$field size must between $0 and $1");
            $errors = $result->getErrorsByField("country");
            $errors->each(function($i, FieldError $error){
                if( $error->getRuleName() == "alnum" ){
                    self::assertEquals("country must contain alnum characters", $error->getMessage());
                }
                else{ // between
                    self::assertEquals("country size must between 7 and 9", $error->getMessage());
                }
            });
            self::assertEquals("Only alphanumeric characters are allowed",
                $result->getErrorsByField("name")->first()->getMessage()); // Override should not appear here
        }

    }