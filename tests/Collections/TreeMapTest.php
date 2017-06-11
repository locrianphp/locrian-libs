<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 11.06.2017
     * Time: 16:52
     */

    namespace Locrian\Tests\Collections;

    use Locrian\Collections\TreeMap;
    use PHPUnit_Framework_TestCase;

    class TreeMapTest extends PHPUnit_Framework_TestCase{

        /**
         * @var TreeMap
         */
        private $root;

        protected function setUp(){
            $r = new TreeMap("r", 0);

            $c1 = new TreeMap("c1", 1);
            $c2 = new TreeMap("c2", 2);
            $c3 = new TreeMap("c3", 3);

            $c11 = new TreeMap("c11", 11);
            $c12 = new TreeMap("c12", 12);

            $c21 = new TreeMap("c21", 21);
            $c22 = new TreeMap("c22", 22);

            $c111 = new TreeMap("c111", 111);
            $c112 = new TreeMap("c112", 112);

            $c221 = new TreeMap("c221", 221);

            $r->add($c1);
            $r->add($c2);
            $r->add($c3);

            $c1->add($c11);
            $c1->add($c12);

            $c2->add($c21);
            $c2->add($c22);

            $c11->add($c111);
            $c11->add($c112);

            $c22->add($c221);

            $this->root = $r;
        }

        public function testGet(){
            self::assertEquals(1, $this->root->get("c1")->getValue());
            self::assertEquals(2, $this->root->get("c2")->getValue());
            self::assertEquals(3, $this->root->get("c3")->getValue());

            $c1 = $this->root->get("c1");
            self::assertEquals(11, $c1->get("c11")->getValue());
            self::assertEquals(12, $c1->get("c12")->getValue());

            $c11 = $c1->get("c11");
            self::assertEquals(111, $c11->get("c111")->getValue());
            self::assertEquals(112, $c11->get("c112")->getValue());

            $c2 = $this->root->get("c2");
            self::assertEquals(21, $c2->get("c21")->getValue());
            self::assertEquals(22, $c2->get("c22")->getValue());

            self::assertNull($c2->get("c32"));

            $c22 = $c2->get("c22");
            self::assertEquals(221, $c22->get("c221")->getValue());
        }

        public function testRemove(){
            $this->root->remove("c2");
            self::assertNull($this->root->get("c2"));
        }

        public function testGetParent(){
            $c2 = $this->root->get("c2");
            self::assertEquals($this->root->getKey(), $c2->getParent()->getKey());
        }

        public function testSearch(){
            $r1 = $this->root->search(2);
            self::assertEquals("c2", $r1->getKey());

            $r2 = $this->root->search(12);
            self::assertEquals("c12", $r2->getKey());

            $r3 = $this->root->search(111);
            self::assertEquals("c111", $r3->getKey());

            $r4 = $this->root->search(221);
            self::assertEquals("c221", $r4->getKey());
            self::assertEquals("c22", $r4->getParent()->getKey());

            $r5 = $this->root->get("c2")->search(221);
            self::assertEquals("c221", $r5->getKey());
        }

        public function testSize(){
            self::assertEquals(11, $this->root->size());
            $r3 = $this->root->search(111);
            self::assertEquals(1, $r3->size());
            $this->root->clear();
            self::assertEquals(1, $this->root->size());
        }

        public function testEach(){
            $arr = ["r" => 0, "c1" => 1, "c11" => 11, "c111" => 111,
                "c112" => 112, "c12" => 12, "c2" => 2, "c21" => 21, "c22" => 22, "c221" => 221, "c3" => 3];
            $this->root->each(function($key, TreeMap $node) use ($arr){
                self::assertEquals($arr[$key], $node->getValue());
            });
        }

        public function testToArray(){
            $arr = [
                "c1" => [
                    "c11" => [
                        "c111" => 111,
                        "c112" => 112
                    ],
                    "c12" => 12
                ],
                "c2" => [
                    "c21" => 21,
                    "c22" => [
                        "c221" => 221
                    ]
                ],
                "c3" => 3
            ];
            $arr2 = $this->root->toArray();
            self::assertEquals($arr, $arr2);
        }

        public function testToJson(){
            $json = '{"c1":{"c11":{"c111":111,"c112":112},"c12":12},"c2":{"c21":21,"c22":{"c221":221}},"c3":3}';
            self::assertEquals($json, $this->root->toJson());
        }

    }















