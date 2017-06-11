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

    namespace Locrian\Bus;

    use Locrian\Collections\ArrayList;
    use Locrian\Collections\HashMap;
    use Locrian\Collections\Queue;

    class Publisher{

        /**
         * @var HashMap
         */
        private $cache;


        /**
         * @var bool
         */
        private $cacheEvents;


        /**
         * Publisher constructor.
         *
         * @param $useCache boolean
         * All events will be cached if useCache when true
         */
        public function __construct($useCache){
            $this->cache = new HashMap();
            $this->cacheEvents = ($useCache === true ? true : false);
        }


        /**
         * @param ArrayList $subscribers
         * @param Event $event
         * Publishes new event
         */
        public function publish($subscribers, $event){
            if( $subscribers != null ){
                $iterator = $subscribers->iterator();
                while( $iterator->hasNext() ){
                    $subscriber = $iterator->next();
                    $subscriber->receive($event);
                }
            }
        }


        /**
         * @param ArrayList $subscribers
         * @param Event $event
         * Publishes and caches event
         */
        public function publishAndCache($subscribers, $event){
            $this->publish($subscribers, $event);
            // Cache anyway
            if( $this->cacheEvents ){
                $this->cacheEvent($event);
            }
        }


        /**
         * @param $eventType string
         * @param ArrayList $subscribers
         * Publishes all the cached events with a specific event type to the subscribers
         */
        public function publishCache($eventType, $subscribers){
            if( $this->cache->has($eventType) ){
                $this->cache->get($eventType)->each(function($i, $event) use($subscribers){
                    $this->publish($subscribers, $event);
                });
            }
        }


        /**
         * @param Event $event
         * Adds an event to the cache
         */
        private function cacheEvent(Event $event){
            if( $this->cache->has($event->getEventType()) ){
                $this->cache->get($event->getEventType())->push($event);
            }
            else{
                $events = new Queue();
                $events->push($event);
                $this->cache->add($event->getEventType(), $events);
            }
        }

    }