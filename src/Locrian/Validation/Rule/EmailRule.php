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

    use Locrian\Validation\Rule;

    class EmailRule extends Rule{

        /**
         * @param $target string
         * @param $args array
         *
         * @return boolean
         *
         * Validates target
         */
        public function validate($target, $args){

            if( is_string($target) ){
                return preg_match('/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,3}(\.[a-zA-Z]{2})?$/i', $target);
            }
            else{
                return false;
            }
        }

    }