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


    abstract class BufferedStream extends Stream{

        /**
         * @var int buffer size
         */
        private $bufferSize;


        /**
         * BufferedStream constructor.
         *
         * @param $stream
         * @param $bufferSize
         *
         * @throws IOException
         */
        public function __construct($stream, $bufferSize){
            parent::__construct($stream);
            if( is_int($bufferSize) && $bufferSize > 0 ){
                $this->bufferSize = $bufferSize;
            }
            else{
                throw new IOException("Invalid buffer size");
            }
        }


        /**
         * @return int
         */
        public function getBufferSize(){
            return $this->bufferSize;
        }

    }