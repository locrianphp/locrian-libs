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

    namespace Locrian\Conf\Tokenizer;

    interface Tokenizer{

        /**
         * @param string $content
         * Tokenize given content
         */
        public function tokenize($content);

        /**
         * @return \Locrian\Collections\Queue
         */
        public function getTokens();
    }