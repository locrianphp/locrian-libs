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

    namespace Locrian\Conf;

    use Locrian\Collections\TreeMap;
    use Locrian\InvalidArgumentException;

    class ConfTree extends TreeMap{

        const OBJECT_NODE = 1;
        const ARRAY_NODE = 2;
        const NAMESPACE_NODE = 3;

        /**
         * @var integer
         * Type of the node
         */
        private $type;


        /**
         * ConfTree constructor.
         *
         * @param int $type
         *
         * @throws \Locrian\InvalidArgumentException
         */
        public function __construct($type){
            if( $type == self::OBJECT_NODE || $type == self::ARRAY_NODE || $type == self::NAMESPACE_NODE ){
                parent::__construct(null, null);
                $this->type = $type;
            }
            else{
                throw new InvalidArgumentException("Invalid node type");
            }
        }


        /**
         * @return int
         */
        public function getType(){
            return $this->type;
        }

    }