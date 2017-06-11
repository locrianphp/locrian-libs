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


    use Locrian\InvalidArgumentException;

    class StringUtils{

        /**
         * @param $token string
         * @param $string string
         *
         * @return bool
         *
         * Returns true if a string contains a sub string
         */
        public static function contains($token, $string){
            return self::indexOf($token, $string) >= 0;
        }


        /**
         * @param $token string
         * @param $string string
         *
         * @return int
         *
         * Returns the start index of a sub string in a string. If string does not contain the substring then returns -1
         */
        public static function indexOf($token, $string){
            $position = strpos($string, $token);
            if( $position === false ){
                return -1;
            }
            else{
                return $position;
            }
        }


        /**
         * @param $token string
         * @param $string string
         *
         * @return bool
         *
         * Returns true if a string starts with a sub string
         */
        public static function startsWith($token, $string){
            $tokenLen = strlen($token);
            if( $tokenLen > strlen($string) ){
                return false;
            }
            else{
                for( $i = 0; $i < $tokenLen - 1; $i++ ){
                    if( self::charAt($i, $token) !== self::charAt($i, $string) ){
                        return false;
                    }
                }
                return true;
            }
        }


        /**
         * @param $token string
         * @param $string string
         *
         * @return bool
         *
         * Returns true if a string ends with a sub string
         */
        public static function endWith($token, $string){
            $stringLen = strlen($string);
            $tokenLen = strlen($token);
            if( $tokenLen > $stringLen ){
                return false;
            }
            else{
                for( $i = $stringLen - 1, $j = $tokenLen - 1; $j >= 0; $i--, $j-- ){
                    if( self::charAt($j, $token) !== self::charAt($i, $string) ){
                        return false;
                    }
                }
                return true;
            }
        }


        /**
         * @param $string1 string
         * @param $string2 string
         *
         * @return bool
         *
         * Returns true if two strings have the same length
         */
        public static function equalLength($string1, $string2){
            return strlen($string1) === strlen($string2);
        }


        /**
         * @param $string1 string
         * @param $string2 string
         *
         * @return bool
         *
         * Returns true if two strings equal to each other
         */
        public static function equals($string1, $string2){
            return $string1 === $string2;
        }


        /**
         * @param $string string
         *
         * @return string
         *
         * Converts a string to uppercase
         */
        public static function upper($string){
            return mb_strtoupper($string, "utf8");
        }


        /**
         * @param $string string
         *
         * @return string
         *
         * Converts a string to lowercase
         */
        public static function lower($string){
            return mb_strtolower($string, "utf8");
        }


        /**
         * @param $posOrSubstring  string|int
         * @param $string string string
         *
         * @return string
         * @throws InvalidArgumentException
         *
         * Removes a specific index or the first matched substring in a string
         */
        public static function remove($posOrSubstring, $string){
            if( is_int($posOrSubstring) ){
                if( strlen($string) <= $posOrSubstring ){
                    return $string;
                }
                else{
                    $newString = "";
                    $len = strlen($string);
                    for( $i = 0; $i < $len; $i++ ){
                        if( $i != $posOrSubstring ){
                            $newString .= self::charAt($i, $string);
                        }
                    }
                    return $newString;
                }
            }
            else{
                if( is_string($posOrSubstring) ){
                    $from = '%' . preg_quote($posOrSubstring, '%') . '%';
                    return preg_replace($from, "", $string, 1);
                }
                else{
                    throw new InvalidArgumentException("Position or substring required");
                }
            }
        }


        /**
         * @param $subString string
         * @param $string string
         *
         * @return string
         *
         * Removes every repeat of a substring in a string
         */
        public static function removeAll($subString, $string){
            $from = '%' . preg_quote($subString, '%') . '%';
            return preg_replace($from, "", $string);
        }


        /**
         * @param $string string
         *
         * @return string
         *
         * Reverses the given string
         */
        public static function reverse($string){
            if( self::isBlank($string) ){
                return $string;
            }
            else{
                $newString = "";
                $len = strlen($string);
                for( $i = $len - 1; $i >= 0; $i-- ){
                    $newString .= self::charAt($i, $string);
                }
                return $newString;
            }
        }


        /**
         * @param $separator string
         * @param $string string
         *
         * @return array
         *
         * Splits a string into a string array
         */
        public static function split($separator, $string){
            return explode($separator, $string);
        }


        /**
         * @param $separator array
         * @param $string string
         *
         * @return string
         *
         * Joins a string array with a separator
         */
        public static function join($separator, $string){
            return implode($separator, $string);
        }


        /**
         * @param $position int
         * @param $string string
         *
         * @return bool
         *
         * Returns the character which is at the given position
         */
        public static function charAt($position, $string){
            if( strlen($string) < ($position + 1) ){
                return false;
            }
            else{
                return $string{$position};
            }
        }


        /**
         * @param $string string
         *
         * @return int
         *
         * Returns true if given string contains only numerical characters
         */
        public static function isNumeric($string){
            return preg_match("%^[0-9]+$%", $string);
        }


        /**
         * @param $string string
         *
         * @return int
         *
         * Returns true if given string contains only alphanumeric characters
         */
        public static function isAlpha($string){
            return preg_match("%^[a-zA-Z0-9]+$%", $string);
        }


        /**
         * @param $string string
         *
         * @return string
         *
         * Removes whitespaces at the beginning and the end of a string
         */
        public static function trim($string){
            return trim($string);
        }


        /**
         * @param $string string
         *
         * @return bool
         *
         * Returns true if a string is empty or whitespace
         */
        public static function isBlank($string){
            return self::isEmpty(self::trim($string));
        }


        /**
         * @param $string string
         *
         * @return bool
         *
         * Returns true if a string is blank (ignores whitespaces)
         */
        public static function isEmpty($string){
            return self::equals($string, "");
        }

    }











