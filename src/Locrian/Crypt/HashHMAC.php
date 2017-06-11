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

	namespace Locrian\Crypt;

	class HashHMAC{

		/**
		 * @var string crypt secret
		 */
		private $secret;


		/**
		 * HashHMAC constructor.
		 * @param string $secret
		 */
		public function __construct($secret = null){
			$this->secret = $secret;
		}


		/**
		 * @param string $data string which will be hashed
		 * @return string string The hashed string
		 */
		public function sha1($data){
			return $this->hashHMAC($data, "sha1");
		}


		/**
		 * @param string $data string which will be hashed
		 * @return string string The hashed string
		 */
		public function sha224($data){
			return $this->hashHMAC($data, "sha224");
		}


		/**
		 * @param string $data string which will be hashed
		 * @return string string The hashed string
		 */
		public function sha256($data){
			return $this->hashHMAC($data, "sha256");
		}


		/**
		 * @param string $data string which will be hashed
		 * @return string string The hashed string
		 */
		public function sha384($data){
			return $this->hashHMAC($data, "sha384");
		}


		/**
		 * @param string $data string which will be hashed
		 * @return string string The hashed string
		 */
		public function sha512($data){
			return $this->hashHMAC($data, "sha512");
		}


		/**
		 * @param string $data string which will be hashed
		 * @return string string The hashed string
		 */
		public function ripemd256($data){
			return $this->hashHMAC($data, "ripemd256");
		}


		/**
		 * @param string $data string which will be hashed
		 * @return string string The hashed string
		 */
		public function crc32($data){
			return $this->hashHMAC($data, "crc32");
		}


		/**
		 * @param string $data string which will be hashed
		 * @return string string The hashed string
		 */
		public function joaat($data){
			return $this->hashHMAC($data, "joaat");
		}


		/**
		 * @param string $data string which will be hashed
		 * @return string string The hashed string
		 */
		public function whirlpool($data){
			return $this->hashHMAC($data, "whirlpool");
		}


		/**
		 * @param string $data string which will be hashed
		 * @return string string The hashed string
		 */
		public function md2($data){
			return $this->hashHMAC($data, "md2");
		}


		/**
		 * @param string $data string which will be hashed
		 * @return string string The hashed string
		 */
		public function md4($data){
			return $this->hashHMAC($data, "md4");
		}


		/**
		 * @param string $data string which will be hashed
		 * @return string string The hashed string
		 */
		public function md5($data){
			return $this->hashHMAC($data, "md5");
		}


		/**
		 * @param $data string
		 * @param $algorithm string
		 * @return string
		 *
		 * Hashes given data according to the given algorithm
		 */
		private function hashHMAC($data, $algorithm){
			$context = hash_init($algorithm, HASH_HMAC, $this->secret);
			hash_update($context, $data);
			return hash_final($context);
		}

	}