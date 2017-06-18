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

    namespace Locrian\IO;

    class BufferedInputStream extends BufferedStream implements InputStream{

        /**
         * BufferedInputStream constructor.
         *
         * @param $handle
         * @param $bufferSize
         *
         * @throws IOException
         */
        public function __construct($handle, $bufferSize = 1024){
            $bufferSize++; // Reading bufferSize - 1 bytes
            if( $handle instanceof File ){
                if( !$handle->exists() ){
                    $handle->touch();
                }
                $stream = fopen($handle->getPath(), "r");
                if( $stream === false ){
                    throw new IOException("Failed to open stream");
                }
                else{
                    parent::__construct($stream, $bufferSize);
                }
            }
            else{
                if( is_resource($handle) ){
                    parent::__construct($handle, $bufferSize);
                }
                else{
                    throw new IOException("Failed to open stream");
                }
            }
        }


        /**
         * @return bool|string
         * Reads chunk from the resource
         */
        public function read(){
            if( !feof($this->getStream()) ){
                return fgets($this->getStream(), $this->getBufferSize());
            }
            else{
                return null;
            }
        }


        /**
         * @param $offset
         *
         * @return int
         * Skips currentPos + offset bytes
         */
        public function skip($offset){
            return fseek($this->getStream(), $offset, SEEK_CUR);
        }


        /**
         * @return bool
         * Sets the file pointer position to the beginning of the file
         */
        public function rewind(){
            return rewind($this->getStream());
        }


        /**
         * @return int
         * Returns the current file pointer position
         */
        public function getPosition(){
            return ftell($this->getStream());
        }

    }