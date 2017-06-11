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


    interface InputStream{

        /**
         * @return mixed
         * Reads from the stream
         */
        public function read();

        /**
         * @param $offset
         *
         * @return mixed
         * Moves current position + offset bytes forward
         */
        public function skip($offset);

        /**
         * @return mixed
         * Returns the current position of the file pointer
         */
        public function getPosition();

    }