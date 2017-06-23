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

    namespace Locrian\Util;

    use Locrian\Collections\HashMap;
    use Locrian\InvalidArgumentException;

    class SimpleTemplate{

        /**
         * @param string $content
         * @param array|\Locrian\Collections\HashMap $values
         * @return string
         *
         * @throws \Locrian\InvalidArgumentException
         */
        public static function replace($content, $values){
            if( is_array($values) ){
                $values = new HashMap($values);
            }
            if( !($values instanceof HashMap) ){
                throw new InvalidArgumentException("Values must be either array or an instance of HashMap");
            }
            $keys = $values->getKeys();
            foreach( $keys as $key ){
                $search = "{{" . $key . "}}";
                $content = str_replace($search, $values->get($key), $content);
            }
            return $content;
        }

    }