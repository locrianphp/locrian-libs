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

    class Path{

        /**
         * Path delimiter
         * : on linux
         * ; on windows
         */
        const DELIMITER = PATH_SEPARATOR;


        /**
         * Directory separator
         * / on linux
         * \ on windows
         */
        const SEPARATOR = DIRECTORY_SEPARATOR;


        /**
         * @param $path string
         * @param string $suffix
         *
         * @return string
         *
         * Returns basename of the given path
         */
        public static function basename($path, $suffix = null){
            return basename($path, $suffix);
        }


        /**
         * @param $path string
         * @return string
         *
         * Returns directory name (with parents) of the given file
         */
        public static function dirName($path){
            return dirname($path);
        }


        /**
         * @param $path string path
         * @return bool
         * Checks whether the given path is absolute
         */
        public static function isAbsolute($path){
            return strspn($path, '/\\', 0, 1)
                   || (parse_url($path, PHP_URL_SCHEME) !== null)
                   || (strlen($path) > 3 && ctype_alpha($path[0]) && substr($path, 1, 1) === ':' && strspn($path, '/\\', 2, 1));
        }


        /**
         * @return string
         * Joins the given strings with the path separator
         */
        public static function join(){
            $args = func_get_args();
            $result = "";
            foreach( $args as $arg ){
                $result = $result . self::SEPARATOR . $arg;
            }
            $result = StringUtils::remove(0, $result);
            return $result;
        }


        /**
         * @param $path string
         *
         * @return string
         * Normalizes the separator according to the OS separator
         */
        public static function normalize($path){
            return preg_replace("@[\\\\\\/]+@s", DIRECTORY_SEPARATOR, $path);
        }

    }