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

    namespace Locrian\Collections;

    interface IndexedList extends Collection{

        public function add($item);

        public function addAll($items);

        public function get($index);

        public function first();

        public function last();

        public function set($index, $value);

        public function has($index);

    }