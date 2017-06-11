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
    use Locrian\InvalidArgumentException;
    use Locrian\Jsonable;
    use Locrian\Arrayable;
    use Locrian\Cloneable;
    use RuntimeException;
    use Locrian\Collections\Iterator\Iterate;
    use Locrian\Collections\Iterator\MapIterator;

    class HashMap implements Map, Cloneable, Jsonable, Arrayable, Iterate{

        /**
         * @var array keys
         */
        protected $keys;


        /**
         * @var array values
         */
        protected $values;


        /**
         * @var int map size
         */
        protected $mapSize;


        /**
         * @var integer indexer
         */
        protected $indexer;


        /**
         * Map constructor.
         *
         * @param array $items
         */
        public function __construct($items = []){
            $this->keys = [];
            $this->values = [];
            $this->mapSize = 0;
            $this->indexer = 0;

            if( is_array($items) && count($items) != 0 ){
                $this->addAll($items);
            }
        }


        /**
         * @param $key
         * @param $value
         *
         * @throws RuntimeException
         * Adds a new element to the Map if the key does'nt exist
         */
        public function add($key, $value){
            if( !$this->has($key) ){
                $this->keys[$key] = $this->indexer;
                $this->values[$this->indexer] = $value;
                $this->mapSize++;
                $this->indexer++;
            }
        }


        /**
         * @param $items
         *
         * @throws InvalidArgumentException
         * Parses an array and adds those items to the map
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
                    $this->add($key, $value);
                }
            }
        }


        /**
         * @param $key
         * @param $value
         *
         * @throws RuntimeException
         * Overrides a value or adds new one
         */
        public function set($key, $value){
            if( !$this->has($key) ){
                $this->add($key, $value);
            }
            else{
                $this->values[$this->keys[$key]] = $value;
            }
        }


        /**
         * @param $key
         *
         * @return bool
         */
        public function has($key){
            return isset($this->keys[$key]);
        }


        /**
         * @param $key
         *
         * @return mixed
         * Returns an element which has the key $key
         */
        public function get($key){
            if( $this->has($key) ){
                return $this->values[$this->keys[$key]];
            }
            return null;
        }


        /**
         * @param $key
         *
         * @return mixed
         */
        public function remove($key){
            if( !$this->has($key) ){
                return null;
            }
            else{
                $data = $this->values[$this->keys[$key]];
                unset($this->values[$this->keys[$key]]);
                unset($this->keys[$key]);
                $this->mapSize--;
                return $data;
            }
        }


        /**
         * @return int size of the Map
         */
        public function size(){
            return $this->mapSize;
        }


        /**
         * @return bool
         */
        public function isEmpty(){
            return $this->size() === 0;
        }


        /**
         * Clears the Map
         */
        public function clear(){
            $this->mapSize = 0;
            $this->keys = [];
            $this->values = [];
        }


        /**
         * @param $item
         *
         * @return int|string
         * Searches a value and returns its key
         */
        public function search($item){
            $returnKey = null;
            foreach( $this->keys as $key => $value ){
                if( $this->values[$value] === $item ){
                    $returnKey = $key;
                    break;
                }
            }
            return $returnKey;
        }


        /**
         * @return MapIterator
         * Returns an iterator object for Map
         */
        public function iterator(){
            return new MapIterator($this);
        }


        /**
         * @return array keys
         */
        public function getKeys(){
            return array_keys($this->keys);
        }


        /**
         * @return array values
         */
        public function getValues(){
            return array_values($this->values);
        }


        /**
         * @return mixed
         * Returns the first element of the Map
         */
        public function first(){
            if( $this->size() === 0 ){
                return null;
            }
            else{
                return $this->get($this->getKeys()[0]);
            }
        }


        /**
         * @return mixed
         * Returns the last element of the Map
         */
        public function last(){
            if( $this->size() === 0 ){
                return null;
            }
            else{
                $keys = $this->getKeys();
                $endKey = end($keys);
                return $this->get($endKey);
            }
        }


        /**
         * @param Closure $callback
         * Foreach implementation with callback
         */
        public function each(Closure $callback){
            $keys = $this->getKeys();
            foreach( $keys as $key ){
                $callback($key, $this->get($key));
            }
        }


        /**
         * @param Closure $callback
         * @return Map filter
         * Filters a list. If callback returns true then element is added to the new list. If not element will not
         *     added to new list
         */
        public function filter(Closure $callback){
            $filtered = new HashMap();
            $this->each(function($key, $value) use ($callback, $filtered){
                $res = $callback($key, $value);
                if( $res === true ){
                    $filtered->add($key, $value);
                }
            });
            return $filtered;
        }


        /**
         * @return Map
         * Clones the map object
         */
        public function makeClone(){
            $data = array_combine(array_keys($this->keys), array_values($this->values));
            return new HashMap($data);
        }


        /**
         * @return string
         * Returns the json version of this object
         */
        public function toJson(){
            $data = array_combine(array_keys($this->keys), array_values($this->values));
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
            $data = array_combine(array_keys($this->keys), array_values($this->values));
            foreach( $data as $key => $value ){
                if( $value instanceof Arrayable ){
                    $data[$key] = $value->toArray();
                }
            }
            return $data;
        }
    }