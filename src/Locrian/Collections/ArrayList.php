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

	use Closure;
    use Locrian\Jsonable;
    use Locrian\Arrayable;
    use Locrian\Cloneable;
    use Locrian\InvalidArgumentException;
    use Locrian\IndexOutOfBoundsException;
    use Locrian\Collections\Iterator\Iterable;
    use Locrian\Collections\Iterator\ArrayListIterator;

    class ArrayList implements IndexedList, Cloneable, Jsonable, Arrayable, Iterable{

		/**
		 * @var array data
		 */
		protected $data;


		/**
		 * @var int ArrayList size
		 */
		protected $listSize;


		/**
		 * ArrayList constructor.
		 *
		 * @param array $items
		 * Arrays can be directly parsed here
		 */
		public function __construct($items = []){
			$this->data = [];
			$this->listSize = 0;
			if( is_array($items) && count($items) != 0 ){
				$this->addAll($items);
			}
		}


		/**
		 * @param $item string | Object | integer ...
		 * Adds new element to ArrayList
		 */
		public function add($item){
			$this->data[] = $item;
			$this->listSize++;
		}


        /**
         * @param $items
         *
         * @throws InvalidArgumentException
         * Parses an array adds its values to $_data
         */
        public function addAll($items){
            if( !is_array($items) && !($items instanceof Arrayable) ){
                throw new InvalidArgumentException("Data must be an array or an Arrayable instance.");
            }
            else{
                if( $items instanceof Arrayable ){
                    $items = $items->toArray();
                }
                foreach($items as $key => $value ){
                    $this->add($value);
                }
            }
        }


		/**
		 * @param $index
		 *
		 * @return mixed
		 * @throws InvalidArgumentException
		 * Returns the element which has the index $index
		 */
		public function get($index){
			if( !is_int($index) ){
				throw new InvalidArgumentException("Index must be an integer!");
			}
			if( isset($this->data[$index]) ){
				return $this->data[$index];
			}
			else{
				return null;
			}
		}


		/**
		 * @param $index
		 * @param $value
		 *
		 * @throws IndexOutOfBoundsException
		 * Overrides an existing indexed value or adds new value to the list
		 */
		public function set($index, $value){
            if( $this->has($index) ){
                $this->data[$index] = $value;
            }
		}


		/**
		 * @param $index
		 *
		 * @return bool
		 */
		public function has($index){
			return isset($this->data[$index]);
		}


		/**
		 * @return int Size of the ArrayList
		 */
		public function size(){
			return $this->listSize;
		}


        /**
         * @return bool
         */
        public function isEmpty(){
            return $this->size() === 0;
        }


		/**
		 * @param $index
		 *
		 * @return mixed
		 * @throws IndexOutOfBoundsException
		 * @throws InvalidArgumentException
		 *
		 */
		public function remove($index){
			if( !is_int($index) ){
				throw new InvalidArgumentException("Index must be an integer!");
			}
			if( isset($this->data[$index]) ){
				$data = $this->data[$index];
				unset($this->data[$index]);
				$items = $this->data;
				$this->clear();
				$this->addAll($items);
				return $data;
			}
			else{
				throw new IndexOutOfBoundsException("Index '" . $index . "' is out of bounds!");
			}
		}


		/**
		 * Clears all the data in the list
		 */
		public function clear(){
			$this->listSize = 0;
			$this->data = [];
		}


		/**
		 * @param $item
		 *
		 * @return int|string
		 * Searches an item in the list and returns it's index if it is found.
		 * If it is not fount method returns -1
		 */
		public function search($item){
			$index = -1;
			foreach($this->data as $key => $value ){
				if( $value === $item ){
					$index = $key;
					break;
				}
			}
			return $index;
		}


		/**
		 * @return ArrayListIterator an iterator for ArrayList
		 */
		public function iterator(){
			return new ArrayListIterator($this);
		}


		/**
		 * @return bool|mixed
		 * @throws InvalidArgumentException
		 * Returns the first element of the ArrayList
		 */
		public function first(){
			if( $this->size() === 0 ){
				return null;
			}
			else{
				return $this->get(0);
			}
		}


		/**
		 * @return bool|mixed
		 * @throws InvalidArgumentException
		 * Returns the last element of the ArrayList
		 */
		public function last(){
			if( $this->size() === 0 ){
				return null;
			}
			else{
				return $this->get($this->size() - 1);
			}
		}


		/**
		 * @param Closure $callback
		 * Builtin foreach implementation with callback
		 */
		public function each(Closure $callback){
			$len = $this->size();
			for( $i = 0; $i < $len; $i++){
				$callback($i, $this->get($i));
			}
		}


		/**
		 * @param Closure $callback
		 * @return ArrayList filter
		 * Filters a list. If callback returns true then element is added to the new list. If not element will not added to new list
		 */
		public function filter(Closure $callback){
			$filtered = new ArrayList();
			$this->each(function($i, $ele) use($callback, $filtered){
				$res = $callback($i, $ele);
				if( $res === true ){
					$filtered->add($ele);
				}
			});
			return $filtered;
		}


		/**
		 * @return ArrayList
		 */
		public function makeClone(){
			$data = $this->data;
			return new ArrayList($data);
		}


		/**
		 * @return string
		 * Converts the list to json
		 */
		public function toJson(){
			$data = $this->data;
			foreach( $data as $key => $value ){
				if( $value instanceof Arrayable ){
					$data[$key] = $value->toArray();
				}
			}
			return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		}


		/**
		 * @return array
		 * Returns the array version of this object
		 */
		public function toArray(){
			$data = $this->data;
			foreach( $data as $key => $value ){
				if( $value instanceof Arrayable ){
					$data[$key] = $value->toArray();
				}
			}
			return $data;
		}

	}