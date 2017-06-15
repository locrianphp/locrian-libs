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

    class BetweenRule extends Rule{

        /**
         * @param $target string
         * @param $args array
         * @throws \Locrian\InvalidArgumentException
         *
         * @return bool
         */
        public function validate($target, $args){
            if( is_numeric(doubleval($args[0])) && is_numeric(doubleval($args[1])) ){
                $min = doubleval($args[0]);
                $max = doubleval($args[1]);
                $len = strlen($target);
                if( is_string($target) ){
                    return ($min < $len) && ($max > $len);
                }
                else{
                    if( is_numeric($target) ){
                        return ($min < $target) && ($max > $target);
                    }
                    else{
                        return false;
                    }
                }
            }
            else{
                throw new InvalidArgumentException("Max and min must be integers");
            }
        }

    }