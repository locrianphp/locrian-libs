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

    use Locrian\Collections\LinkedList;

    class LinkedListIterator implements Iterator{

        /**
         * @var int current index
         */
        private $index;


        /**
         * @var LinkedList original list
         */
        private $list;


        /**
         * LinkedListIterator constructor.
         *
         * @param $list LinkedList original linked list
         */
        public function __construct(LinkedList $list){
            $this->index = -1;
            $this->list = $list;
        }


        /**
         * @return bool
         */
        public function hasNext(){
            return ($this->index + 1) < $this->list->size();
        }


        /**
         * @return mixed
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
         * @return null|mixed
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
         * @return int
         */
        public function index(){
            return $this->index;
        }


        /**
         * Removes the current element from the list
         */
        public function remove(){
            $this->list->removeByIndex($this->index);
            $this->index--; // Current index will be the previous one after deletion
        }


        /**
         * Resets the iterator
         */
        public function reset(){
            $this->index = -1;
        }


        /**
         * @return LinkedList returns the origin
         */
        public function getCollection(){
            return $this->list;
        }

    }