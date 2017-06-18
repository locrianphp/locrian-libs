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

    namespace Locrian\Http;

    use Locrian\Http\Session\Session;

    class Flash{

        /**
         * Session key prefix
         */
        const PREFIX = "locrian_flash__";


        /**
         * @var \Locrian\Http\Session\Session
         */
        private $session;


        /**
         * Flash constructor.
         *
         * @param \Locrian\Http\Session\Session $session
         */
        public function __construct(Session $session){
            $this->session = $session;
        }


        /**
         * @param string $key
         * @param mixed $value
         * Sets new flash attribute
         */
        public function setAttribute($key, $value){
            $key = $this->getKey($key);
            $this->session->setAttribute($key, $value);
        }


        /**
         * @param string $key
         * @return bool
         * Checks if the given key exists
         */
        public function hasAttribute($key){
            $key = $this->getKey($key);
            return $this->session->hasAttribute($key);
        }


        /**
         * @param string $key
         * @return mixed
         * Find an attribute with its key
         */
        public function getAttribute($key){
            $key = $this->getKey($key);
            if( $this->session->hasAttribute($key) ){
                $attr = $this->session->getAttribute($key);
                $this->session->removeAttribute($key);
                return $attr;
            }
            else{
                return null;
            }
        }


        /**
         * @param string $key
         * Remove an attribute with its key
         */
        public function removeAttribute($key){
            $key = $this->getKey($key);
            if( $this->session->hasAttribute($key) ){
                $this->session->removeAttribute($key);
            }
        }


        /**
         * @param $key
         * @return string
         * Merge key with prefix
         */
        private function getKey($key){
            return self::PREFIX . $key;
        }

    }