<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 19.06.2017
     * Time: 23:46
     */

    namespace Locrian\Tests\DI;

    use Locrian\DI\Container;
    use Locrian\DI\ServiceProvider;
    use PHPUnit_Framework_TestCase;

    class ContainerTest extends PHPUnit_Framework_TestCase{

        public function testSingleton(){
            $c = new Container();
            $c->singleton("a", function(){
                return new A();
            });
            $a1 = $c->get("a");
            $a2 = $c->get("a");
            self::assertEquals($a1->getId(), $a2->getId());
        }

        public function testFactory(){
            $c = new Container();
            $c->factory("a", function(){
                return new A();
            });
            $a1 = $c->get("a");
            $a2 = $c->get("a");
            self::assertNotEquals($a1->getId(), $a2->getId());
        }

        public function testPut(){
            $c = new Container();
            $c->put("a", new A());
            $c->put("int", 5);
            $a1 = $c->get("a");
            $a2 = $c->get("a");
            self::assertEquals($a1->getId(), $a2->getId());
            self::assertEquals(5, $c->get("int"));
        }

        public function testGetBeanNames(){
            $c = new Container();
            $c->put("a", new A());
            $c->put("int", 5);
            self::assertEquals(["a", "int"], $c->getBeanNames());
        }

        public function testHas(){
            $c = new Container();
            $c->put("a", new A());
            self::assertTrue($c->has("a"));
        }

        public function testRemove(){
            $c = new Container();
            $c->put("a", new A());
            self::assertTrue($c->has("a"));
            $c->remove("a");
            self::assertFalse($c->has("a"));
        }

        public function testServiceProvider(){
            $c = new Container();
            $c->register(new CustomServiceProvider());
            self::assertTrue($c->has("a"));
        }

    }


    class A{
        private $id;
        public function __construct(){
            $this->id = uniqid();
        }
        public function getId(){
            return $this->id;
        }
    }


    class CustomServiceProvider implements ServiceProvider{
        public function register(Container $container){
            $container->put("a", new A());
        }
    }