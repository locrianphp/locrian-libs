<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 18.06.2017
     * Time: 23:48
     */

    namespace Locrian\Tests\IO;

    use Locrian\IO\File;
    use PHPUnit_Framework_TestCase;

    class FileTest extends PHPUnit_Framework_TestCase{

        private $filePath = "tests/IO/file.txt";

        private $dirPath = "tests/IO/dir/";

        public function testNameAndPath(){
            $f = new File($this->filePath);
            self::assertEquals("file.txt", $f->getName());
            self::assertEquals($this->filePath, $f->getPath());
            $f = new File($this->dirPath);
            self::assertEquals("dir", $f->getName());
            self::assertEquals($this->dirPath, $f->getPath());
            $f = new File("/");
            self::assertEquals("", $f->getName());
            self::assertEquals("/", $f->getPath());
        }

        public function testIsAbsolute(){
            $f = new File("/tmp");
            self::assertTrue($f->isAbsoluteFile());
            $f = new File($this->filePath);
            self::assertFalse($f->isAbsoluteFile());
        }

        public function testIsFlags(){
            $f = new File("tests/IO/FileTest.php");
            self::assertFalse($f->isDirectory());
            self::assertTrue($f->isFile());
            self::assertFalse($f->isLink());
            self::assertTrue($f->isReadable());
            self::assertTrue($f->isWritable());
            self::assertFalse($f->isExecutable());
            self::assertTrue($f->exists());
            $f = new File("tests/IO/");
            self::assertTrue($f->isDirectory());
            self::assertFalse($f->isFile());
            self::assertTrue($f->exists());
        }

        public function testTouchAndSize(){
            $f = new File($this->filePath);
            $f->touch();
            self::assertTrue($f->exists());
            self::assertTrue(file_exists($f->getPath()));
            self::assertEquals(0, $f->getSize());
            unlink($f->getPath());
        }

        public function testMkdir(){
            $d = new File($this->dirPath);
            self::assertFalse($d->exists());
            $d->mkdir();
            self::assertTrue($d->exists());
            rmdir($d->getPath());
        }

        public function testMove(){
            $f = new File($this->filePath);
            $f->touch();
            $f->move(new File("tests/IO/file2.txt"));
            self::assertEquals("file2.txt", $f->getName());
            self::assertTrue(file_exists($f->getPath()));
            self::assertFalse(file_exists($this->filePath));
            unlink($f->getPath());
        }

        public function testCopy(){
            $f = new File($this->filePath);
            $f->touch();
            $f->copy(new File("tests/IO/file2.txt"));
            self::assertEquals("file.txt", $f->getName());
            self::assertTrue(file_exists($f->getPath()));
            self::assertTrue(file_exists($this->filePath));
            unlink($f->getPath());
            unlink("tests/IO/file2.txt");
        }

        public function testRename(){
            $f = new File($this->filePath);
            $f->touch();
            $f->rename(new File("tests/IO/file2.txt"));
            self::assertEquals("file2.txt", $f->getName());
            self::assertTrue(file_exists($f->getPath()));
            self::assertFalse(file_exists($this->filePath));
            unlink($f->getPath());
        }

        public function testRemove(){
            $f = new File($this->filePath);
            $f->touch();
            self::assertTrue($f->exists());
            $f->remove();
            self::assertFalse($f->exists());
        }

        public function testCreateParentDirectories(){
            $f = new File("tests/IO/dir/subdir/subdir2/a.txt");
            $f->touch();
            self::assertTrue(file_exists("tests/IO/dir"));
            self::assertTrue(file_exists("tests/IO/dir/subdir"));
            self::assertTrue(file_exists("tests/IO/dir/subdir/subdir2"));
            self::assertTrue(file_exists("tests/IO/dir/subdir/subdir2/a.txt"));
            (new File("tests/IO/dir"))->remove(true);
        }

        public function testMoveRemoveDir(){
            $f = $this->createTree();
            self::assertTrue(file_exists("tests/IO/dir"));
            $f->move(new File("tests/IO/dir2/"));
            self::assertFalse(file_exists("tests/IO/dir"));
            self::assertTrue(file_exists("tests/IO/dir2"));
            self::assertTrue(file_exists("tests/IO/dir2/a.txt"));
            self::assertTrue(file_exists("tests/IO/dir2/c.txt"));
            self::assertTrue(file_exists("tests/IO/dir2/subdir/a.txt"));
            $f->remove(true); // Remove recursively
            self::assertFalse(file_exists("tests/IO/dir2"));
        }

        public function testRenameRemoveDir(){
            $f = $this->createTree();
            self::assertTrue(file_exists("tests/IO/dir"));
            $f->rename(new File("tests/IO/dir2/"));
            self::assertFalse(file_exists("tests/IO/dir"));
            self::assertTrue(file_exists("tests/IO/dir2"));
            self::assertTrue(file_exists("tests/IO/dir2/a.txt"));
            self::assertTrue(file_exists("tests/IO/dir2/c.txt"));
            self::assertTrue(file_exists("tests/IO/dir2/subdir/a.txt"));
            $f->remove(true); // Remove recursively
            self::assertFalse(file_exists("tests/IO/dir2"));
        }

        public function testCopyRemoveDir(){
            $f = $this->createTree();
            self::assertTrue(file_exists("tests/IO/dir"));
            $f->copy(new File("tests/IO/dir2/"));
            self::assertTrue(file_exists("tests/IO/dir"));
            self::assertTrue(file_exists("tests/IO/dir"));
            self::assertTrue(file_exists("tests/IO/dir/a.txt"));
            self::assertTrue(file_exists("tests/IO/dir/c.txt"));
            self::assertTrue(file_exists("tests/IO/dir/subdir/a.txt"));
            self::assertTrue(file_exists("tests/IO/dir2"));
            self::assertTrue(file_exists("tests/IO/dir2/a.txt"));
            self::assertTrue(file_exists("tests/IO/dir2/c.txt"));
            self::assertTrue(file_exists("tests/IO/dir2/subdir/a.txt"));
            $f->remove(true); // Remove recursively
            (new File("tests/IO/dir2"))->remove(true);
            self::assertFalse(file_exists("tests/IO/dir2"));
            self::assertFalse(file_exists("tests/IO/dir"));
        }

        private function createTree(){
            $f = new File($this->dirPath);
            (new File($f->getPath() . "a.txt"))->touch();
            (new File($f->getPath() . "b.txt"))->touch();
            (new File($f->getPath() . "c.txt"))->touch();
            (new File($f->getPath() . "subdir"))->mkdir();
            (new File($f->getPath() . "subdir/a.txt"))->touch();
            return $f;
        }

    }