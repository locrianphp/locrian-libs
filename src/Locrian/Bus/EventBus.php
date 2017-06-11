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
    use Locrian\InvalidArgumentException;

    class EventBus{

        /**
         * @var SubscriberManager
         * Subscriber manager which manages subscribers by event types that thew subscribed
         */
        private $subscriberManager;


        /**
         * @var Publisher
         * Publishes and caches events
         */
        private $publisher;


        /**
         * EventBus constructor.
         *
         * @param bool $cacheMode defines whether cache mode is on
         *
         * @throws InvalidArgumentException
         */
        public function __construct($cacheMode = false){
            if( is_bool($cacheMode) ){
                $this->subscriberManager = new SubscriberManager();
                $this->publisher = new Publisher($cacheMode);
            }
            else{
                throw new InvalidArgumentException("Cache mode must be a boolean");
            }
        }


        /**
         * @param $eventType string
         * @param Subscriber $subscriber
         * This method allows a subscriber to subscribe a specific type of event channel
         *
         * @throws InvalidArgumentException
         */
        public function subscribe($eventType, Subscriber $subscriber){
            if( is_string($eventType) ){
                $this->subscriberManager->addSubscriber($eventType, $subscriber);
            }
            else{
                throw new InvalidArgumentException("Event type must be a string");
            }
        }


        /**
         * @param Subscriber $subscriber
         * Global subscribers receive all types of events that are published
         */
        public function subscribeGlobalChannel(Subscriber $subscriber){
            $this->subscriberManager->addGlobalSubscriber($subscriber);
        }


        /**
         * @param Event $event
         * Publishes new event to a channel so that anyone who subscribed that channel will receive it
         */
        public function publish(Event $event){
            $subscribers = $this->subscriberManager->getSubscribers($event->getEventType());
            $this->publisher->publishAndCache($subscribers, $event);
            $this->publisher->publish($this->subscriberManager->getGlobalSubscribers(), $event);
        }


        /**
         * @param $eventType
         * @param Subscriber $subscriber
         * Publishes all the cached events with the given type to the given subscriber
         */
        public function sendCache($eventType, Subscriber $subscriber){
            $this->publisher->publishCache($eventType, new ArrayList([$subscriber]));
        }


        /**
         * @return ArrayList
         * Gives all the channels which are subscribed at least one subscriber
         */
        public function getEventChannels(){
            return $this->subscriberManager->getEventChannels();
        }

    }













