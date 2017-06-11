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

	use Locrian\Collections\ArrayList;

    class ArrayListIterator implements Iterator{

		/**
		 * @var ArrayList list
		 */
		private $list;


		/**
		 * @var int current index
		 */
		private $index;


		/**
		 * ArrayListIterator constructor.
		 *
		 * @param ArrayList $list
		 */
		public function __construct(ArrayList $list){
			$this->list = $list;
			$this->index = -1;
		}


		/**
		 * @return bool existence of the next item
		 */
		public function hasNext(){
			return ($this->index + 1) < $this->list->size();
		}


		/**
		 * @return bool|mixed
		 * @throws \Locrian\InvalidArgumentException
		 */
		public function next(){
			if( $this->hasNext() ){
                $this->index++;
				$next = $this->list->get($this->index);
				return $next;
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
		 * @throws \Locrian\InvalidArgumentException
		 */
		public function previous(){
			if( $this->hasPrevious() ){
                $this->index--;
				$prev = $this->list->get($this->index);
				return $prev;
			}
			else{
				return null;
			}
		}


		/**
		 * @return int current index
		 */
		public function index(){
			return $this->index;
		}


		/**
		 * @throws \Locrian\IndexOutOfBoundsException
		 * @throws \Locrian\InvalidArgumentException
		 */
		public function remove(){
			$this->list->remove($this->index);
			$this->index--; // ArrayList will recreate its indexes so the current index will be the previous one
		}


		/**
		 * Resets the iterator to start again
		 */
		public function reset(){
			$this->index = -1;
		}


		/**
		 * @return ArrayList
		 * Returns the collection object which is being iterated by the iterator
		 */
		public function getCollection(){
			return $this->list;
		}

	}