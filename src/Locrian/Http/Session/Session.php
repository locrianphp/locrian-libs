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

    namespace Locrian\Http\Session;

    class Session{

        /**
         * @var string
         * Session id
         */
        private $id;


        /**
         * @var \Locrian\Collections\HashMap
         * Session attributes
         */
        private $attributes;


        /**
         * @var integer
         * Creation timestamp
         */
        private $creationTime;


        /**
         * DefaultSession constructor.
         *
         * @param string $id
         */
        public function __construct($id){
            $this->id = $id;
            $this->creationTime = time();
        }


        /**
         * @return string
         */
        public function getId(){
            return $this->id;
        }


        /**
         * @param string $name
         * @return bool
         */
        public function hasAttribute($name){
            return $this->attributes->has($name);
        }


        /**
         * @param string $name
         * @return mixed
         */
        public function getAttribute($name){
            return $this->attributes->get($name);
        }


        /**
         * @return array
         */
        public function getAttributeNames(){
            return $this->attributes->getKeys();
        }


        /**
         * @param string $name
         * @param $value
         * Override existing attribute or add new one
         */
        public function setAttribute($name, $value){
            $this->attributes->set($name, $value);
        }


        /**
         * @param string $name
         * Remove an attribute
         */
        public function removeAttribute($name){
            $this->attributes->remove($name);
        }


        /**
         * @return int
         */
        public function getCreationTime(){
            return $this->creationTime;
        }

    }