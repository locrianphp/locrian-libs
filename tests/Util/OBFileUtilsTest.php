<?php
    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 12.06.2017
     * Time: 00:44
     */

    namespace Locrian\tests\Util;

    use Locrian\IO\File;
    use Locrian\Util\FileUtils;
    use Locrian\Util\OBUtils;
    use PHPUnit_Framework_TestCase;

    class OBFileUtilsTest extends PHPUnit_Framework_TestCase{

        public function testFunctionBuffer(){
            self::assertEquals("Hello world!", OBUtils::callbackBuffer(function(){
                echo "Hello world!";
            }));
        }

        public function testReadText(){
            $content = file_get_contents("LICENSE");
            self::assertEquals($content, OBUtils::fileBuffer("LICENSE"));
            self::assertEquals($content, FileUtils::readText(new File("LICENSE")));
        }

    }