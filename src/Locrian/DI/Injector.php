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

    use Locrian\Util\StringUtils;

    class Injector{

        /**
         * @var \Locrian\DI\Container
         */
        private $container;


        /**
         * Injector constructor.
         *
         * @param \Locrian\DI\Container $container
         */
        public function __construct(Container $container){
            $this->container = $container;
        }


        /**
         * @param object $victim
         * Injects beans in the container to the appropriate setter methods or fields of the victim
         */
        public function inject($victim){
            $setters = $this->resolveSetters($victim, $this->container->getBeanNames());
            $fields = $this->resolveFields($victim, $this->container->getBeanNames());
            $result = $this->combineResults($fields, $setters);
            foreach( $result as $beanName => $injectionTarget ){
                if( $injectionTarget->getType() == InjectionTarget::METHOD ){
                    $m = $injectionTarget->getName();
                    $victim->$m($this->container->get($beanName));
                }
                else{
                    $f = $injectionTarget->getName();
                    $victim->$f = $this->container->get($beanName);
                }
            }
        }


        /**
         * @param array $fields
         * @param array $setters
         * @return array
         * Combines field and setter resolver's results.
         * Setter methods are prior
         */
        private function combineResults(Array $fields, Array $setters){
            $result = $setters;
            foreach( $fields as $field => $injectionTarget ){
                if( !isset($result[$field]) ){
                    $result[$field] = $injectionTarget;
                }
            }
            return $result;
        }


        /**
         * @param object $target
         * @param array $keys
         * @return array
         * Resolves public setter methods that match with a bean name
         */
        private function resolveSetters($target, $keys){
            $methods = get_class_methods($target);
            $valuableMethods = [];
            foreach( $methods as $method ){
                if( StringUtils::startsWith("set", $method) && strlen($method) > 3 ){
                    $beanName = StringUtils::remove("set", $method);
                    $chr = strtolower($beanName[0]);
                    $beanName[0] = $chr;
                    if( in_array($beanName, $keys) ){
                        $valuableMethods[$beanName] = new InjectionTarget(InjectionTarget::METHOD, $method);
                    }
                }
            }
            return $valuableMethods;
        }


        /**
         * @param object $target
         * @param array $keys
         * @return array
         * Resolves public fields that match with a bean name
         */
        private function resolveFields($target, $keys){
            $fields = get_object_vars($target);
            $valuableFields = [];
            foreach( $fields as $field => $value ){
                if( in_array($field, $keys) ){
                    $valuableFields[$field] = new InjectionTarget(InjectionTarget::FIELD, $field);
                }
            }
            return $valuableFields;
        }

    }