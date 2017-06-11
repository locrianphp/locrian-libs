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

    use Closure;
    use Locrian\BadMethodCallException;
    use Locrian\IO\IOException;

    class OBUtils{

        /**
         * @param string $file String
         *
         * @return string
         * @throws IOException
         *
         * $content = OBUtils::fileBuffer("assets/files/test.txt");
         * echo $content;   // o/p -> contents in the test.txt file
         */
        public static function fileBuffer($file = ""){
            if( file_exists($file) ){
                ob_start();
                require $file;
                $contents = ob_get_contents();
                ob_end_clean();
                return $contents;
            }
            else{
                throw new IOException($file . " could not be found!");
            }
        }


        /**
         * @param Closure $function
         *
         * @return string
         * @throws BadMethodCallException
         *
         * $content = OBUtils::functionBuffer(function(){ echo "test"; });
         * echo $content;   // o/p -> "test";
         */
        public static function callbackBuffer(Closure $function){
            ob_start();
            $function();
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }

    }