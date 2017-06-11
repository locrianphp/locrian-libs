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
    use Locrian\InvalidArgumentException;
    use Locrian\IndexOutOfBoundsException;
    use Locrian\Collections\Iterator\Iterate;
    use Locrian\Collections\Iterator\LinkedListIterator;
    use Locrian\Jsonable;

    class LinkedList implements Collection, IndexedList, Cloneable, Arrayable, Jsonable, Iterate{

        /**
         * @var ListNode head node
         */
        private $head;


        /**
         * @var ListNode last node
         */
        private $last;


        /**
         * @var int size
         */
        private $listSize;


        /**
         * LinkedList constructor.
         *
         * @param array $items first items
         */
        public function __construct(array $items = []){
            $this->head = null;
            $this->last = null;
            $this->listSize = 0;
            $this->addAll($items);
        }


        /**
         * @param $item
         * Adds new item to the end of the list
         */
        public function add($item){
            $this->addLast($item);
        }


        /**
         * @param $index integer
         * @param $data mixed
         * @throws IndexOutOfBoundsException
         * Add item to the given index
         */
        public function addTo($index, $data){
            if( $this->has($index) || $index == $this->size() ){ // $index == $this->size() means add last
                $node = new ListNode($data);
                if( $this->size() == 0 ){
                    $this->head = &$node;
                    $this->last = &$node;
                }
                else{
                    if( $index == 0 ){ // Add first
                        $node->setNext($this->head);
                        $this->head = &$node;
                    }
                    else{
                        if( $index == $this->size() ){ // Add last
                            $this->last->setNext($node);
                            $this->last = &$node;
                        }
                        else{ // Add somewhere in the middle
                            $current = $this->head;
                            $i = 0;
                            while( $current->getNext() != null ){
                                if( $index == ($i + 1) ){
                                    $node->setNext($current->getNext());
                                    $current->setNext($node);
                                    break;
                                }
                                else{
                                    $current = $current->getNext();
                                    $i++;
                                }
                            }
                        }
                    }
                }
                $this->listSize++;
            }
            else{
                throw new IndexOutOfBoundsException("Invalid index");
            }
        }


        /**
         * @param $index
         * @param $data
         * @throws IndexOutOfBoundsException
         * Adds new element after the given index
         */
        public function addAfter($index, $data){
            $this->addTo($index + 1, $data);
        }


        /**
         * @param $index integer
         * @param $data mixed
         * Adds new element before the given index
         */
        public function addBefore($index, $data){
            $this->addTo($index, $data);
        }


        /**
         * @param $data mixed
         * Adds new item to the end of the list
         */
        public function addLast($data){
            $this->addTo($this->size(), $data);
        }


        /**
         * @param $data mixed
         * Adds new item to the beginning of the list
         */
        public function addFirst($data){
            $this->addTo(0, $data);
        }


        /**
         * @param $items array|Arrayable
         *
         * @throws InvalidArgumentException
         * Adds all the items to the last of
         */
        public function addAll($items){
            if( !is_array($items) && !($items instanceof Arrayable) ){
                throw new InvalidArgumentException("Data must be an array or an Arrayable instance.");
            }
            else{
                if( $items instanceof Arrayable ){
                    $items = $items->toArray();
                }
                foreach( $items as $key => $value ){
                    $this->addLast($value);
                }
            }
        }


        /**
         * @param $index integer
         * @return mixed|null
         * Returns a value by index
         */
        public function get($index){
            $node = $this->findNodeByIndex($index);
            if( $node != null ){
                return $node->getData();
            }
            else{
                return null;
            }
        }


        /**
         * @param $index int
         * @param $value mixed
         * Overrides a value by given index
         */
        public function set($index, $value){
            $node = $this->findNodeByIndex($index);
            if( $node != null ){
                $node->setData($value);
            }
        }


        /**
         * @param $index integer
         * @return ListNode|null
         * Finds and returns node by its index
         */
        private function findNodeByIndex($index){
            if( $this->has($index) ){
                $current = $this->head;
                for( $i = 0; $i < $this->listSize; $i++ ){
                    if( $i === $index ){
                        break;
                    }
                    else{
                        $current = $current->getNext();
                    }
                }
                return $current;
            }
            else{
                return null;
            }
        }


        /**
         * @param $index integer
         * @return bool existence of given item
         * Checks if the given item is in the list
         */
        public function has($index){
            return $this->size() > $index && $index >= 0;
        }


        /**
         * @return LinkedList
         * Clones the list
         */
        public function makeClone(){
            $arr = $this->toArray();
            return new LinkedList($arr);
        }


        /**
         * @return array Turns the list into an array
         */
        public function toArray(){
            $arr = [];
            $tmp = $this->head;
            while( $tmp != null ){
                $item = $tmp->getData();
                if( $item instanceof Arrayable ){
                    $arr[] = $item->toArray();
                }
                else{
                    $arr[] = $item;
                }
                $tmp = $tmp->getNext();
            }
            return $arr;
        }


        /**
         * @return string
         * Converts list to json
         */
        public function toJson(){
            return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }


        /**
         * @return LinkedListIterator returns an iterator for the linked list
         */
        public function iterator(){
            return new LinkedListIterator($this);
        }


        /**
         * Destroys the list
         */
        public function clear(){
            $this->head = null;
            $this->last = null;
            $this->listSize = 0;
        }


        /**
         * @param $item mixed
         *
         * @return int
         * Returns the index of the requested element
         * If element does not exist then returns -1
         */
        public function search($item){
            $currentIndex = 0;
            $visitor = $this->head;
            while( $visitor != null ){
                if( $visitor->getData() == $item ){
                    return $currentIndex;
                }
                $currentIndex++;
                $visitor = $visitor->getNext();
            }
            return -1;
        }


        /**
         * @param $item mixed
         * Removes the only first founded element from the list
         */
        public function remove($item){
            $index = $this->search($item);
            if( $index >= 0 ){
                $this->removeByIndex($index);
            }
        }


        /**
         * @param $item mixed
         * Removes all duplicated elements as well
         */
        public function removeMany($item){
            do{
                $index = $this->search($item);
                if( $index >= 0 ){
                    $this->removeByIndex($index);
                }
            }
            while( $index >= 0 );
        }


        /**
         * @param $index
         *
         * @throws IndexOutOfBoundsException
         * Removes an element from the list by its index
         */
        public function removeByIndex($index){
            if( $this->size() <= $index ){
                throw new IndexOutOfBoundsException("Given element index is larger then the list size.");
            }
            else{
                if( $this->size() > 0 ){
                    if( $this->size() == 1 ){
                        $this->head = null;
                        $this->last = null;
                        $this->listSize = 0;
                    }
                    else{
                        if( $index == 0 ){ // Head
                            $this->head = $this->head->getNext();
                            $this->listSize--;
                        }
                        else{
                            if( $index == ($this->size() - 1) ){ // Tail
                                $tmp = $this->head;
                                for( $i = 0; $i < $this->size() - 2; $i++ ){
                                    $tmp = $tmp->getNext();
                                }
                                $tmp->setNext(null);
                                $this->last = $tmp;
                                $this->listSize--;
                            }
                            else{
                                $currentIndex = 1;
                                $prev = $this->head;
                                $current = $prev->getNext();
                                while( $current != null ){
                                    if( $currentIndex == $index ){
                                        $prev->setNext($current->getNext());
                                        $this->listSize--;
                                        break;
                                    }
                                    $currentIndex++;
                                    $prev = $prev->getNext();
                                    $current = $current->getNext();
                                }
                            }
                        }
                    }
                }
            }
        }


        /**
         * @return int list item size
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
         * @return mixed value of the first item in the list
         * If there is no item then this method will return -1
         */
        public function first(){
            return $this->size() > 0 ? $this->head->getData() : null;
        }


        /**
         * @return mixed value of the last item in the list
         * If there is no item then this method will return -1
         */
        public function last(){
            return $this->size() > 0 ? $this->last->getData() : null;
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
         * @return LinkedList filter
         * Filters a list. If callback returns true then element is added to the new list. If not element will not
         *     added to new list
         */
        public function filter(Closure $callback){
            $filtered = new LinkedList();
            $this->each(function($i, $ele) use ($callback, $filtered){
                $res = $callback($i, $ele);
                if( $res === true ){
                    $filtered->addLast($ele);
                }
            });
            return $filtered;
        }

    }