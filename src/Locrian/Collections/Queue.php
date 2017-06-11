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
    use Locrian\Collections\Iterator\Iterate;
    use Locrian\Collections\Iterator\LinkedListIterator;
    use Locrian\InvalidArgumentException;
    use Locrian\Jsonable;

    class Queue implements Cloneable, Arrayable, Jsonable, Collection, Iterate{

        /**
         * @var LinkedList the list
         */
        private $linkedList;


        /**
         * Queue constructor.
         *
         * @param array $dataSet
         */
        public function __construct(array $dataSet = []){
            $this->linkedList = new LinkedList();
            $this->pushAll($dataSet);
        }


        /**
         * @param $item mixed
         * Adds new item to queue
         */
        public function push($item){
            $this->linkedList->addLast($item);
        }


        /**
         * @param $items array
         *
         * @throws InvalidArgumentException
         * Parses an array and adds its elements to a queue. First element will be the first one in the queue.
         * For example if the array is something like [ 3, 4, 15, 12, 7 ], queue would be 7 (last one) -> 12 -> 15 -> 4
         *     -> 3 (first one)
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
                    $this->push($items[$i]);
                }
            }
        }


        /**
         * @return mixed
         * Returns and removes the next item
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
         * Returns the first item
         */
        public function top(){
            return $this->linkedList->first();
        }


        /**
         * @return Queue
         * Clones the queue
         */
        public function makeClone(){
            $arr = $this->linkedList->toArray();
            return new Queue($arr);
        }


        /**
         * @return array
         * Creates an array from the queue
         */
        public function toArray(){
            return $this->linkedList->toArray();
        }


        /**
         * @return string
         * Converts queue to json
         */
        public function toJson(){
            return $this->linkedList->toJson();
        }


        /**
         * @return LinkedListIterator
         * Returns an iterator for the queue
         */
        public function iterator(){
            return $this->linkedList->iterator();
        }


        /**
         * Destroys the queue
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
         * @return bool
         */
        public function isEmpty(){
            return $this->size() === 0;
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
         * @return Queue filter
         * Filters a list. If callback returns true then element is added to the new list. If not element will not
         *     added to new list
         */
        public function filter(Closure $callback){
            $filtered = new Queue();
            $this->each(function($i, $ele) use ($callback, $filtered){
                $res = $callback($i, $ele);
                if( $res === true ){
                    $filtered->push($ele);
                }
            });
            return $filtered;
        }

    }