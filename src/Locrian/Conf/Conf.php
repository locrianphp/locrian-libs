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

    use Locrian\Collections\HashMap;
    use Locrian\Conf\Tokenizer\DefaultConfTokenizer;
    use Locrian\InvalidArgumentException;
    use Locrian\IO\File;
    use Locrian\IO\IOException;
    use Locrian\Util\FileUtils;
    use Locrian\Util\Path;
    use Locrian\Util\StringUtils;
    use Symfony\Component\Yaml\Exception\ParseException;

    class Conf{

        /**
         * @var string
         * Cache directory
         */
        private $cacheFilePath;


        /**
         * @var array
         * Parsed configurations
         */
        private $conf;


        /**
         * @var \Locrian\IO\File
         */
        private $confFile;


        /**
         * Conf constructor.
         *
         * @param string $fileName
         * @param boolean $cache
         * @param string|null $cacheDir
         * @throws \Locrian\IO\IOException
         * @throws \Locrian\InvalidArgumentException
         */
        public function __construct($fileName, $cache = false, $cacheDir = null){
            $this->confFile = new File($fileName);
            $this->cacheFilePath = null;
            if( !$this->confFile->exists() ){
                throw new IOException("Conf file does not exist");
            }
            if( $cache === true ){
                if( $cacheDir == null || !is_string($cacheDir) ){
                    throw new InvalidArgumentException("Invalid directory path");
                }
                $this->cacheFilePath = Path::normalize(Path::join($cacheDir, (md5($this->confFile->getName()) . ".cache")));
                $this->processCache();
            }
            else{
                $tree = $this->parseTree();
                $this->conf = $this->convertArray($tree);
            }
        }


        /**
         * @return string
         */
        public function getCacheFilePath(){
            return $this->cacheFilePath;
        }


        /**
         * @param string $key
         * @return mixed|null
         */
        public function find($key){
            if( isset($this->conf[$key]) ){
                return $this->conf[$key];
            }
            else{
                return null;
            }
        }


        /**
         * @param string $key
         * @param mixed $value
         * @throws \Locrian\InvalidArgumentException
         *
         * Overrides an existing configuration
         * Change will not be permanent
         */
        public function override($key, $value){
            if( isset($this->conf[$key]) ){
                $this->conf[$key] = $value;
            }
            else{
                throw new InvalidArgumentException("Unknown configuration: " . $key);
            }
        }


        /**
         * @param string $ns
         * @return array
         *
         * Returns all configurations which belongs to the given namespace
         */
        public function findAll($ns){
            $tmp = [];
            $ns = $ns . ".";
            foreach( $this->conf as $key => $value ){
                if( StringUtils::startsWith($ns, $key) ){
                    $tokens = explode(".", trim(str_replace($ns, "", $key), "."));
                    $arr = &$tmp;
                    foreach( $tokens as $v ){
                        if( !isset($arr[$v]) ){
                            $arr[$v] = [];
                        }
                        $arr = &$arr[$v];
                    }
                    $arr = $value;
                }
            }
            return $tmp;
        }


        /**
         * Compare conf and cache file's last modified timestamps and if cache is out of date
         * parse conf and recreate cache file.
         */
        private function processCache(){
            $cacheFile = new File($this->cacheFilePath);
            if( $cacheFile->exists() ){
                $cacheStamp = $cacheFile->getLastModified();
                $confStamp = $this->confFile->getLastModified();
                if( ($cacheStamp <= $confStamp) || $cacheStamp === false || $confStamp === false ){ // Then we have changes in conf file or an error occurred
                    $tree = $this->parseTree();
                    $this->conf = $this->convertArray($tree);
                    $this->exportToFile($cacheFile, $this->conf);
                }
                else{
                    $this->conf = require $cacheFile->getPath();
                }
            }
            else{
                $tree = $this->parseTree();
                $this->conf = $this->convertArray($tree);
                $this->exportToFile($cacheFile, $this->conf);
            }
        }


        /**
         * @param \Locrian\IO\File $cacheFile
         * @param array $arr
         * @throws \Locrian\IO\IOException
         *
         * Export the parsed content as a php array
         */
        private function exportToFile(File $cacheFile, Array $arr){
            if( !$cacheFile->exists() ){
                if( !$cacheFile->touch() ){
                    throw new IOException("Conf cache file cannot be created");
                }
            }
            $content = "<?php\n\n\treturn [\n";
            foreach( $arr as $k => $v ){
                $content .= "\t\t\"" . $k . "\" => ";
                if( is_string($v) ){
                    $content .= "\"" . $v . "\",\n";
                }
                else if( is_array($v) ){
                    $tmp = "[";
                    foreach( $v as $value ){
                        if( is_string($value) ){
                            $tmp .= "\"" . $value . "\",";
                        }
                        else{
                            $tmp .= $value . ",";
                        }
                    }
                    $tmp = rtrim($tmp, ",");
                    $tmp .= "],\n";
                    $content .= $tmp;
                }
                else if( is_bool($v) ){
                    $v = $v === true ? "true" : "false";
                    $content .= $v . ",\n";
                }
                else{
                    $content .= $v . ",\n";
                }
            }
            $content = rtrim(rtrim($content), ",");
            $content .= "\n\t];";
            FileUtils::writeText($cacheFile, $content);
        }


        /**
         * @return \Locrian\Conf\ConfTree
         *
         * Parses conf tree
         */
        private function parseTree(){
            $parser = new ConfParser(new DefaultConfTokenizer());
            return $parser->parseTree(FileUtils::readText($this->confFile));
        }


        /**
         * @param \Locrian\Conf\ConfTree $tree
         * @return array
         *
         * Converts tree to array
         */
        private function convertArray(ConfTree $tree){
            $map = new HashMap();
            $this->convertArrayHelper("", $tree, $map);
            $arr = $map->toArray();
            foreach( $arr as $k => $v ){
                if( is_string($v) ){
                    if( preg_match_all("/\\$\\{([a-zA-Z0-9\\.]+)\\}/", $v, $matched) ){
                        if( count($matched) == 2 ){
                            foreach( $matched[1] as $key => $value ){
                                if( $map->has($value) ){
                                    $replace = "\${" . $value . "}";
                                    $v = str_replace($replace, $map->get($value), $v);
                                    $arr[$k] = $v;
                                }
                                else{
                                    $nsTokens = explode(".", $k);
                                    $nsTokens[count($nsTokens) - 1] = "";
                                    $vl = trim(implode(".", $nsTokens), ".") . "." . $value; // Key for searching array
                                    if( $map->has($vl) ){
                                        $replace = "\${" . $value . "}";
                                        $v = str_replace($replace, $map->get($vl), $v);
                                        $arr[$k] = $v;
                                    }
                                    else{
                                        throw new ParseException("Invalid variable: " . $value);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return $arr;
        }


        /**
         * @param string $namespace
         * @param \Locrian\Conf\ConfTree $tree
         * @param \Locrian\Collections\HashMap $map
         *
         * Recursive convert
         */
        private function convertArrayHelper($namespace, ConfTree $tree, HashMap $map){
            $namespace = trim($tree->getKey() == null ? $namespace : ($namespace . "." . $tree->getKey()), ".");
            if( $tree->getType() == ConfTree::OBJECT_NODE ){
                $map->add($namespace, $tree->getValue());
            }
            else if( $tree->getType() == ConfTree::ARRAY_NODE ){
                $map->add($namespace, $tree->getValue()->toArray());
            }
            else{
                foreach( $tree->getChildren()->getKeys() as $k ){
                    $this->convertArrayHelper($namespace, $tree->getChildren()->get($k), $map);
                }
            }
        }

    }