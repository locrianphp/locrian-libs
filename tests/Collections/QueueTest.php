<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 11.06.2017
     * Time: 14:28
     */

    namespace Locrian\Tests\Collections;

    use Locrian\Collections\Queue;
    use PHPUnit_Framework_TestCase;

    class QueueTest extends PHPUnit_Framework_TestCase{

        /**
         * @var Queue
         */
        private $queue;

        protected function setUp(){
            $q = new Queue();
            $q->push(1);
            $q->push(2);
            $q->push(3);
            $q->push(4);
            $q->push(5);
            $this->queue = $q;
        }

        public function testBasics(){
            self::assertEquals(1, $this->queue->top());
            self::assertTrue($this->queue->size() === 5);
            $this->queue->pop();
            self::assertEquals(4, $this->queue->size());
            self::assertEquals(-1, $this->queue->search(1));
            self::assertEquals(0, $this->queue->search(2));
            $this->queue->clear();
            self::assertEquals(0, $this->queue->size());
        }

        public function testPushPop(){
            $arr = [1, 2, 3, 4, 5];
            for( $i = 0; $i < count($arr); $i++ ){
                if( $arr[$i] == 2 || $arr[$i] == 4 ){
                    $this->queue->push($arr[$i]);
                }
                $item = $this->queue->pop();
                self::assertEquals($arr[$i], $item);
            }
            self::assertEquals(2, $this->queue->size());
            self::assertEquals(2, $this->queue->pop());
            self::assertEquals(4, $this->queue->pop());
            self::assertEquals(0, $this->queue->size());
        }

        public function testIteratorIterate(){
            $arr = [1, 2, 3, 4, 5];
            $it = $this->queue->iterator();
            $i = 0;
            while( $it->hasNext() ){
                self::assertEquals($arr[$i], $it->next());
                $i++;
            }
        }

        public function testEach(){
            $arr = [1, 2, 3, 4, 5];
            $this->queue->each(function($i, $item) use ($arr){
                self::assertEquals($arr[$i], $item);
            });
        }

        public function testFilter(){
            $arr = [3, 4, 5];
            $newList = $this->queue->filter(function($i, $ele){
                return $ele > 2;
            });
            $newList->each(function($i, $ele) use ($arr){
                self::assertEquals($arr[$i], $ele);
            });
        }

    }