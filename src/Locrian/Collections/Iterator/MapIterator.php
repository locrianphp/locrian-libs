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

	use Locrian\Collections\Map;

	class MapIterator implements Iterator{

		/**
		 * @var Map object
		 */
		private $map;


		/**
		 * @var array keys of the map object
		 */
		private $keys;


		/**
		 * @var int current index
		 */
		private $index;


		/**
		 * MapIterator constructor.
		 *
		 * @param Map $map
		 */
		public function __construct(Map $map){
			$this->index = -1;
			$this->map = $map;
			$this->keys = $map->getKeys();
		}


		/**
		 * @return bool
		 */
		public function hasNext(){
			return ($this->index + 1) < $this->map->size();
		}


		/**
		 * @return bool|mixed
		 */
		public function next(){
			if( $this->hasNext() ){
                $this->index++;
				$data = $this->map->get($this->keys[$this->index]);
				return $data;
			}
			else{
                return null;
            }
		}


		/**
		 * @return bool
		 */
		public function hasPrevious(){
			return ($this->index - 1) >= 0;
		}


		/**
		 * @return bool|mixed
		 */
		public function previous(){
			if( $this->hasPrevious() ){
			    $this->index--;
				$data = $this->map->get($this->keys[$this->index]);
				return $data;
			}
			else{
                return null;
            }
		}


		/**
		 * @return int
		 */
		public function index(){
			return $this->index;
		}


		/**
		 * Removes current element from Map
		 */
		public function remove(){
			$this->map->remove($this->keys[$this->index]);
			$this->keys = $this->map->getKeys();
			$this->index--;
		}


		/**
		 * Resets the iterator for next iterations
		 */
		public function reset(){
			$this->index = 0;
			$this->keys = $this->map->getKeys();
		}


		/**
		 * @return Map
		 * Returns the original object which is being iterated by the iterator
		 */
		public function getCollection(){
			return $this->map;
		}

	}