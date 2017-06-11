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

    use Locrian\IO\BufferedInputStream;
    use Locrian\IO\BufferedOutputStream;
    use Locrian\IO\File;

    class FileUtils{

        /**
         * File append mode
         */
        const APPEND = "file_append";


        /**
         * File overwrite mode
         */
        const OVERWRITE = "file_overwrite";


        /**
         * @param File $target
         * @param $content
         * @param string $writeMode
         * @param int $fileMode
         *
         * @return bool
         * Writes text to a file
         */
        public static function writeText(File $target, $content, $writeMode = self::OVERWRITE, $fileMode = 0755){
            if( !$target->exists() ){
                $target->touch($fileMode);
            }
            if( $writeMode == self::OVERWRITE ){
                $resource = fopen($target->getPath(), "w");
            }
            else{
                if( $writeMode == self::APPEND ){
                    $resource = fopen($target->getPath(), "a");
                }
                else{
                    return false;
                }
            }
            if( $resource !== false ){
                $out = new BufferedOutputStream($resource);
                $out->write($content);
                $out->flush();
                $out->close();
                return true;
            }
            else{
                return false;
            }
        }


        /**
         * @param File $target
         *
         * @return bool|string
         * Reads file and returns its content as text
         */
        public static function readText(File $target){
            if( $target->exists() && $target->isReadable() ){
                $in = new BufferedInputStream($target);
                $text = "";
                while( ($chunk = $in->read()) !== null ){
                    $text .= $chunk;
                }
                $in->close();
                return $text;
            }
            else{
                return false;
            }
        }

    }




