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

    namespace Locrian\Util;

    use Locrian\InvalidArgumentException;

    class Filter{

        /**
         * @var array
         *
         * $_quote = [
         *      "keys"  =>  [
         *
         *      ],
         *      "values"  =>  [
         *
         *      ]
         * ]
         *
         * Example for key value pairs
         *
         * keys : "'", '"'
         * values : &#39;, &#34;
         *
         * All the following private fields in this format
         */
        private $quote;


        /**
         * @var array
         * Php tag encoding and decoding characters
         */
        private $phpTag;


        /**
         * @var array
         *
         * All the bad characters and their html representations
         * Like htmlspecialchars function characters
         */
        private $badChars;


        /**
         * Security constructor.
         */
        public function __construct(){
            $this->quote = [
                "keys" => [
                    "'", '"'
                ],
                "values" => [
                    "&#39;", "&#34;"
                ]
            ];
            $this->phpTag = [
                "keys" => [
                    "<?php", "<?", "<%", "%>", "?>"
                ],
                "values" => [
                    "&#60;&#63;php", "&#60;&#63;", "&#60;&#37;", "&#37;&#62;", "&#63;&#62;"
                ]
            ];
            $this->badChars = [
                "keys" => [
                    "&", "%", "$", "(", ")", "'", '"',
                    "*", "/", "<", ">", "?", "@", "\\",
                    "!", "+", "-", ",", ".", "=", "^",
                    "_", "{", "|", "}", "~"
                ],
                "values" => [
                    "&#38;", "&#37;", "&#36;", "&#40;", "&#41;", "&#39;", "&#34;",
                    "&#42;", "&#47;", "&#60;", "&#62;", "&#63;", "&#64;", "&#92;",
                    "&#33;", "&#43;", "&#45;", "&#45;", "&#45;", "&#61;", "&#94;",
                    "&#95;", "&#123;", "&#124;", "&#125;", "&#123;"
                ]
            ];
        }


        /**
         * @param $target
         * @param array $keys
         * @param array $values
         * @param bool $isRegex
         *
         * @return mixed
         * @throws InvalidArgumentException
         */
        private function convert($target, array $keys, array $values, $isRegex = false){
            if( !is_string($target) ){
                throw new InvalidArgumentException("Target must be a string!");
            }
            if( count($keys) !== count($values) ){
                throw new InvalidArgumentException("Keys and Values must be compatible with each other!");
            }

            // Iteration count (keys = values)
            $iterationCount = count($keys);

            // If keys are not regex then we should use str_replace
            if( $isRegex === false ){
                for( $i = 0; $i < $iterationCount; $i++ ){
                    $target = str_replace($keys[$i], $values[$i], $target);
                }
            }
            else{
                for( $i = 0; $i < $iterationCount; $i++ ){
                    $target = preg_replace($keys[$i], $values[$i], $target);
                }
            }
            return $target;
        }


        /**
         * @param $target
         * @param array $keys
         * @param string $value
         * @param bool $isRegex
         *
         * @return mixed
         * @throws InvalidArgumentException
         *
         * Clears the target string from given characters in the keys array
         */
        private function clear($target, array $keys, $value = "", $isRegex = false){
            if( !is_string($target) || !is_string($value) ){
                throw new InvalidArgumentException("Target and Value must be a string!");
            }
            // Iteration count (keys = values)
            $iterationCount = count($keys);
            // If keys are not regex then we should use str_replace
            if( $isRegex === false ){
                for( $i = 0; $i < $iterationCount; $i++ ){
                    $target = str_replace($keys[$i], $value, $target);
                }
            }
            else{
                for( $i = 0; $i < $iterationCount; $i++ ){
                    $target = preg_replace($keys[$i], $value, $target);
                }
            }
            return $target;
        }


        /**
         * @param $target string
         *
         * @return string
         * @throws InvalidArgumentException
         *
         * Encodes the single and double quotes in the target string
         */
        public function encodeQuotes($target){
            return $this->convert($target, $this->quote['keys'], $this->quote['values']);
        }


        /**
         * @param $target
         *
         * @return mixed
         * @throws InvalidArgumentException
         *
         * Decodes the single and double quotes in the target string
         */
        public function decodeQuotes($target){
            return $this->convert($target, $this->quote['values'], $this->quote['keys']);
        }


        /**
         * @param $target string
         *
         * @return string
         * @throws InvalidArgumentException
         *
         * Clears single and double quotes in the target string
         */
        public function clearQuotes($target){
            return $this->clear($target, $this->quote['keys']);
        }


        /**
         * @param $target
         *
         * @return mixed
         * @throws InvalidArgumentException
         *
         * Encodes php tags in the target string
         */
        public function encodePhpTags($target){
            return $this->convert($target, $this->phpTag['keys'], $this->phpTag['values']);
        }


        /**
         * @param $target
         *
         * @return mixed
         * @throws InvalidArgumentException
         *
         * Decodes php tags in the target string
         */
        public function decodePhpTags($target){
            return $this->convert($target, $this->phpTag['values'], $this->phpTag['keys']);
        }


        /**
         * @param $target
         *
         * @return mixed
         * @throws InvalidArgumentException
         *
         * Clears php tags in the target string
         */
        public function clearPhpTags($target){
            return $this->clear($target, $this->phpTag['keys']);
        }


        /**
         * @param $target string
         *
         * @return string
         * @throws InvalidArgumentException
         *
         * Encodes bad characters. Look private $_badChars field to see the character list
         */
        public function encodeBadChars($target){
            return $this->convert($target, $this->badChars['keys'], $this->badChars['values']);
        }


        /**
         * @param $target
         *
         * @return mixed
         * @throws InvalidArgumentException
         *
         * Decodes bad characters
         */
        public function decodeBadChars($target){
            return $this->convert($target, $this->badChars['values'], $this->badChars['keys']);
        }


        /**
         * @param $target
         * @return mixed
         *
         * Clears bad characters
         */
        public function clearBadChars($target){
            return $this->clear($target, $this->badChars["keys"]);
        }


        /**
         * @param $target
         * @param string $whiteList
         *
         * @return mixed
         * @throws InvalidArgumentException
         *
         * Cleans html tags which are not in the white list
         */
        public function stripTags($target, $whiteList = ""){
            if( is_string($whiteList) ){
                return $this->decodeBadChars(strip_tags($this->decodeBadChars($target), $whiteList));
            }
            else{
                throw new InvalidArgumentException("White list must be a string!");
            }
        }


        /**
         * @param $target
         *
         * @return mixed
         * @throws InvalidArgumentException
         *
         * Clears url and returns the clean new url
         */
        public function clearUrl($target){
            return $this->stripTags($this->convert(urldecode(filter_var($target, FILTER_SANITIZE_URL)), [" "], ["+"]));
        }

    }