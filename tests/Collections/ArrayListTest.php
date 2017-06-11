<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 09.06.2017
     * Time: 16:46
     */

    namespace Locrian\Tests\Collections;

    use PHPUnit_Framework_TestCase;
    use Locrian\Collections\ArrayList;

    class ArrayListTest extends PHPUnit_Framework_TestCase{

        /**
         * @var ArrayList
         */
        private $list;

        protected function setUp(){
            $list = new ArrayList();
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
            $this->list->remove(0);
            self::assertEquals(4, $this->list->size());
            self::assertEquals(-1, $this->list->search(1));
            self::assertEquals(0, $this->list->search(2));
            $this->list->clear();
            self::assertEquals(0, $this->list->size());
        }

        public function testSet(){
            for( $x = 0; $x < $this->list->size(); $x++ ){
                $this->list->set($x, $x + 5);
            }
            $this->list->set($x, $x + 5); // Do nothing
            for( $x = 0; $x < $this->list->size(); $x++ ){
                self::assertEquals($x + 5, $this->list->get($x));
            }
        }

        public function testEach(){
            $arr = [1, 2, 3, 4, 5];
            $this->list->each(function($i, $item) use ($arr){
                self::assertEquals($arr[$i], $item);
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
            $arr = [1, 2, 3, 4, 5];
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

        public function testIteratorPrevious(){
            $arr = [1, 2, 3, 4, 3, 4, 5];
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

        public function testToArray(){
            $arr = [1, 2, 3, 4, 5];
            $arr2 = $this->list->toArray();
            for( $i = 0; $i < count($arr); $i++ ){
                self::assertEquals($arr[$i], $arr2[$i]);
            }
        }

        public function testFilter(){
            $newListArray = [3, 5];
            $newList = $this->list->filter(function($i, $item){
                if( $i == 2 || $item == 5 ){
                    return true;
                }
                else{
                    return false;
                }
            });
            $newList->each(function($i, $item) use ($newListArray){
                self::assertEquals($newListArray[$i], $item);
            });
        }

        public function testToJson(){
            $json = "[1,2,3,4,5]";
            self::assertEquals($json, $this->list->toJson());
        }

    }