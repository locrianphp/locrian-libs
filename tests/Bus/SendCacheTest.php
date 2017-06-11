<?php
    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 11.06.2017
     * Time: 20:16
     */

    namespace Locrian\tests\Bus;

    use Locrian\Bus\Event;
    use Locrian\Bus\EventBus;
    use Locrian\Bus\Subscriber;
    use Locrian\Collections\Queue;

    class SendCacheTest extends \PHPUnit_Framework_TestCase implements Subscriber{

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
            $this->bus = new EventBus(true);
            $e1 = new Event(self::EVENT_TYPE, "e1");
            $e2 = new Event(self::EVENT_TYPE2, "e2");
            $e3 = new Event(self::EVENT_TYPE2, "e3");
            $e4 = new Event(self::EVENT_TYPE, "e4");
            $this->queue->push($e1);
            $this->queue->push($e4);
            $this->bus->publish($e1);
            $this->bus->publish($e2);
            $this->bus->publish($e3);
            $this->bus->publish($e4);
        }

        public function testCache(){
            $this->bus->subscribe(self::EVENT_TYPE, $this);
            $e5 = new Event(self::EVENT_TYPE, "e5");
            $this->queue->push($e5);
            $this->bus->sendCache(self::EVENT_TYPE, $this);
            $this->bus->publish($e5);
        }

        /**
         * @param Event $event
         * Subscribers will receive Events which are subscribed before
         */
        public function receive(Event $event){
            self::assertEquals($this->queue->pop()->getEvent(), $event->getEvent());
        }

    }