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

    class ValidationResult{

        /**
         * @var \Locrian\Collections\HashMap
         * Errors of all fields
         */
        private $errors;


        /**
         * ValidationResult constructor.
         */
        public function __construct(){
            $this->errors = null;
        }


        /**
         * @param string $field field name
         * @param string $ruleName rule name
         * @param string $message error message
         * @param array $args arguments
         */
        public function addError($field, $ruleName, $message, Array $args){
            if( $this->errors == null ){
                $this->errors = new HashMap();
            }
            $msg = $this->formatMessage($field, $message, $args);
            if( $this->errors->has($field) ){
                $this->errors->get($field)->add(new FieldError($field, $ruleName, $msg, $message, $args));
            }
            else{
                $list = new ArrayList();
                $list->add(new FieldError($field, $ruleName, $msg, $message, $args));
                $this->errors->add($field, $list);
            }
        }


        /**
         * @param string $rule
         * @param string $field
         * @param string $message
         * Overrides a field message to create custom field messages
         */
        public function overrideFieldMessage($rule, $field, $message){
            if( $this->errors->has($field) ){
                $errors = $this->errors->get($field);
                $errors->each(function($i, FieldError $err) use($rule, $message){
                    if( $err->getRuleName() == $rule ){
                        $msg = $this->formatMessage($err->getFieldName(), $message, $err->getArgs());
                        $err->setMessage($msg);
                    }
                });
            }
        }


        /**
         * @param string $field field name
         * @param string $message raw message
         * @param array $args extra arguments
         * @return string
         */
        private function formatMessage($field, $message, $args){
            $argsLen = count($args);
            for( $i = 0; $i < $argsLen; $i++ ){
                $message = str_replace("$" . $i, $args[$i], $message);
            }
            $message = str_replace("\$field", $field, $message);
            return $message;
        }


        /**
         * @return bool
         * True if validation passed
         */
        public function passed(){
            return $this->errors == null || $this->errors->size() == 0;
        }


        /**
         * @return bool
         * True if validation failed
         */
        public function failed(){
            return !$this->passed();
        }


        /**
         * @return \Locrian\Collections\HashMap|null
         */
        public function getErrors(){
            return $this->errors;
        }


        /**
         * @param string $fieldName
         * @return bool
         */
        public function hasErrorsByField($fieldName){
            return $this->errors != null && $this->errors->has($fieldName);
        }


        /**
         * @param string $fieldName
         * @return ArrayList|null
         */
        public function getErrorsByField($fieldName){
            if( $this->errors != null && $this->errors->has($fieldName) ){
                return $this->errors->get($fieldName);
            }
            else{
                return null;
            }
        }

    }