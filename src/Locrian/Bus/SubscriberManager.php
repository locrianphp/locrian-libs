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

    class SubscriberManager{

        /**
         * @var ArrayList
         * Subscribers which listens the global channel
         */
        private $globalSubscribers;


        /**
         * @var HashMap
         * Subscriber channel mapping
         */
        private $subscriberEventTypeMapping;


        /**
         * SubscriberStore constructor.
         */
        public function __construct(){
            $this->globalSubscribers = new ArrayList();
            $this->subscriberEventTypeMapping = new HashMap();
        }


        /**
         * @param $eventType string
         * @param Subscriber $subscriber
         * Adds new subscriber to a channel
         */
        public function addSubscriber($eventType, Subscriber $subscriber){
            if( $this->subscriberEventTypeMapping->has($eventType) ){
                $subscribers = $this->subscriberEventTypeMapping->get($eventType);
                $subscribers->add($subscriber);
            }
            else{
                $subscribers = new ArrayList();
                $subscribers->add($subscriber);
                $this->subscriberEventTypeMapping->add($eventType, $subscribers);
            }
        }


        /**
         * @param $eventType string
         * @return ArrayList | null
         */
        public function getSubscribers($eventType){
            if( $this->subscriberEventTypeMapping->has($eventType) ){
                return $this->subscriberEventTypeMapping->get($eventType);
            }
            else{
                return null;
            }
        }


        /**
         * @param Subscriber $subscriber
         * Adds new global subscriber
         */
        public function addGlobalSubscriber(Subscriber $subscriber){
            $this->globalSubscribers->add($subscriber);
        }


        /**
         * @return ArrayList
         * Returns global subscribers
         */
        public function getGlobalSubscribers(){
            return $this->globalSubscribers;
        }


        /**
         * @return ArrayList
         * Returns all the channels which are subscribed
         * Only subscribed channels can be listed
         */
        public function getEventChannels(){
            return new ArrayList($this->subscriberEventTypeMapping->getKeys());
        }

    }