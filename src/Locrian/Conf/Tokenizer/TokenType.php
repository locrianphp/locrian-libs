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

    class TokenType{

        // For Application { Application is the namespace token
        const NAMESPACE = 1;

        // {
        const CURLY_OPEN = 2;

        // }
        const CURLY_CLOSE = 3;

        // [
        const SQUARE_OPEN = 4;

        // ]
        const SQUARE_CLOSE = 5;

        // :=
        const ASSIGN = 6;

        // key := value
        const KEY = 7;

        // key := value
        const VALUE = 8;

        // ,
        const COMMA = 9;

        // Lines starting with #
        const COMMENT_LINE = 10;

    }