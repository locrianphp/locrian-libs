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
    use Locrian\Jsonable;
    use Locrian\UnsupportedOperationException;

    class TreeMap implements Collection, Cloneable, Arrayable, Jsonable{

        /**
         * @var TreeMap
         */
        private $parent;


        /**
         * @var HashMap
         */
        private $children;


        /**
         * @var mixed
         */
        private $key;


        /**
         * @var mixed
         */
        private $value;


        /**
         * TreeNode constructor.
         * @param $key mixed
         * @param $value mixed
         */
        public function __construct($key, $value){
            $this->key = $key;
            $this->value = $value;
            $this->parent = null;
            $this->children = new HashMap();
        }


        /**
         * @param $key TreeMap|mixed
         * @param $value mixed|null value or null
         * Adds new child
         * add(TreeMapChild)
         * add(key, value)
         */
        public function add($key, $value = null){
            if( $key instanceof TreeMap ){
                $child = $key;
                $key = $child->getKey();
            }
            else{
                $child = new TreeMap($key, $value);
            }
            $child->setParent($this);
            $this->children->add($key, $child);
        }


        /**
         * @param $key mixed
         * @return TreeMap
         * Returns the child that is mapped by given key
         */
        public function get($key){
            return $this->children->get($key);
        }


        /**
         * @param $key
         * Removes the child that is mapped by given key
         */
        public function remove($key){
            $this->children->remove($key);
        }


        /**
         * @return mixed
         */
        public function getKey(){
            return $this->key;
        }


        /**
         * @param mixed $key
         */
        public function setKey($key){
            $this->key = $key;
        }


        /**
         * @return TreeMap|null
         */
        public function getParent(){
            return $this->parent;
        }


        /**
         * @param mixed $parent
         */
        public function setParent($parent){
            $this->parent = &$parent;
        }


        /**
         * @return HashMap
         */
        public function getChildren(){
            return $this->children;
        }


        /**
         * @param mixed $children
         */
        public function setChildren($children){
            $this->children = $children;
        }


        /**
         * @return mixed
         */
        public function getValue(){
            return $this->value;
        }


        /**
         * @param mixed $value
         */
        public function setValue($value){
            $this->value = $value;
        }


        /**
         * Clears all the children
         */
        public function clear(){
            $this->children->clear();
        }


        /**
         * @param $key mixed
         * @return bool
         * Checks the child exist
         */
        public function has($key){
            return $this->children->has($key);
        }


        /**
         * @param $item
         * @return TreeMap|null
         * Search a value
         */
        public function search($item){
            if( $this->value == $item ){
                return $this;
            }
            else{
                foreach( $this->children->getKeys() as $i ) {
                    $result = $this->children->get($i)->search($item);
                    if( $result != null ){
                        return $result;
                    }
                }
                return null;
            }
        }


        /**
         * @return int
         * Size of the tree
         */
        public function size(){
            $size = 1;
            foreach( $this->children->getKeys() as $i ) {
                $size += $this->children->get($i)->size();
            }
            return $size;
        }


        /**
         * @return bool
         */
        public function isEmpty(){
            return $this->size() === 0;
        }


        /**
         * @param Closure $callback
         * Simple dept first iterator
         */
        public function each(Closure $callback){
            $callback($this->key, $this);
            foreach( $this->children->getKeys() as $i ) {
                $this->children->get($i)->each($callback);
            }
        }


        /**
         * @param Closure $callback
         * @throws UnsupportedOperationException
         */
        public function filter(Closure $callback){
            throw new UnsupportedOperationException("Filter is not supported on TreeMap");
        }


        /**
         * @return TreeMap
         * Clones the tree starting this node. Parent nodes will not be cloned!!!
         */
        public function makeClone(){
            $root = new TreeMap($this->getKey(), $this->getValue());
            $root->setParent($this->getParent());
            foreach( $this->children->getKeys() as $i ) {
                $child = $this->children->get($i)->makeClone();
                $root->add($i, $child);
            }
            return $root;
        }


        /**
         * @return array|mixed
         * Converts tree to array (leaf nodes will not be converted, they will return value only)
         */
        public function toArray(){
            if( $this->children->isEmpty() ){
                return ($this->getValue() instanceof Arrayable) ? $this->getValue()->toArray() : $this->getValue();
            }
            else{
                $tmp = [];
                foreach( $this->children->getKeys() as $i ){
                    $c = $this->children->get($i);
                    $tmp[$c->getKey()] = $c->toArray();
                }
                return $tmp;
            }
        }


        /**
         * @return string
         * Convert tree to json
         */
        public function toJson(){
            return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    }