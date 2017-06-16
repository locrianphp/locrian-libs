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

    class NumberRule extends Rule{

        /**
         * @param $target string
         * @param $args array
         *
         * @return boolean
         *
         * Validates target
         */
        public function validate($target, $args){
            if( is_numeric($target) ){
                return true;
            }
            else{
                return preg_match("/^[0-9\.]+$/", $target);
            }
        }

    }