<?php
    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 11.06.2017
     * Time: 20:32
     */

    namespace Locrian\tests\Bus;

    use Locrian\Bus\Event;
    use Locrian\Bus\EventBus;
    use Locrian\Bus\Subscriber;
    use Locrian\Collections\Queue;
    use PHPUnit_Framework_TestCase;

    class GlobalChannelTest extends PHPUnit_Framework_TestCase implements Subscriber{

        /**
         * @var EventBus
         */
        private $bus;

        const EVENT_TYPE = "test_event_type";
        const EVENT_TYPE2 = "test_event_type2";

        /**
         * @var Queue
         */
        private $queue;

        protected function setUp(){
            $this->queue = new Queue();
            $this->bus = new EventBus(false);
            $this->bus->subscribeGlobalChannel($this);
        }

        public function testGlobalChannel(){
            $e1 = new Event(self::EVENT_TYPE, "e1");
            $e2 = new Event(self::EVENT_TYPE2, "e2");
            $e3 = new Event(self::EVENT_TYPE2, "e3");
            $e4 = new Event(self::EVENT_TYPE, "e4");
            $this->queue->push($e1);
            $this->queue->push($e2);
            $this->queue->push($e3);
            $this->queue->push($e4);
            $this->bus->publish($e1);
            $this->bus->publish($e2);
            $this->bus->publish($e3);
            $this->bus->publish($e4);
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