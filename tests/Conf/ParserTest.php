<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 16.06.2017
     * Time: 13:42
     */

    namespace Locrian\Tests\Conf;

    use Locrian\Collections\ArrayList;
    use Locrian\Collections\Queue;
    use Locrian\Conf\ConfParser;
    use Locrian\Conf\ConfTree;
    use Locrian\Conf\Tokenizer\DefaultConfTokenizer;
    use Locrian\IO\File;
    use Locrian\Util\FileUtils;
    use PHPUnit_Framework_TestCase;

    class ParserTest extends PHPUnit_Framework_TestCase{

        /**
         * @var ConfParser
         */
        private $parser;

        protected function setUp(){
            $this->parser = new ConfParser(new DefaultConfTokenizer());
        }

        public function testParser1(){
            $root = $this->parser->parseTree(FileUtils::readText(new File("tests/Conf/files/tk.conf")));
            $app = $root->get("Application");
            self::assertNotNull($app);
            self::assertEquals("Application", $app->getKey());
            self::assertEquals(ConfTree::NAMESPACE_NODE, $app->getType());
            $k = $app->get("key");
            self::assertNotNull($k);
            self::assertEquals("key", $k->getKey());
            self::assertEquals(ConfTree::OBJECT_NODE, $k->getType());
            self::assertEquals("value", $k->getValue());
        }

        public function testParser2(){
            $root = $this->parser->parseTree(FileUtils::readText(new File("tests/Conf/files/tk2.conf")));
            $arr = [
                $this->createNode(ConfTree::NAMESPACE_NODE, null, null), $this->createNode(ConfTree::NAMESPACE_NODE, "Application", null),
                $this->createNode(ConfTree::OBJECT_NODE, "type", "development"), $this->createNode(ConfTree::OBJECT_NODE, "lang", "en-us"),
                $this->createNode(ConfTree::OBJECT_NODE, "varUsage", "\${Application.type} or \${type} the rest"),
                $this->createNode(ConfTree::NAMESPACE_NODE, "Http", null), $this->createNode(ConfTree::NAMESPACE_NODE, "Cookie", null),
                $this->createNode(ConfTree::OBJECT_NODE, "crypt", true), $this->createNode(ConfTree::ARRAY_NODE, "carr", new ArrayList([1, 2, 3])),
                $this->createNode(ConfTree::OBJECT_NODE, "cls", "Locrian\Conf\Conf::class"), $this->createNode(ConfTree::NAMESPACE_NODE, "Array", null),
                $this->createNode(ConfTree::ARRAY_NODE, "Arr", new ArrayList(["value1", "value2"])), $this->createNode(ConfTree::ARRAY_NODE, "arr2", new ArrayList(["val1"])),
            ];
            $queue = new Queue($arr);
            $root->each(function($key, ConfTree $node) use($queue){
                 $n = $queue->pop();
                 self::assertEquals($n->getType(), $node->getType());
                 self::assertEquals($n->getKey(), $node->getKey());
                 self::assertEquals($n->getValue(), $node->getValue());
            });
        }

        private function createNode($type, $key, $value){
            $n = new ConfTree($type);
            $n->setKey($key);
            $n->setValue($value);
            return $n;
        }

    }