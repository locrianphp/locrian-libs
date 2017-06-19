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

    namespace Locrian\DI;

    class InjectionTarget{

        /**
         * Target is a method
         */
        const METHOD = 1;


        /**
         * Target is a field
         */
        const FIELD = 2;


        /**
         * @var integer
         * Target type (METHOD|FIELD)
         */
        private $type;


        /**
         * @var string
         * Target name
         */
        private $name;


        /**
         * InjectionTarget constructor.
         *
         * @param int $type
         * @param string $name
         */
        public function __construct($type, $name){
            $this->type = $type;
            $this->name = $name;
        }


        /**
         * @return int
         */
        public function getType(){
            return $this->type;
        }


        /**
         * @return string
         */
        public function getName(){
            return $this->name;
        }

    }