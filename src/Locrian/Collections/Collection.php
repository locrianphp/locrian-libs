<?php

    /**
     * * * * * * * * * * * * * * * * * * * *
     *        Locrian Framework         *
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

	use Closure;

	interface Collection{

		public function clear();

		public function search($item);

		public function remove($key);

		public function size();

		public function first();

		public function last();

		public function each(Closure $callback);

		public function filter(Closure $callback);

	}