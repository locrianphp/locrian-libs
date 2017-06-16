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

    namespace Locrian\Conf;

    use Locrian\Conf\Tokenizer\DefaultConfTokenizer;
    use Locrian\InvalidArgumentException;
    use Locrian\IO\File;
    use Locrian\IO\IOException;
    use Locrian\Util\FileUtils;

    class Conf{

        /**
         * @var boolean
         */
        private $cache;


        /**
         * @var string
         * Cache directory
         */
        private $cacheDir;


        /**
         * @var array
         * Parsed configurations
         */
        private $conf;


        /**
         * @var \Locrian\IO\File
         */
        private $file;


        public function __construct($fileName, $cache = false, $cacheDir = null){
            $this->file = new File($fileName);
            if( !$this->file->exists() ){
                throw new IOException("Conf file does not exist");
            }
            if( $cache === true ){
                if( $cacheDir == null || !is_string($cacheDir) ){
                    throw new InvalidArgumentException("Invalid directory path");
                }
                $this->processCache();
            }
            else{
                $this->parseTree();
            }
        }


        private function processCache(){
            // Compare last modified dates of generated ad source files then parse again or use generated file
        }


        private function parseTree(){
            $parser = new ConfParser(new DefaultConfTokenizer());
            $tree = $parser->parseTree(FileUtils::readText($this->file));
            $this->convertArray($tree);
        }


        private function convertArray(ConfTree $tree){

        }

    }