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

    namespace Locrian\Http\Session\Repository;

    use Locrian\Http\Session\Session;
    use Locrian\IO\File;
    use Locrian\IO\IOException;
    use Locrian\Util\FileUtils;
    use Locrian\Util\Path;

    class FileRepository implements SessionRepository{

        /**
         * @var integer
         * Max life time in miliseconds
         */
        private $maxLifetime;


        /**
         * @var \Locrian\IO\File
         * Session save directory
         */
        private $sessionDir;


        /**
         * FileDriver constructor.
         *
         * @param int $maxLifetime
         * @param string $sessionDir
         *
         * @throws \Locrian\IO\IOException
         */
        public function __construct($maxLifetime, $sessionDir){
            $this->maxLifetime = $maxLifetime;
            $dir = new File($sessionDir);
            if( !$dir->exists() ){
                if( !$dir->mkdir() ){
                    throw new IOException("Cannot create directory");
                }
            }
            $this->sessionDir = $dir;
        }


        /**
         * @param \Locrian\Http\Session\Session $session
         * Recreate or override saved session
         */
        public function save(Session $session){
            $fileName = $session->getId() . "_" . $session->getCreationTime() . ".session";
            $file = new File(Path::join($this->sessionDir->getPath(), $fileName));
            $ss = serialize($session);
            FileUtils::writeText($file, $ss);
        }


        /**
         * @param string $id
         * @return \Locrian\Http\Session\Session|null
         */
        public function find($id){
            $search = $id . "_*";
            $result = glob(Path::normalize(Path::join($this->sessionDir->getPath(), $search)));
            if( $result === false || count($result) !== 1 ){
                return null;
            }
            else{
                $file = new File($result[0]);
                if( $file->exists() ){
                    $content = FileUtils::readText($file);
                    if( $content !== false ){
                        $session = unserialize($content);
                        $now = time();
                        if( ($now - $session->getCreationTime()) >= $this->maxLifetime ){
                            $file->remove();
                            return null;
                        }
                        else{
                            return $session;
                        }
                    }
                    else{
                        return null;
                    }
                }
                else{
                    return null;
                }
            }
        }


        /**
         * @param string $id
         * Remove a session
         */
        public function remove($id){
            $search = $id . "_*";
            $result = glob(Path::join($this->sessionDir->getPath(), $search));
            if( $result !== false && count($result) === 1 ){
                $file = new File($result[0]);
                if( $file->exists() ){
                    $file->remove();
                }
            }
        }


        /**
         * @return integer
         * return session count
         */
        public function count(){
            $result = glob(Path::join($this->sessionDir->getPath(), "*.session"));
            return $result === false ? 0 : count($result);
        }


        /**
         * Destroy all the expired sessions
         */
        public function destroyExpiredSessions(){
            $result = glob(Path::join($this->sessionDir->getPath(), "*.session"));
            if( $result !== false ){
                $now = time();
                foreach( $result as $fileName ){
                    $exploded = explode("_", $fileName);
                    $time = intval(str_replace(".session", "", end($exploded)));
                    if( $time > 0 && ($now - $time) >= $this->maxLifetime ){
                        $f = new File($fileName);
                        $f->remove();
                    }
                }
            }
        }

    }