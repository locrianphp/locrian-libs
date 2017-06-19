<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 20.06.2017
     * Time: 00:38
     */

    namespace Locrian\Tests\DI;

    use Locrian\DI\Container;
    use Locrian\DI\Injector;
    use PHPUnit_Framework_TestCase;

    class InjectorTest extends PHPUnit_Framework_TestCase{

        public function testInjector(){
            $c = new Container();
            $c->put("a", 1);
            $c->put("b", 2);
            $c->put("c", 3);
            $c->put("d", 4);
            $i = new Injector($c);
            $v = new Victim();
            $i->inject($v);
            self::assertEquals(1, $v->a);
            self::assertEquals(2, $v->getB());
            self::assertEquals(3, $v->c);
            self::assertNull($v->e);
            self::assertEquals("not present", $v->d);
        }

    }


    class Victim{
        public $a;
        private $b;
        public $c;
        public $e;
        public function getB(){
            return $this->b;
        }
        public function setA($a){
            $this->a = $a;
        }
        public function setB($b){
            $this->b = $b;
        }
        function __get($name){
            return "not present";
        }
    }