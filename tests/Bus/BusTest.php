<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 11.06.2017
     * Time: 20:09
     */

    namespace Locrian\Tests\Bus;

    use Locrian\Bus\Event;
    use Locrian\Bus\EventBus;
    use Locrian\Bus\Subscriber;
    use Locrian\Collections\Queue;
    use PHPUnit_Framework_TestCase;

    class BusTest extends PHPUnit_Framework_TestCase implements Subscriber{

        /**
         * @var EventBus
         */
        private $bus;

        /**
         * @var Queue
         */
        private $queue;

        const EVENT_TYPE = "test_event_type";
        const EVENT_TYPE2 = "test_event_type2";

        protected function setUp(){
            $this->queue = new Queue();
            $this->bus = new EventBus(false);
            $this->bus->subscribe(self::EVENT_TYPE, $this);
            $this->bus->subscribe(self::EVENT_TYPE2, $this);
        }

        public function testEvent(){
            $e1 = new Event(self::EVENT_TYPE, "New Event1");
            $e2 = new Event(self::EVENT_TYPE2, "New Event2");
            $this->queue->push($e1);
            $this->queue->push($e2);
            $this->bus->publish($e1);
            $this->bus->publish($e2);
        }

        public function testEventChannels(){
            $ch = $this->bus->getEventChannels();
            self::assertEquals(self::EVENT_TYPE, $ch->get(0));
            self::assertEquals(self::EVENT_TYPE2, $ch->get(1));
        }

        /**
         * @param Event $event
         * Subscribers will receive Events which are subscribed before
         */
        public function receive(Event $event){
            $e = $this->queue->pop();
            self::assertEquals($e->getEventType(), $event->getEventType());
            self::assertEquals($e->getEvent(), $event->getEvent());
        }

    }