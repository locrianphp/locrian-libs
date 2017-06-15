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

    class UrlRule extends Rule{

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
                return filter_var($target, FILTER_VALIDATE_URL) !== false;
            }
            else{
                return false;
            }
        }

    }