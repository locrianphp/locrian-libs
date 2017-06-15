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

    abstract  class Rule{

        /**
         * @var string
         * Fully qualified rule class
         */
        private $ruleClass;


        /**
         * @var string
         * Rule name. Ex: for EmailRule rule name is "email"
         */
        private $ruleName;


        /**
         * @var string
         * Error message
         */
        private $message;


        /**
         * Rule constructor.
         */
        public function __construct(){

        }

        /**
         * @return string
         */
        public function getRuleClass(){
            return $this->ruleClass;
        }

        /**
         * @param string $ruleClass
         */
        public function setRuleClass($ruleClass){
            $this->ruleClass = $ruleClass;
        }

        /**
         * @return string
         */
        public function getRuleName(){
            return $this->ruleName;
        }

        /**
         * @param string $ruleName
         */
        public function setRuleName($ruleName){
            $this->ruleName = $ruleName;
        }

        /**
         * @return string
         */
        public function getMessage(){
            return $this->message;
        }

        /**
         * @param string $message
         */
        public function setMessage($message){
            $this->message = $message;
        }


        /**
         * @param $target mixed
         * @param $args array
         * @return boolean
         */
        public abstract function validate($target, $args);

    }