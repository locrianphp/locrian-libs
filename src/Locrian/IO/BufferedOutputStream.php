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


    class BufferedOutputStream extends BufferedStream implements OutputStream{

        /**
         * @var string
         * Buffer
         */
        private $stringBuffer;


        /**
         * BufferedOutputStream constructor.
         *
         * @param $handle
         * @param $bufferSize
         *
         * @throws IOException
         */
        public function __construct($handle, $bufferSize = 1024){
            $this->stringBuffer = "";
            if( $handle instanceof File ){
                $stream = fopen($handle->getPath(), "w");
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
         * @param $data
         * Writes data to the stream
         */
        public function write($data){
            $this->stringBuffer .= $data;
            if( strlen($this->stringBuffer) >= $this->getBufferSize() ){
                $tmp = str_split($this->stringBuffer, $this->getBufferSize());
                foreach( $tmp as $val ){
                    $this->writeToStream($val);
                }
                $this->stringBuffer = "";
            }
        }


        /**
         * @param $data
         * Writes the given data to the stream
         */
        private function writeToStream($data){
            fwrite($this->getStream(), $data);
        }


        /**
         * Writes the data to the stream if there is some remaining data in the buffer
         * Use this function before closing the stream
         */
        public function flush(){
            if( strlen($this->stringBuffer) > 0 ){
                $this->writeToStream($this->stringBuffer);
            }
        }

    }