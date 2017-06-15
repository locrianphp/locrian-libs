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

    class RequiredRule extends Rule{

        /**
         * @param $target
         * @param $args
         *
         * @return bool
         *
         */
        public function validate($target, $args){
            if( is_string($target) ){
                return !empty(trim($target));
            }
            else{
                return true;
            }
        }

    }