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

    namespace Locrian\DI;

    use Closure;
    use Locrian\Collections\HashMap;
    use Locrian\IndexOutOfBoundsException;
    use Locrian\InvalidArgumentException;
    use Locrian\RuntimeException;


    class Container{

        /**
         * @var HashMap callable items
         */
        private $beans;


        /**
         * Container constructor.
         */
        public function __construct(){
            $this->beans = new HashMap();
        }


        /**
         * @param $callable Closure function
         * @param $name string | integer name of the service
         *
         * @throws InvalidArgumentException
         * @throws \Locrian\RuntimeException
         *
         * Singleton method is to bind the same object to all targets
         * See the following example
         *
         * $container = new Container();
         * $container->singleton("Mail", function(){
         *      return new Mailer();
         * });
         */
        public function singleton($name, Closure $callable){
            if( is_callable($callable) ){
                if( !$this->beans->has($name) ){
                    $callable = $callable();
                    $this->beans->add($name, function() use ($callable){
                        return $callable;
                    });
                }
                else{
                    throw new RuntimeException("Bean name must be unique");
                }
            }
            else{
                throw new InvalidArgumentException("Callable must be a function!");
            }
        }


        /**
         * @param $callable Closure function
         * @param $name string | integer name of the service
         *
         * @throws InvalidArgumentException
         * @throws \Locrian\RuntimeException
         *
         * For every target a new object is created
         * See the following example
         *
         * $cont = new Container()
         * $cont->factory("Database", function(){
         *      return new Database();
         * });
         */
        public function factory($name, Closure $callable){
            if( is_callable($callable) ){
                if( !$this->beans->has($name) ){
                    $this->beans->add($name, $callable);
                }
                else{
                    throw new RuntimeException("Bean name must be unique");
                }
            }
            else{
                throw new InvalidArgumentException("Callable must be a function");
            }
        }


        /**
         * @param $name string
         * @param $target mixed
         * @throws \Locrian\RuntimeException
         *
         * Puts any object or primitive type int the container. Callback is not required.
         */
        public function put($name, $target){
            if( !$this->beans->has($name) ){
                $this->beans->add($name, function() use($target){
                    return $target;
                });
            }
            else{
                throw new RuntimeException("Service name must be unique");
            }
        }


        /**
         * @param $name string | integer name of the service
         *
         * @return mixed
         * @throws IndexOutOfBoundsException
         *
         * Returns the bean which named as $name
         */
        public function get($name){
            if( $this->beans->has($name) ){
                $ret = $this->beans->get($name);
                return $ret();
            }
            else{
                throw new IndexOutOfBoundsException(sprintf("There is no bean as '%s'", $name));
            }
        }


        /**
         * @param $name string
         *
         * Remove item from container
         */
        public function remove($name){
            if( $this->beans->has($name) ){
                $this->beans->remove($name);
            }
        }


        /**
         * @param ServiceProvider $provider
         *
         * You can create your own custom service providers and attach their services
         * by using this method.
         *
         * Service provider must implement the service provider interface which has just one
         * register method which takes a container object (this object). Register method must
         * use the multiple and singleton methods of the container object when adding new services
         * to the container. See the following example.
         *
         * class CustomServiceProvider implements ServiceProvider{
         *
         *      public function register(Container $container){
         *
         *          $container->singleton("NameOfService", function(){
         *              return new NameOfTheObject();
         *          });
         *
         *          $container->factory("NameOfOtherService", function(){
         *              return new NameOfOtherObject();
         *          });
         *
         *      }
         *
         * }
         */
        public function provide(ServiceProvider $provider){
            $provider->register($this);
        }

    }