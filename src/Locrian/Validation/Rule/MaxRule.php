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

    namespace Locrian\Validation\Rule;

    use Locrian\InvalidArgumentException;
    use Locrian\Validation\Rule;

    class MaxRule extends Rule{

        /**
         * @var integer
         */
        private $num;


        /**
         * @param $target
         * @param $args
         *
         * @return bool
         * @throws InvalidArgumentException
         */
        public function validate($target, $args){
            if( is_numeric(doubleval($args[0])) ){
                $this->num = doubleval($args[0]);
                if( is_string($target) ){
                    return strlen($target) <= $this->num;
                }
                else{
                    if( is_numeric($target) ){
                        return $target <= $this->num;
                    }
                    else{
                        return false;
                    }
                }
            }
            else{
                throw new InvalidArgumentException("Max number must be an integer!");
            }
        }

    }