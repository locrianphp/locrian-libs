<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 11.06.2017
     * Time: 14:49
     */

    namespace Locrian\Tests\Collections;

    use Locrian\Collections\Stack;
    use PHPUnit_Framework_TestCase;

    class StackTest extends PHPUnit_Framework_TestCase{

        /**
         * @var Stack
         */
        private $stack;

        protected function setUp(){
            $s = new Stack();
            $s->push(1);
            $s->push(2);
            $s->push(3);
            $s->push(4);
            $s->push(5);
            $this->stack = $s;
        }

        public function testBasics(){
            self::assertEquals(5, $this->stack->top());
            self::assertTrue($this->stack->size() === 5);
            $this->stack->pop();
            self::assertEquals(4, $this->stack->size());
            self::assertEquals(3, $this->stack->search(1));
            self::assertEquals(0, $this->stack->search(4));
            $this->stack->clear();
            self::assertEquals(0, $this->stack->size());
        }

        public function testPushPop(){
            $arr = [5, 4, 3, 2, 1];
            for( $i = 0; $i < count($arr); $i++ ){
                $item = $this->stack->pop();
                self::assertEquals($arr[$i], $item);
            }
            $this->stack->push(4);
            $this->stack->push(2);
            self::assertEquals(2, $this->stack->size());
            self::assertEquals(2, $this->stack->pop());
            self::assertEquals(4, $this->stack->pop());
            self::assertEquals(0, $this->stack->size());
        }

        public function testIteratorIterate(){
            $arr = [5, 4, 3, 2, 1];
            $it = $this->stack->iterator();
            $i = 0;
            while( $it->hasNext() ){
                self::assertEquals($arr[$i], $it->next());
                $i++;
            }
        }

        public function testEach(){
            $arr = [5, 4, 3, 2, 1];
            $this->stack->each(function($i, $item) use ($arr){
                self::assertEquals($arr[$i], $item);
            });
        }

        public function testFilter(){
            $arr = [5, 4, 3];
            $newList = $this->stack->filter(function($i, $ele){
                return $ele > 2;
            });
            $newList->each(function($i, $ele) use ($arr){
                self::assertEquals($arr[$i], $ele);
            });
        }

    }