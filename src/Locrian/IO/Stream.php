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

    abstract class Stream{

        /**
         * @var resource
         * Stream resource (coming from fopen, fsockopen)
         */
        private $stream;


        /**
         * Stream constructor.
         *
         * @param $stream resource
         *
         * @throws IOException
         */
        public function __construct($stream){
            if( is_resource($stream) ){
                $this->stream = $stream;
            }
            else{
                throw new IOException("Failed to open stream");
            }
        }


        /**
         * @return resource
         */
        public function getStream(){
            return $this->stream;
        }


        /**
         * @return bool
         * Closes the stream
         */
        public function close(){
            return fclose($this->stream);
        }

    }