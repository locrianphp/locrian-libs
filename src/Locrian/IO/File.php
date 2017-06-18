<?php

    /**
     * * * * * * * * * * * * * * * * * * * *
     *        Locrian Framework            *
     * * * * * * * * * * * * * * * * * * * *
     *                                     *
     * Author  : Özgür Senekci             *
     *                                     *
     * Skype   :  socialinf                *
     *                                     *
     * License : The MIT License (MIT)     *
     *                                     *
     * * * * * * * * * * * * * * * * * * * *
     */

    namespace Locrian\IO;

    use Locrian\Collections\ArrayList;
    use Locrian\InvalidArgumentException;
    use Locrian\Util\Path;

    class File{

        /**
         * Directory separator
         */
        const SEPARATOR = DIRECTORY_SEPARATOR;


        /**
         * Creates all non exist folders when using touch or mkdir
         */
        const CREATE_ALL_NON_EXIST = "create_all_non_exist_policy";


        /**
         * Creates only the last node (file or directory) and if there is a missing parent
         * folder then throws IOException when using touch or mkdir
         */
        const CREATE_ONLY_LAST_NODE = "create_only_last_node_policy";


        /**
         * @var string
         * Relative file path
         */
        private $relativeFilePath;


        /**
         * @var mixed
         * File name
         */
        private $name;


        /**
         * @var string creating policy
         */
        private $policy;


        /**
         * File constructor.
         *
         * @param $path
         * @param $policy
         *
         * @throws IOException
         */
        public function __construct($path, $policy = self::CREATE_ALL_NON_EXIST){
            if( is_string($path) && strlen($path) > 0 ){
                // Not uri
                if( !preg_match("%^[a-zA-Z]+://%", $path) ){
                    $path = Path::normalize($path);
                    $this->relativeFilePath = $path;
                    $nameArr = explode(self::SEPARATOR, $path);
                    $this->name = end($nameArr);
                    if( $this->name == "" && count($nameArr) > 1 ){ // We are dealing with a directory with a separator at the end of its path
                        $this->name = $nameArr[count($nameArr) - 2];
                    }
                }
                else{ // Uri
                    $this->relativeFilePath = $path;
                    $this->name = $path;
                }
                $this->policy = $policy;
            }
            else{
                throw new IOException("File constructor requires a valid file path");
            }
        }


        /**
         * @return mixed
         * Gives file name
         */
        public function getName(){
            return $this->name;
        }


        /**
         * @return mixed|string
         * Gives relative path
         */
        public function getPath(){
            return $this->relativeFilePath;
        }


        /**
         * @return bool
         * Returns whether the path is absolute
         */
        public function isAbsoluteFile(){
            return Path::isAbsolute($this->getPath());
        }


        /**
         * @return bool
         * returns true if given path is a directory
         */
        public function isDirectory(){
            return is_dir($this->getPath());
        }


        /**
         * @return bool
         * Returns true if file is a link
         */
        public function isLink(){
            return is_link($this->getPath());
        }


        /**
         * @return bool
         * returns true if given path is a file
         */
        public function isFile(){
            return is_file($this->getPath());
        }


        /**
         * @return bool
         * Returns true if the file readable
         */
        public function isReadable(){
            return $this->exists() && is_readable($this->getPath());
        }


        /**
         * @return bool
         * Returns true if file is writable
         */
        public function isWritable(){
            return $this->exists() && is_writable($this->getPath());
        }


        /**
         * @return bool
         * Returns true if file is executable
         */
        public function isExecutable(){
            return $this->exists() && is_executable($this->getPath());
        }


        /**
         * @return bool
         * Checks whether the file exists
         */
        public function exists(){
            return $this->isDirectory() || $this->isFile();
        }


        /**
         * @return int
         * returns file size
         * returns 0 if given path is not a file
         */
        public function getSize(){
            if( $this->exists() && $this->isFile() ){
                return filesize($this->getPath());
            }
            else{
                return 0;
            }
        }


        /**
         * @return bool|int
         * Returns the file permissions like 0755
         */
        public function getPermissions(){
            if( $this->exists() ){
                return (int) fileperms($this->getPath());
            }
            else{
                return false;
            }
        }


        /**
         * @return bool|int
         * Returns last access time
         */
        public function getLastAccess(){
            if( $this->exists() && $this->isFile() ){
                return fileatime($this->getPath());
            }
            else{
                return false;
            }
        }


        /**
         * @return bool|int
         * Returns last modified time
         */
        public function getLastModified(){
            if( $this->exists() && $this->isFile() ){
                return filemtime($this->getPath());
            }
            else{
                return false;
            }
        }


        /**
         * @param File $destination
         *
         * @return bool
         * Moves files or directories
         */
        public function move(File $destination){
            if( $this->copy0($destination) ){
                if( $this->remove(true) ){
                    $this->updatePaths($destination);
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        }


        /**
         * @param File $destination
         *
         * @return bool
         * Copies files or directory
         */
        public function copy(File $destination){
            return $this->copy0($destination);
        }


        /**
         * @param File $destination
         *
         * @return bool
         * Renames files or directory
         */
        public function rename(File $destination){
            return $this->move($destination);
        }


        /**
         * @param File $destination
         *
         * @return bool
         * Copy helper
         */
        private function copy0(File $destination){
            if( $this->exists() ){
                if( $this->isFile() ){
                    return copy($this->getPath(), $destination->getPath());
                }
                else{
                    if( $this->isDirectory() ){
                        $success = true;
                        $destination->mkdir($this->getPermissions());
                        $childList = $this->getChildFiles();
                        $len = $childList->size();
                        for( $i = 0; $i < $len; $i++ ){
                            $child = $childList->get($i);
                            if( !$child->copy(new File($destination->getPath() . "/" . $child->getName())) ){
                                $success = false;
                            }
                        }
                        return $success;
                    }
                    else{
                        return false;
                    }
                }
            }
            else{
                return false;
            }
        }


        /**
         * @param File $file
         * Updates the paths after move operation
         */
        private function updatePaths(File $file){
            $this->relativeFilePath = $file->getPath();
            $this->name = $file->getName();
        }


        /**
         * @param int $fileMode
         *
         * @return bool
         * @throws InvalidArgumentException
         * Creates a file
         * If policy is CREATE_ALL_NON_EXIST then creates all the parent folders if they don't exist
         */
        public function touch($fileMode = 0755){
            if( $this->exists() ){
                return false;
            }
            else{
                if( is_int($fileMode) ){
                    if( $this->policy == self::CREATE_ALL_NON_EXIST ){
                        $exploded = explode(self::SEPARATOR, $this->getPath());
                        $len = count($exploded);
                        if( $len > 1 ){
                            unset($exploded[$len - 1]);
                            $this->createParentDirectories(implode(self::SEPARATOR, $exploded), $fileMode);
                            return $this->createFile($this->getPath());
                        }
                        else{
                            return $this->createFile($this->getPath());
                        }
                    }
                    else{
                        return $this->createFile($this->getPath());
                    }
                }
                else{
                    throw new InvalidArgumentException("File mode must be an integer.");
                }
            }
        }


        /**
         * @return bool|string
         * Returns mime of a file
         */
        public function getMime(){
            if( $this->exists() && $this->isFile() ){
                return mime_content_type($this->getPath());
            }
            else{
                return false;
            }
        }


        /**
         * @param int $fileMode
         *
         * @return bool
         * @throws IOException
         * Creates a directory
         * If policy is CREATE_ALL_NON_EXIST then creates all the parent folders if they don't exist
         */
        public function mkdir($fileMode = 0755){
            if( !$this->exists() ){
                if( is_int($fileMode) ){
                    return $this->createParentDirectories($this->getPath(), $fileMode);
                }
                else{
                    throw new IOException("File mode must be an integer.");
                }
            }
            else{
                return false;
            }
        }


        /**
         * @param bool $recursive
         *
         * @return bool Removes a file or directory
         * Removes a file or directory
         */
        public function remove($recursive = false){
            if( $this->exists() ){
                if( $this->isFile() ){
                    return unlink($this->getPath());
                }
                else{
                    if( $this->isDirectory() ){
                        if( $recursive === true ){
                            $files = $this->getChildFiles();
                            $size = $files->size();
                            if( $size > 0 ){
                                for( $i = 0; $i < $size; $i++ ){
                                    $files->get($i)->remove(true);
                                }
                            }
                        }
                        return rmdir($this->getPath());
                    }
                }
                return false;
            }
            return false;
        }


        /**
         * @param $fileMode
         *
         * @return bool
         * Changes file mode
         */
        public function chmod($fileMode){
            if( $this->exists() ){
                return chmod($this->getPath(), $fileMode);
            }
            else{
                return false;
            }
        }


        /**
         * @param $path
         *
         * @return bool
         * Helper method
         */
        private function createFile($path){
            $file = fopen($path, "w");
            if( $file === false ){
                return false;
            }
            else{
                fclose($file);
                return true;
            }
        }


        /**
         * @param $path
         * @param $fileMode
         *
         * @return bool
         * Helper method
         */
        private function createParentDirectories($path, $fileMode){
            if( !is_dir($path) ){
                if( $this->policy == self::CREATE_ALL_NON_EXIST ){
                    return mkdir($path, $fileMode, true);
                }
                else{
                    return mkdir($path, $fileMode, false);
                }
            }
            else{
                // Directories wont actually be created if this line is executed but
                // since this is just a helper method we don't need to control that
                return true;
            }
        }


        /**
         * @return null|ArrayList
         * Returns the list of all files in a directory
         */
        public function getChildFiles(){
            if( $this->isDirectory() ){
                $list = new ArrayList();
                $parentPath = $this->getPath() . "/";
                $fileList = scandir($parentPath);
                foreach( $fileList as $fileName ){
                    if( $fileName != "." && $fileName != ".." ){
                        $list->add(new File($parentPath . $fileName));
                    }
                }
                return $list;
            }
            else{
                return null;
            }
        }


        /**
         * @return string
         * toString
         */
        public function __toString(){
            return $this->getPath();
        }

    }