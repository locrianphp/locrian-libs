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

    use Locrian\Cloneable;
    use Locrian\InvalidArgumentException;
    use Symfony\Component\Yaml\Exception\ParseException;

    class Uri implements Cloneable{

        /**
         * @var string http, https...
         */
        private $scheme;


        /**
         * @var string domain.org
         */
        private $host;


        /**
         * @var integer 80, 8080...
         */
        private $port;


        /**
         * @var string username (when authentication is required)
         */
        private $user;


        /**
         * @var string password (when authentication is required)
         */
        private $pass;


        /**
         * @var string /requestUri/ only the path. Doesn't involve queries or fragments
         */
        private $path;


        /**
         * @var string /requestUri/?q=test, query is q=test
         */
        private $query;


        /**
         * @var string /requestUri#fragment
         */
        private $fragment;


        /**
         * Uri constructor.
         */
        public function __construct(){
            $this->path = null;
            $this->host = null;
            $this->user = null;
            $this->pass = null;
            $this->query = null;
            $this->scheme = null;
            $this->fragment = null;
            $this->port = -1;
        }


        /**
         * @return Uri
         * Clone maker
         */
        public function makeClone(){
            $clone = clone $this;
            return $clone;
        }


        /**
         * @param $url
         *
         * @return \Locrian\Http\Uri
         * @throws InvalidArgumentException
         * @throws ParseException
         *
         * Parses string and creates a url
         */
        public static function parse($url){
            if( !is_string($url) ){
                throw new InvalidArgumentException("Uri must be a string!");
            }
            $parts = parse_url($url);
            if( !isset($parts['host']) || !isset($parts["scheme"]) ){
                throw new ParseException("Invalid uri");
            }
            $uri = new Uri();
            $uri->setScheme($parts['scheme']);
            $uri->setPath(isset($parts['path']) ? $parts['path'] : null);
            $uri->setQuery(isset($parts['query']) ? $parts['query'] : null);
            $uri->setHost($parts['host']);
            $uri->setUser(isset($parts['user']) ? $parts['user'] : null);
            $uri->setPassword(isset($parts['pass']) ? $parts['pass'] : null);
            $uri->setFragment(isset($parts['fragment']) ? $parts['fragment'] : null);
            if( isset($parts["port"]) ){
                $uri->setPort($parts["port"]);
            }
            else{
                if( $uri->getScheme() == "https" ){
                    $uri->port = isset($parts['port']) ? $parts['port'] : 443;
                }
                else if( $uri->getScheme() == "http" ){
                    $uri->port = isset($parts['port']) ? $parts['port'] : 80;
                }
            }
            return $uri;
        }


        /**
         * @return string
         * Returns full url
         */
        public function __toString(){
            $uri = $this->scheme == null ? "" : $this->scheme . "://";
            $auth = $this->user == null ? "" : ($this->user .
                ($this->pass == null ? "" : ":" . $this->pass) . "@");
            $uri = $uri
                . $auth
                . ($this->host == null ? "" : ($this->host))
                . (($this->port == 80 || $this->port == 443 || $this->port == -1) ? "" : ":" . $this->port)
                . (($this->path == null) ? "" : $this->path);
            if( $this->path == null && ($this->query != null || $this->fragment != null) ){
                $uri .= "/";
            }
            $uri .= ($this->query == null ? "" : "?" . $this->query)
                . ($this->fragment == null ? "" : "#" . $this->fragment);
            return $uri;
        }


        /**
         * * Getter Setter methods * *
         *
         *  Setter methods are useful if you want to change a property of a Uri object
         *  and keep the original Uri object. To do that you can clone the original object
         *  first and set the new properties. See the following example
         *
         *  $uri = new Uri(Environment.create());
         *  $newUri = $uri->makeClone()->setPort(88)->setPath("/newPath/");
         *  echo $uri();
         *  echo $newUri();
         *
         *  After this process outputs will be different and the original uri will still be the same.
         */


        /**
         * @return string Scheme
         */
        public function getScheme(){
            return $this->scheme;
        }


        /**
         * @param string $scheme
         *
         * @return $this
         */
        public function setScheme($scheme){
            $this->scheme = $scheme;
            return $this;
        }


        /**
         * @return string
         */
        public function getHost(){
            return $this->host;
        }


        /**
         * @param string $host
         *
         * @return $this
         */
        public function setHost($host){
            $this->host = $host;
            return $this;
        }


        /**
         * @return int
         */
        public function getPort(){
            return $this->port;
        }


        /**
         * @param int $port
         *
         * @return $this
         */
        public function setPort($port){
            $this->port = $port;
            return $this;
        }


        /**
         * @return string
         */
        public function getUser(){
            return $this->user;
        }


        /**
         * @param string $user
         *
         * @return $this
         */
        public function setUser($user){
            $this->user = $user;
            return $this;
        }


        /**
         * @return string
         */
        public function getPassword(){
            return $this->pass;
        }


        /**
         * @param string $pass
         *
         * @return $this
         */
        public function setPassword($pass){
            $this->pass = $pass;
            return $this;
        }


        /**
         * @return string
         */
        public function getPath(){
            return $this->path;
        }


        /**
         * @param string $path
         *
         * @return $this
         */
        public function setPath($path){
            $this->path = $path;
            return $this;
        }


        /**
         * @return string
         */
        public function getQuery(){
            return $this->query;
        }


        /**
         * @param string $query
         *
         * @return $this
         */
        public function setQuery($query){
            $this->query = $query;
            return $this;
        }


        /**
         * @return string
         */
        public function getFragment(){
            return $this->fragment;
        }


        /**
         * @param string $fragment
         *
         * @return $this
         */
        public function setFragment($fragment){
            $this->fragment = $fragment;
            return $this;
        }

    }