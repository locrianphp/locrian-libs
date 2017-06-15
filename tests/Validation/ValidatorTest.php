<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 15.06.2017
     * Time: 14:09
     */

    namespace Locrian\Tests\Validation;

    use Locrian\Validation\Rule;
    use Locrian\Validation\Rule\AlnumRule;
    use Locrian\Validation\Rule\AlnumsRule;
    use Locrian\Validation\Rule\AlphaRule;
    use Locrian\Validation\Rule\AlphasRule;
    use Locrian\Validation\Rule\BetweenRule;
    use Locrian\Validation\Rule\EmailRule;
    use Locrian\Validation\Rule\MaxRule;
    use Locrian\Validation\Rule\MinRule;
    use Locrian\Validation\Rule\NumberRule;
    use Locrian\Validation\Rule\RequiredRule;
    use Locrian\Validation\Rule\UrlRule;
    use PHPUnit_Framework_TestCase;
    use Locrian\Validation\Validator;

    class ValidatorTest extends PHPUnit_Framework_TestCase{

        /**
         * @var Validator
         */
        private $validator;

        protected function setUp(){
            $this->validator = new Validator();
        }

        public function testGetRuleNames(){
            $defaultRules = [ "alnum", "alnums", "alpha", "alphas", "between",
                "email", "max", "min", "number", "required", "url" ];
            $rules = $this->validator->getRuleNames();
            $rules->each(function($i, $rule) use($defaultRules){
                self::assertEquals($defaultRules[$i], $rule);
            });
        }

        public function testGetRules(){
            $defaultRules = [ "alnum" => AlnumRule::class, "alnums" => AlnumsRule::class, "alpha" => AlphaRule::class,
                "alphas" => AlphasRule::class, "between" => BetweenRule::class, "email" => EmailRule::class,
                "max" => MaxRule::class, "min" => MinRule::class, "number" => NumberRule::class, "required" => RequiredRule::class, "url" => UrlRule::class ];
            $rules = $this->validator->getRules();
            $rules->each(function($i, Rule $rule) use($defaultRules){
                self::assertEquals($defaultRules[$rule->getRuleName()], $rule->getRuleClass());
            });
        }

        public function testAddRule(){
            $this->validator->addRule(DummyRule::class, "Dummy rule");
            self::assertTrue($this->validator->hasRule("dummy"));
            self::assertEquals("Dummy rule", $this->validator->getRule("dummy")->getMessage());
            self::assertEquals("dummy", $this->validator->getRuleNames()->last());
            self::assertEquals(DummyRule::class, $this->validator->getRules()->last()->getRuleClass());
        }

        public function testOverrideRuleMessage(){
            $msg = "Overwritten message content";
            $this->validator->overrideRuleMessage("alnum", $msg);
            self::assertEquals($msg, $this->validator->getRule("alnum")->getMessage());
        }

        public function testValidate(){
            $result = $this->validator->validate([
                "First Name" => [ "value", "required|between(4,9)" ]
            ]);
            self::assertTrue($result->passed());
            self::assertFalse($result->failed());
            $result = $this->validator->validate([
                "First Name" => [ "value", "required|min(9)" ]
            ]);
            self::assertFalse($result->passed());
            self::assertTrue($result->failed());
            $result = $this->validator->validate([
                "First Name" => [ "value", "required|between(4,9)" ],
                "Framework" => [ "Locrian", "max(10)" ]
            ]);
            self::assertTrue($result->passed());
            self::assertFalse($result->failed());
        }

    }