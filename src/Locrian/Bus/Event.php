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

    class Event{

        /**
         * @var string
         * Event type which will be used to deliver event to the correct subscribers
         */
        private $eventType;


        /**
         * @var mixed
         * Event body
         */
        private $event;


        /**
         * Event constructor.
         *
         * @param $eventType string
         * @param $event mixed
         */
        public function __construct($eventType, $event){
            $this->eventType = $eventType;
            $this->event = $event;
        }


        /**
         * @return mixed
         */
        public function getEventType(){
            return $this->eventType;
        }


        /**
         * @return mixed
         */
        public function getEvent(){
            return $this->event;
        }

    }