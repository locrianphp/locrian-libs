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

	namespace Locrian\Collections\Iterator;

	interface Iterator{

		public function hasNext();

		public function next();

		public function hasPrevious();

		public function previous();

		public function index();

		public function remove();

		public function reset();

		public function getCollection();

	}