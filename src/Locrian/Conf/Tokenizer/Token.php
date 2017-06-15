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

    namespace Locrian\Conf\Tokenizer;

    class Token{

        /**
         * @var string token
         */
        private $token;


        /**
         * @var int token type
         */
        private $tokenType;


        /**
         * Token constructor.
         *
         * @param string $token
         * @param integer $tokenType
         */
        public function __construct($token, $tokenType){
            $this->token = $token;
            $this->tokenType = $tokenType;
        }


        /**
         * @return mixed
         */
        public function getToken(){
            return $this->token;
        }


        /**
         * @param mixed $token
         */
        public function setToken($token){
            $this->token = $token;
        }


        /**
         * @return mixed
         */
        public function getTokenType(){
            return $this->tokenType;
        }


        /**
         * @param mixed $tokenType
         */
        public function setTokenType($tokenType){
            $this->tokenType = $tokenType;
        }

    }