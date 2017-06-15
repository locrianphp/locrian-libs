<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 15.06.2017
     * Time: 18:16
     */

    namespace Locrian\tests\Validation;


    use Locrian\Validation\Rule;

    class DummyRule extends Rule{

        /**
         * @param $target mixed
         * @param $args array
         * @return boolean
         */
        public function validate($target, $args){
            return true;
        }

    }