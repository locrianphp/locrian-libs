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

    namespace Locrian\Http\Session;

    use Locrian\Crypt\HashHMAC;
    use Locrian\Http\Session\Driver\SessionDriver;
    use Locrian\IO\File;
    use Locrian\Util\Path;
    use Locrian\Util\Properties;

    class SessionManager{

        /**
         * Session name prefix
         */
        const SESSION_ID_PREFIX = "locrian_session__";


        /**
         * Session manager property file name
         */
        const CACHE_FILE_NAME = "session_manager.properties";


        /**
         * @var \Locrian\Http\Session\Driver\SessionDriver
         */
        private $driver;


        /**
         * @var \Locrian\Crypt\HashHMAC
         */
        private $hashHMAC;


        /**
         * @var \Locrian\Util\Properties
         */
        private $cache;


        /**
         * @var integer
         */
        private $gcInterval;


        /**
         * SessionManager constructor.
         *
         * @param \Locrian\Http\Session\Driver\SessionDriver $driver
         * @param \Locrian\Crypt\HashHMAC $hashHMAC
         * @param string $cacheDir
         * @param integer $gcInterval garbage collecting check interval
         */
        public function __construct(SessionDriver $driver, HashHMAC $hashHMAC, $cacheDir, $gcInterval){
            $this->driver = $driver;
            $this->hashHMAC = $hashHMAC;
            $this->gcInterval = $gcInterval;
            $this->cache = new Properties(new File(Path::join($cacheDir, self::CACHE_FILE_NAME)));
            $this->cache->load();
            $this->checkGC();
        }


        /**
         * Check the time and start garbage collecting
         */
        private function checkGC(){
            $now = time();
            $lastGCCheck = $this->cache->getInt("lastGCCheck", 0);
            if( ($now - $lastGCCheck) >= $this->gcInterval ){
                $this->driver->destroyExpiredSessions();
                $this->cache->setProperty("lastGCCheck", $now);
                $this->cache->commit();
            }
        }


        /**
         * @return \Locrian\Http\Session\Session
         * Create new empty Session object
         */
        public function createSession(){
            return new Session($this->generateSessionId());
        }


        /**
         * @param \Locrian\Http\Session\Session $session
         * Save the session
         */
        public function saveSession(Session $session){
            $this->driver->save($session);
        }


        /**
         * @param string $id Session id
         * @return \Locrian\Http\Session\Session|null
         * Return the session belongs to the given id
         */
        public function findSession($id){
            return $this->driver->find($id);
        }


        /**
         * @param string $id Session id
         * Remove the session belongs to the given id
         */
        public function removeSession($id){
            $this->driver->remove($id);
        }


        /**
         * @return integer
         * Return session count
         * This may not give the active session count
         * Some inactive sessions may not be removed yet
         */
        public function getSessionCount(){
            return $this->driver->count();
        }


        /**
         * @return string
         * Create new unique session name
         */
        private function generateSessionId(){
            return $this->hashHMAC->sha1(self::SESSION_ID_PREFIX . uniqid());
        }

    }