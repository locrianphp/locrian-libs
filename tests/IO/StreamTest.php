<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 19.06.2017
     * Time: 00:27
     */

    namespace Locrian\Tests\IO;

    use Locrian\IO\BufferedInputStream;
    use Locrian\IO\BufferedOutputStream;
    use Locrian\IO\File;
    use PHPUnit_Framework_TestCase;

    class StreamTest extends PHPUnit_Framework_TestCase{

        protected function setUp(){
            touch("tests/IO/stream.file.txt");
        }

        public function testBufferedOutputStream(){
            $path = "tests/IO/stream.file.txt";
            $content = "Lorem ipsum dolor sit amet.";
            $stream = new BufferedOutputStream(new File($path), 256);
            self::assertEquals(256, $stream->getBufferSize());
            $stream->write($content);
            $stream->flush();
            $stream->close();
            self::assertEquals($content, file_get_contents($path));
        }

        public function testBufferedInputStream(){
            $path = "tests/IO/stream.file.txt";
            $content = "Lorem ipsum dolor sit amet.";
            file_put_contents($path, $content);
            self::assertEquals($content, file_get_contents($path));
            $stream = new BufferedInputStream(new File($path), 5);
            $data = "";
            while( ($chunk = $stream->read()) !== null ){
                $data .= $chunk;
            }
            self::assertEquals($content, $data);
            $stream->rewind(); // Put file pointer to the beginning
            self::assertEquals(0, $stream->getPosition());
            $chunk = $stream->read();
            self::assertEquals("Lorem", $chunk);
            self::assertEquals(5, $stream->getPosition());
            $stream->skip(5);
            $chunk = $stream->read();
            self::assertEquals("m dol", $chunk);
            self::assertEquals(15, $stream->getPosition());
            $stream->close();
        }

        public function tearDown(){
            unlink("tests/IO/stream.file.txt");
        }

    }