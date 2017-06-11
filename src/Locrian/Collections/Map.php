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

	interface Map extends Collection{

		public function add($key, $value);

		public function addAll($items);

		public function get($key);

		public function set($key, $value);

		public function has($key);

        public function first();

        public function last();

		public function getKeys();

		public function getValues();

	}