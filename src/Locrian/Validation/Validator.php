<?php

    /**
     * * * * * * * * * * * * * * * * * * * *
     *        Locrian Framework            *
     * * * * * * * * * * * * * * * * * * * *
     *                                     *
     * Author  : Özgür Senekci             *
     *                                     *
     * Skype   :  socialinf                *
     *                                     *
     * License : The MIT License (MIT)     *
     *                                     *
     * * * * * * * * * * * * * * * * * * * *
     */

    namespace Locrian\Validation;

    use Locrian\Collections\ArrayList;
    use Locrian\Collections\HashMap;
    use Locrian\InvalidArgumentException;

    class Validator{

        /**
         * @var \Locrian\Collections\HashMap
         */
        private $rules;


        /**
         * Validator constructor.
         */
        public function __construct(){
            $this->rules = new HashMap();
            $this->registerRules(new DefaultRuleProvider());
        }


        /**
         * @param $ruleClass string
         * @param string $message error message
         * @throws \Locrian\InvalidArgumentException
         * Adds new rule
         */
        public function addRule($ruleClass, $message){
            if( is_string($ruleClass) ){
                $tokens = explode("\\", $ruleClass);
                $ruleName = mb_strtolower(str_replace("Rule", "", end($tokens)));
                $rule = new $ruleClass();
                if( $rule instanceof Rule ){
                    $rule->setRuleClass($ruleClass);
                    $rule->setRuleName($ruleName);
                    $rule->setMessage($message);
                    $this->rules->add($ruleName, $rule);
                }
                else{
                    throw new InvalidArgumentException("Rules must extend Rule abstract class");
                }
            }
            else{
                throw new InvalidArgumentException("Rule must be fully qualified class name of the target rule");
            }
        }


        /**
         * @return \Locrian\Collections\ArrayList
         * Returns all the available rule names
         */
        public function getRuleNames(){
            $list = new ArrayList();
            $this->rules->each(function($i, Rule $ele) use($list){
                 $list->add($ele->getRuleName());
            });
            return $list;
        }


        /**
         * @return \Locrian\Collections\ArrayList
         * RuleHolder objects for available rules
         */
        public function getRules(){
            $list = new ArrayList();
            $this->rules->each(function($i, Rule $holder) use($list){
                $list->add($holder);
            });
            return $list;
        }


        /**
         * @param string $ruleName
         * @return bool
         * Checks the rule exists
         */
        public function hasRule($ruleName){
            return $this->rules->has($ruleName);
        }


        /**
         * @param $ruleName
         * @return \Locrian\Validation\Rule|null
         * Returns rule if exists
         */
        public function getRule($ruleName){
            if( $this->hasRule($ruleName) ){
                return $this->rules->get($ruleName);
            }
            else{
                return null;
            }
        }


        /**
         * @param \Locrian\Validation\RuleProvider $provider
         * Registers new rules
         */
        public function registerRules(RuleProvider $provider){
            $provider->registerRules($this);
        }


        /**
         * @param string $ruleName
         * @param string $newMessage
         *
         * @throws \Locrian\InvalidArgumentException
         */
        public function overrideRuleMessage($ruleName, $newMessage){
            if( !is_string($ruleName) ){
                throw new InvalidArgumentException("Rule name must be string");
            }
            if( $this->rules->has($ruleName) ){
                $rule = $this->rules->get($ruleName);
                $rule->setMessage($newMessage);
            }
        }


        /**
         * @param array $fields
         *
         * @throws InvalidArgumentException
         * @throws \Locrian\RuntimeException
         *
         * @return \Locrian\Validation\ValidationResult
         *
         * Validates the given field. See the following example
         *
         * $result = $validator->validate([
         *      "Name" => [ "value", "required|min(4)|max(15)" ]
         * ]);
         *
         * if( !$result->passed() ){
         *      echo $result->getErrorByField("Name");
         * }
         */
        public function validate(Array $fields){
            $result = new ValidationResult();
            foreach( $fields as $fieldName => $options ){
                // "fieldName"  =>  [ "value", "rule1|rule2" ]
                if( !is_array($options) && count($options) !== 2 ){
                    throw new InvalidArgumentException("Options must contain value and rules");
                }
                if( !is_string($options[1]) ){
                    throw new InvalidArgumentException("Rules must be string!");
                }
                // Field value
                $value = $options[0];
                // Split the rules
                $rules = explode("|", $options[1]);
                foreach( $rules as $rule ){
                    if( preg_match("/^([a-z]+)(\\(([0-9,]+)\\))?$/", $rule, $matched) ){
                        /**
                         * Example of match content
                         *
                         * $match[0] = between(7,8)
                         * $match[1] = between
                         * $match[2] = (7,8)
                         * $match[3] = 7,8
                         */
                        // Rule name (min, max, ...)
                        if( $this->rules->has($matched[1]) ){
                            $rule = $this->rules->get($matched[1]);
                            // Args to send to the validators
                            $args = [];
                            if( count($matched) == 4 ){
                                $args = preg_split("#,#", str_replace(" ", "", $matched[3]), -1, PREG_SPLIT_NO_EMPTY);
                            }
                            // If field is not passed then set error to the field
                            if( !$rule->validate($value, $args) ){
                                $result->addError($fieldName, $rule->getRuleName(), $rule->getMessage(), $args);
                            }
                        }
                        else{
                            throw new InvalidArgumentException("Invalid rule");
                        }
                    }
                    else{
                        throw new InvalidArgumentException("Invalid rule");
                    }
                }
            }
            return $result;
        }

    }