<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 11.06.2017
     * Time: 13:10
     */

    namespace Locrian\Tests\Collections;

    use Locrian\Collections\LinkedList;
    use PHPUnit_Framework_TestCase;

    class LinkedListTest extends PHPUnit_Framework_TestCase{

        /**
         * @var LinkedList
         */
        private $list;

        protected function setUp(){
            $list = new LinkedList();
            $list->add(1);
            $list->add(2);
            $list->add(3);
            $list->add(4);
            $list->add(5);
            $this->list = $list;
        }

        public function testBasics(){
            self::assertEquals(1, $this->list->first());
            self::assertEquals(5, $this->list->last());
            self::assertEquals(3, $this->list->get(2));
            self::assertNull($this->list->get(9));
            self::assertTrue($this->list->has(4));
            self::assertTrue($this->list->size() === 5);
            $this->list->remove(1); // Remove by item
            self::assertEquals(4, $this->list->size());
            self::assertEquals(-1, $this->list->search(1));
            self::assertEquals(0, $this->list->search(2));
            $this->list->clear();
            self::assertEquals(0, $this->list->size());
        }

        public function testAdd(){
            $this->list->add(6); // Index 5
            self::assertEquals(5, $this->list->search(6));
            $this->list->addFirst(0); // Index 0
            self::assertEquals(0, $this->list->search(0));
            $this->list->addFirst(8);
            self::assertEquals(0, $this->list->search(8));
        }

        public function testAddBefore(){
            $index = $this->list->search(3); // Index 2
            self::assertEquals(2, $index);
            $this->list->addBefore($index, 99); // 1, 2, 99, 3, 4, 5
            self::assertEquals(2, $this->list->search(99));
            $this->list->addBefore($index, 98); // 1, 2, 98, 99, 3, 4, 5
            self::assertEquals(2, $this->list->search(98));
            $this->list->addBefore($this->list->search(4), 79); // 1, 2, 98, 99, 3, 79, 4, 5
            self::assertEquals(5, $this->list->search(79));
            $this->list->addBefore($this->list->search(99), 17); // 1, 2, 98, 17, 99, 3, 79, 4, 5
            self::assertEquals(3, $this->list->search(17));
            self::assertEquals(9, $this->list->size());
        }

        public function testAddAfter(){
            $index = $this->list->search(3); // Index 2
            self::assertEquals(2, $index);
            $this->list->addAfter($index, 99); // 1, 2, 3, 99, 4, 5
            self::assertEquals(3, $this->list->search(99));
            $this->list->addAfter($index, 98); // 1, 2, 3, 98, 99, 4, 5
            self::assertEquals(3, $this->list->search(98));
            $this->list->addBefore($this->list->search(4), 79); // 1, 2, 3, 98, 99, 79, 4, 5
            self::assertEquals(5, $this->list->search(79));
            $this->list->addAfter($this->list->search(99), 17); // 1, 2, 3, 98, 99, 17, 79, 4, 5
            self::assertEquals(5, $this->list->search(17));
            self::assertEquals(9, $this->list->size());
        }

        public function testGet(){
            self::assertEquals(1, $this->list->get(0));
            self::assertEquals(5, $this->list->get(4));
            self::assertEquals(3, $this->list->get(2));
        }

        public function testSet(){
            $this->list->set(0, 5);
            self::assertEquals(5, $this->list->get(0));
            $data2 = $this->list->get(2) + 5;
            $this->list->set(2, $data2);
            self::assertEquals($data2, $this->list->get(2));
        }

        public function testHas(){
            self::assertTrue($this->list->has(0));
            self::assertTrue($this->list->has(1));
            self::assertTrue($this->list->has(2));
            self::assertTrue($this->list->has(3));
            self::assertTrue($this->list->has(4));
            self::assertFalse($this->list->has(-1));
            self::assertFalse($this->list->has(5));
        }

        public function testToArray(){
            $arr = [1, 2, 3, 4, 5];
            $listArr = $this->list->toArray();
            for( $i = 0; $i < 5; $i++ ){
                self::assertEquals($arr[$i], $listArr[$i]);
            }
            $arr2 = [1, 2, 3, 98, 4, 5];
            $this->list->addAfter(2, 98);
            $listArr2 = $this->list->toArray();
            for( $i = 0; $i < 5; $i++ ){
                self::assertEquals($arr2[$i], $listArr2[$i]);
            }
        }

        public function testToJson(){
            $json = "[1,2,3,4,5]";
            self::assertEquals($json, $this->list->toJson());
        }

        public function testRemove(){
            $this->list->clear();
            $this->list->addAll([1, 2, 1, 3, 7, 9, 5, 6, 3, 2, 1, 3, 5, 8]);
            self::assertEquals(0, $this->list->search(1));
            self::assertEquals(14, $this->list->size());
            $this->list->remove(1); // 2, 1, 3, 7, 9, 5, 6, 3, 2, 1, 3, 5, 8
            self::assertEquals(1, $this->list->search(1));
            self::assertEquals(13, $this->list->size());
            $this->list->removeByIndex(3); // remove 7 --- 2, 1, 3, 9, 5, 6, 3, 2, 1, 3, 5, 8
            self::assertEquals(-1, $this->list->search(7));
            self::assertEquals(9, $this->list->get(3));
            self::assertEquals(12, $this->list->size());
            $this->list->removeMany(3); // Remove all 3's --- 2, 1, 9, 5, 6, 2, 1, 5, 8
            self::assertEquals(9, $this->list->size());
            self::assertEquals(-1, $this->list->search(3));
            self::assertEquals(0, $this->list->search(2));
            $this->list->remove(2);
            self::assertEquals(4, $this->list->search(2));
        }

        public function testEach(){
            $arr = [1, 2, 3, 4, 5];
            $this->list->each(function($i, $item) use($arr){
                self::assertEquals($arr[$i], $item);
            });
        }

        public function testFilter(){
            $arr = [3, 4, 5];
            $newList = $this->list->filter(function($i, $ele){
                return $ele > 2;
            });
            $newList->each(function($i, $ele) use($arr){
                self::assertEquals($arr[$i], $ele);
            });
        }

        public function testIteratorIterate(){
            $arr = [1, 2, 3, 4, 5];
            $it = $this->list->iterator();
            $i = 0;
            while( $it->hasNext() ){
                self::assertEquals($arr[$i], $it->next());
                $i++;
            }
        }

        public function testIteratorRemove(){
            $arr = [ 1, 2, 3, 4, 5 ];
            $it = $this->list->iterator();
            $cnt = 0;
            while( $it->hasNext() ){
                $data = $it->next();
                $it->remove();
                self::assertEquals($arr[$cnt], $data);
                $cnt++;
            }
            self::assertEmpty($this->list->toArray());
        }

        public function testIteratorRemove2(){
            $arr = [ 1, 2, 3, 4, 5 ];
            $arr2 = [ 1, 2, 3 ];
            $it = $this->list->iterator();
            $cnt = 0;
            while( $it->hasNext() ){
                $data = $it->next();
                if( $data > 3 ){
                    $it->remove();
                }
                self::assertEquals($arr[$cnt], $data);
                $cnt++;
            }
            $this->list->each(function($i, $ele) use($arr2){
                self::assertEquals($arr2[$i], $ele);
            });
        }

        public function testIteratorPrevious(){
            $arr = [ 1, 2, 3, 4, 3, 4, 5 ];
            $cnt = 0;
            $it = $this->list->iterator();
            while( $it->hasNext() ){
                if( $it->index() == 3 && $cnt == ($it->index() + 1) ){
                    $data = $it->previous();
                }
                else{
                    $data = $it->next();
                }
                self::assertEquals($arr[$cnt], $data);
                $cnt++;
            }
        }

    }













