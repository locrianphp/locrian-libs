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
    use Locrian\Arrayable;
    use Locrian\Cloneable;
    use Locrian\Collections\Iterator\Iterable;
    use Locrian\Collections\Iterator\LinkedListIterator;
    use Locrian\InvalidArgumentException;
    use Locrian\Jsonable;

    class Stack implements Cloneable, Arrayable, Jsonable, Collection, Iterable{

		/**
		 * @var LinkedList the list
		 */
		private $linkedList;


		/**
		 * Stack constructor.
		 *
		 * @param array $dataSet
		 */
		public function __construct(array $dataSet = []){
			$this->linkedList = new LinkedList();
			$this->pushAll($dataSet);
		}


		/**
		 * @param $item mixed
		 * Adds new item to stack
		 */
		public function push($item){
			$this->linkedList->addFirst($item);
		}


        /**
         * @param $items array
         *
         * @throws InvalidArgumentException
         * Parses an array and adds its elements to a stack. First element will be the deeper one in the stack.
         * For example if the array is something like [ 3, 4, 15, 12, 7 ], stack would be 7 (bottom) -> 12 -> 15 -> 4
         *     -> 3 (top)
         */
        public function pushAll($items){
            if( !is_array($items) && !($items instanceof Arrayable) ){
                throw new InvalidArgumentException("Data must be an array or an Arrayable instance.");
            }
            else{
                if( $items instanceof Arrayable ){
                    $items = $items->toArray();
                }
                $size = count($items);
                for( $i = 0; $i < $size; $i++ ){
                    $this->linkedList->addFirst($items[$i]);
                }
            }
        }


		/**
		 * @return mixed
		 * Returns and removes the top item
		 */
		public function pop(){
			if( $this->linkedList->size() > 0 ){
                $item = $this->linkedList->first();
                $this->linkedList->removeByIndex(0);
                return $item;
            }
            else{
			    return null;
            }
		}


		/**
		 * @return mixed
		 * Returns top item
		 */
		public function top(){
			return $this->first();
		}


		/**
		 * @return Stack
		 * Clones the stack
		 */
		public function makeClone(){
			$arr = $this->linkedList->toArray();
			return new Stack($arr);
		}


		/**
		 * @return array
		 * Creates an array from the stack
		 */
		public function toArray(){
			return $this->linkedList->toArray();
		}


        /**
         * @return string
         * Converts stack to a json
         */
		public function toJson(){
		    return $this->linkedList->toJson();
        }


        /**
		 * @return LinkedListIterator
		 * Returns an iterator for the stack
		 */
		public function iterator(){
			return $this->linkedList->iterator();
		}


		/**
		 * Destroys the stack
		 */
		public function clear(){
			$this->linkedList->clear();
		}


		/**
		 * @param $item mixed item to search
		 *
		 * @return int index of the item
		 */
		public function search($item){
			return $this->linkedList->search($item);
		}


		/**
		 * @param $key mixed
		 *
		 * @return mixed
		 * We wont be using $key parameter but we have to implement it because it comes from the collection interface
		 */
		public function remove($key){
			return $this->pop();
		}


		/**
		 * @return int
		 */
		public function size(){
			return $this->linkedList->size();
		}


		/**
		 * @return mixed
		 */
		public function first(){
			return $this->linkedList->first();
		}


		/**
		 * @return mixed
		 */
		public function last(){
			return $this->linkedList->last();
		}


		/**
		 * @param Closure $callback
		 * Builtin foreach implementation with callback
		 */
		public function each(Closure $callback){
            $i = 0;
            $it = $this->iterator();
            while( $it->hasNext() ){
                $item = $it->next();
                $callback($i, $item);
                $i++;
            }
		}


		/**
		 * @param Closure $callback
		 * @return Stack filter
		 * Filters a list. If callback returns true then element is added to the new list. If not element will not added to new list
		 */
		public function filter(Closure $callback){
			$filtered = [];
			$this->each(function($i, $ele) use($callback, $filtered){
				$res = $callback($i, $ele);
				if( $res === true ){
					$filtered[] = $ele;
				}
			});
			return new Stack($filtered);
		}

	}