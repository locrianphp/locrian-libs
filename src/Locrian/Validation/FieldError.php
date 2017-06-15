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

    class FieldError{

        /**
         * @var string
         */
        private $message;


        /**
         * @var string
         * Raw error message
         */
        private $rawMessage;


        /**
         * @var string
         */
        private $fieldName;


        /**
         * @var string
         */
        private $ruleName;


        /**
         * @var array
         */
        private $args;


        /**
         * FieldError constructor.
         *
         * @param string $message
         * @param string $fieldName
         * @param string $ruleName
         * @param string $rawMessage
         * @param array $args
         */
        public function __construct($fieldName, $ruleName, $message, $rawMessage, Array $args){
            $this->message = $message;
            $this->fieldName = $fieldName;
            $this->ruleName = $ruleName;
            $this->args = $args;
            $this->rawMessage = $rawMessage;
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
         * @return string
         */
        public function getFieldName(){
            return $this->fieldName;
        }


        /**
         * @return string
         */
        public function getRuleName(){
            return $this->ruleName;
        }


        /**
         * @return array
         */
        public function getArgs(){
            return $this->args;
        }


        /**
         * @return string
         */
        public function getRawMessage(){
            return $this->rawMessage;
        }

    }