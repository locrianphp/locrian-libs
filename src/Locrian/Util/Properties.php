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

    namespace Locrian\Util;

    use Locrian\Collections\HashMap;
    use Locrian\InvalidArgumentException;
    use Locrian\IO\File;

    class Properties{

        /**
         * @var File properties file
         */
        private $file;


        /**
         * @var \Locrian\Collections\HashMap
         * Holds the property key value pairs
         */
        private $map;


        /**
         * @var string separator to separate keys and values
         */
        private $keyValueSeparator;


        /**
         * @var string separator to separate properties
         */
        private $propertySeparator;


        /**
         * Properties constructor.
         *
         * @param $file File
         *
         * @throws InvalidArgumentException
         */
        public function __construct(File $file){
            $this->file = $file;
            $this->map = new HashMap();
            $this->keyValueSeparator = "=";
            $this->propertySeparator = "\r\n";
        }


        /**
         * Load key value pairs from property file if exists
         *
         * @return $this
         */
        public function load(){
            if( $this->file->exists() ){
                $content = FileUtils::readText($this->file);
                $content = explode($this->propertySeparator, $content);
                foreach( $content as $value ){
                    $keyValuePair = explode($this->keyValueSeparator, $value);
                    $len = count($keyValuePair);
                    if( $len > 0 ){
                        if( $len == 1 ){
                            $this->map->add($keyValuePair[0], "");
                        }
                        else{
                            $tmpArr = $keyValuePair;
                            if( $len > 2 ){
                                unset($tmpArr[0]);
                                $val = implode("=", $tmpArr);
                            }
                            else{
                                $val = $keyValuePair[1];
                            }
                            $this->map->add($keyValuePair[0], $val);
                        }
                    }
                }
            }
            return $this;
        }


        /**
         * @param $propName string property name
         * @param null $defaultValue the value when the property does not exist
         *
         * @return mixed|null
         * @throws InvalidArgumentException
         */
        public function getString($propName, $defaultValue = null){
            if( $this->map->has($propName) ){
                return $this->map->get($propName);
            }
            else{
                return $defaultValue;
            }
        }


        /**
         * @param string $propName
         * @param int $defaultValue
         * @return int
         */
        public function getInt($propName, $defaultValue = 0){
            if( $this->map->has($propName) ){
                return intval($this->map->get($propName));
            }
            else{
                return $defaultValue;
            }
        }


        /**
         * @param string $propName
         * @param int $defaultValue
         * @return float|int
         */
        public function getDouble($propName, $defaultValue = 0){
            if( $this->map->has($propName) ){
                return doubleval($this->map->get($propName));
            }
            else{
                return $defaultValue;
            }
        }


        /**
         * @param string $propName
         * @param bool $defaultValue
         * @return bool
         */
        public function getBoolean($propName, $defaultValue = false){
            if( $this->map->has($propName) ){
                $val = $this->map->get($propName);
                return $val == "true" ? true : false;
            }
            else{
                return $defaultValue;
            }
        }


        /**
         * @param $propName string property name
         * @param $propValue mixed property name
         *
         * @throws InvalidArgumentException
         * @return $this
         */
        public function setProperty($propName, $propValue){
            if( is_string($propName) ){
                if( $this->map->has($propName) ){
                    $this->map->set($propName, $propValue);
                }
                else{
                    $this->map->add($propName, $propValue);
                }
                return $this;
            }
            else{
                throw new InvalidArgumentException("Property name must be string.");
            }
        }


        /**
         * Saves the properties to the given property file
         */
        public function commit(){
            $content = $this->prepareSave();
            FileUtils::writeText($this->file, $content, FileUtils::OVERWRITE);
        }


        /**
         * @param $propName string
         *
         * @throws InvalidArgumentException
         * @return $this
         */
        public function removeProperty($propName){
            if( is_string($propName) ){
                if( $this->map->has($propName) ){
                    $this->map->remove($propName);
                }
                return $this;
            }
            else{
                throw new InvalidArgumentException("Property name must be string.");
            }
        }


        /**
         * Clears all the properties
         *
         * @return $this
         */
        public function clear(){
            $this->map->clear();
            return $this;
        }


        /**
         * @return string
         */
        private function prepareSave(){
            $finalString = "";
            $names = $this->map->getKeys();
            foreach( $names as $name ){
                $finalString .= $name . $this->keyValueSeparator . $this->map->get($name) . $this->propertySeparator;
            }
            return trim(trim($finalString, $this->propertySeparator), $this->keyValueSeparator);
        }


    }
