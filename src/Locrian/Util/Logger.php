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
    use Locrian\IO\File;

    class Logger{

        /**
         * Info log file path
         */
        private $infoLogFile;


        /**
         * Error log file path
         */
        private $warnLogFile;


        /**
         * Warning log file path
         */
        private $errorLogFile;


        /**
         * Info log type
         */
        const TYPE_INFO = "INFO";


        /**
         * Error log type
         */
        const TYPE_ERROR = "ERROR";


        /**
         * Warning log type
         */
        const TYPE_WARNING = "WARNING";


        /**
         * Logger constructor.
         *
         * @param $infoLogFile
         * @param $warnLogFile
         * @param $errorLogFile
         */
        public function __construct($infoLogFile, $warnLogFile, $errorLogFile){
            $this->infoLogFile = $infoLogFile;
            $this->warnLogFile = $warnLogFile;
            $this->errorLogFile = $errorLogFile;
        }


        /**
         * @param $log
         * Logs info
         */
        public function info($log){
            if( $this->checkLog($log) ){
                $log = $this->createLog($log, self::TYPE_INFO);
                $this->appendFile($this->infoLogFile, $log);
            }
        }


        /**
         * @param $log
         * Logs error
         */
        public function error($log){
            if( $this->checkLog($log) ){
                $log = $this->createLog($log, self::TYPE_ERROR);
                $this->appendFile($this->errorLogFile, $log);
            }
        }


        /**
         * @param $log
         * Logs warning
         */
        public function warning($log){
            if( $this->checkLog($log) ){
                $log = $this->createLog($log, self::TYPE_WARNING);
                $this->appendFile($this->warnLogFile, $log);
            }
        }


        /**
         * @param string $message
         * @param string $type
         *
         * @return string log
         * Creates log text
         */
        private function createLog($message, $type){
            return date("m.d.y H:i:s") . " [" . $type . "] " . $message . "\r\n";
        }


        /**
         * @param $filePath string
         * @param $log string
         *
         * Appends log to file
         */
        private function appendFile($filePath, $log){
            FileUtils::writeText(new File($filePath), $log, FileUtils::APPEND);
        }


        /**
         * @param $log
         *
         * @throws InvalidArgumentException
         * @return boolean
         *
         * Exception thrower helper method
         */
        private function checkLog($log){
            if( !is_string($log) && !method_exists($log, "__toString") ){
                throw new InvalidArgumentException("Log content must be a string");
            }
            else{
                return true;
            }
        }

    }