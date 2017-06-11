<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 09.06.2017
     * Time: 22:14
     */

    namespace Locrian\Tests\Collections;

    use Locrian\Collections\HashMap;

    class HashMapTest extends \PHPUnit_Framework_TestCase{

        /**
         * @var HashMap
         */
        private $map;

        protected function setUp(){
            $map = new HashMap();
            $map->add("a", 1);
            $map->add("b", 2);
            $map->add("c", 3);
            $map->add("d", 4);
            $map->add("e", 5);
            $this->map = $map;
        }

        public function testBasics(){
            self::assertEquals(1, $this->map->first());
            self::assertEquals(5, $this->map->last());
            self::assertEquals(3, $this->map->get("c"));
            self::assertNull($this->map->get("s"));
            self::assertTrue($this->map->has("d"));
            self::assertTrue($this->map->size() === 5);
            $this->map->remove("a");
            self::assertEquals(4, $this->map->size());
            self::assertNull($this->map->search(99));
            self::assertEquals("b", $this->map->search(2));
            $this->map->clear();
            self::assertEquals(0, $this->map->size());
        }

        public function testSet(){
            $keys = $this->map->getKeys();
            for( $x = 0; $x < $this->map->size(); $x++ ){
                $this->map->set($keys[$x], $x + 5);
            }
            $this->map->set("t", $x + 5); // Add new item
            $keys[] = "t";
            for( $x = 0; $x < $this->map->size(); $x++ ){
                self::assertEquals($x + 5, $this->map->get($keys[$x]));
            }
        }

        public function testEach(){
            $arr = ["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5];
            $this->map->each(function($i, $item) use($arr){
                self::assertEquals($arr[$i], $item);
            });
        }

        public function testIteratorIterate(){
            $arr = [1, 2, 3, 4, 5];
            $it = $this->map->iterator();
            $i = 0;
            while( $it->hasNext() ){
                self::assertEquals($arr[$i], $it->next());
                $i++;
            }
        }

        public function testIteratorRemove(){
            $arr = [ 1, 2, 3, 4, 5 ];
            $it = $this->map->iterator();
            $cnt = 0;
            while( $it->hasNext() ){
                $data = $it->next();
                $it->remove();
                self::assertEquals($arr[$cnt], $data);
                $cnt++;
            }
            self::assertEmpty($this->map->toArray());
        }

        public function testIteratorPrevious(){
            $arr = [ 1, 2, 3, 4, 3, 4, 5 ];
            $cnt = 0;
            $it = $this->map->iterator();
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
            $arr = ["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5];
            $arr2 = $this->map->toArray();
            foreach( $arr as $k => $v ){
                self::assertEquals($v, $arr2[$k]);
            }
        }

        public function testFilter(){
            $newListArray = ["c" => 3, "e" => 5];
            $newList = $this->map->filter(function($k, $item){
                if( $k == "c" || $item == 5 ){
                    return true;
                }
                else{
                    return false;
                }
            });
            $newList->each(function($k, $item) use($newListArray){
                self::assertEquals($newListArray[$k], $item);
            });
        }

        public function testToJson(){
            $json = '{"a":1,"b":2,"c":3,"d":4,"e":5}';
            self::assertEquals($json, $this->map->toJson());
        }

    }