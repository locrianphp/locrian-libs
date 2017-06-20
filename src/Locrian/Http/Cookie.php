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

    namespace Locrian\Http;

    use Locrian\Crypt\HashHMAC;
    use Locrian\InvalidArgumentException;

    class Cookie{

        /**
         * @var boolean httpOnly parameter
         */
        private $httpOnly;


        /**
         * @var string Cookie Path
         */
        private $path;


        /**
         * @var string
         */
        private $domain;


        /**
         * @var boolean
         */
        private $secure;


        /**
         * @var boolean allow encryption of cookie keys
         */
        private $cryptKeys;


        /**
         * @var HashHMAC
         */
        private $hash;


        /**
         * Cookie constructor.
         *
         * @param null|\Locrian\Crypt\HashHMAC $hash
         * @param string $path
         * @param bool $httpOnly
         * @param string $domain
         * @param bool $secure
         * @throws \Locrian\InvalidArgumentException
         */
        public function __construct($hash = null, $path = "/", $httpOnly = true, $domain = "", $secure = false){
            if( $hash == null || $hash instanceof HashHMAC ){
                $this->path = $path;
                $this->httpOnly = $httpOnly;
                $this->cryptKeys = $hash == null ? false : true;
                $this->hash = $hash;
                $this->domain = $domain;
                $this->secure = $secure;
            }
            else{
                throw new InvalidArgumentException("Hash must be an instance of HashHMAC");
            }
        }


        /**
         * @param $name String
         *
         * @return array or string which is value of given cookie name
         * @throws InvalidArgumentException
         *
         * $cookie->get("test");     // if a cookie exists with name "test", this method returns the value of that
         *     cookie. Otherwise it returns null.
         */
        public function get($name){
            if( is_string($name) && $name != "" ){
                $name = $this->cryptKeys ? $this->hash->sha1($name) : $name;
                return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
            }
            else{
                throw new InvalidArgumentException("Cookie name must be a string");
            }
        }


        /**
         * @return array
         * Return all cookies
         */
        public function getAll(){
            return $_COOKIE;
        }


        /**
         * @param $name string cookie name
         * @param $value string cookie value
         * @param $time string lifetime ("+1 hour")
         *
         * @param null $domain
         * @param null $secure
         * @param null $path
         * @param null $httpOnly
         * @throws \Locrian\InvalidArgumentException if $httpOnly is true then cookie will only be accessible by http requests
         *
         * $cookie->set("test","test_value","+5 days");
         */
        public function set($name, $value, $time, $path = null, $httpOnly = null, $domain = null, $secure = null){
            if( is_string($name) ){
                if( is_string($time) ){
                    $fixedTime = strtotime($time);
                }
                else if( is_int($time) ){
                    $fixedTime = $time;
                }
                else{
                    throw new InvalidArgumentException("Invalid time");
                }
                if( $path == null ){
                    $path = $this->path;
                }
                if( $httpOnly == null ){
                    $httpOnly = $this->httpOnly;
                }
                if( $domain == null ){
                    $domain = $this->domain;
                }
                if( $secure == null ){
                    $secure = $this->secure;
                }
                $name = $this->cryptKeys ? $this->hash->sha1($name) : $name;
                setcookie($name, $value, $fixedTime, $path, $domain, $secure, $httpOnly);
            }
            else{
                throw new InvalidArgumentException("Name and time must be a string!");
            }
        }


        /**
         * @param $name String cookie name
         *
         * @return bool true | false
         * @throws InvalidArgumentException
         *
         * Tests if the cookie with given name exists.
         *
         * if( $cookie->has("test") ){
         *      echo "Test : " . $cookie->get("test");
         * }
         */
        public function has($name){
            if( is_string($name) && (strlen($name) > 0) ){
                // Check encryption
                $name = $this->cryptKeys ? $this->hash->sha1($name) : $name;
                if( isset($_COOKIE[$name]) ){
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                throw new InvalidArgumentException("Name must be a string");
            }
        }


        /**
         * @param $name String cookie name which will be deleted
         *
         * @throws InvalidArgumentException
         *
         * Deletes a specific cookie.
         *
         * if( $cookie->has("test") ){
         *      $cookie->delete("test");
         * }
         */
        public function remove($name){
            if( is_string($name) ){
                // Check encryption
                $realName = $this->cryptKeys ? $this->hash->sha1($name) : $name;
                if( isset($_COOKIE[$realName]) ){
                    $this->set($name, "", "-1 day");
                }
            }
            else{
                throw new InvalidArgumentException("Name must be a string");
            }
        }


        /**
         * Removes all the cookies including session
         */
        public function destroy(){
            // Take the current value of the crypt
            $tmp = $this->cryptKeys;
            // Make it false so that we can destroy all the cookies with using set() method
            // If we don't do this encrypted keys will be encrypt again and because of that no cookie will be removed.
            $this->cryptKeys = false;
            foreach( $_COOKIE as $key => $val ){
                $this->set($key, "", "-1 day");
            }
            // Set back the crypt
            $this->cryptKeys = $tmp;
        }

    }