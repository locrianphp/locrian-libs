<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 24.06.2017
     * Time: 02:14
     */

    namespace Locrian\Tests\Util;

    use Locrian\Collections\HashMap;
    use Locrian\Util\SimpleTemplate;
    use PHPUnit_Framework_TestCase;

    class SimpleTemplateTest extends PHPUnit_Framework_TestCase{

        public function testReplace(){
            $content = "Why do people {{word}} each other ?";
            $result = "Why do people hate each other ?";
            $map = new HashMap(["word" => "hate", "word2" => "val2"]);
            $arr = ["word" => "hate", "word2" => "val2"];
            $arr2 = ["word2" => "val2"];
            self::assertEquals($result, SimpleTemplate::replace($content, $map));
            self::assertEquals($result, SimpleTemplate::replace($content, $arr));
            self::assertEquals($content, SimpleTemplate::replace($content, $arr2));
        }

    }