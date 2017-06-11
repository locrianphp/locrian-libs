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

    use Locrian\RuntimeException;

    class BCrypt{

		/**
		 * @var array
		 * Default BCrypt options
		 */
		private static $defaultOptions = [
			"cost" => 10
		];


		/**
		 * @param string $data
		 * @param int $cost
		 *
		 * @return string
		 * @throws RuntimeException
		 *
		 * Hashes the given string
		 */
		public static function hash($data, $cost = -1){
			$opts = self::$defaultOptions;
			if( is_int($cost) && $cost > 0 ){
				$opts["cost"] = $cost;
			}
			$result = password_hash($data, PASSWORD_BCRYPT, $opts);
			if( $result === false ){
				throw new RuntimeException("BCrypt not supported");
			}
			return $result;
		}


		/**
		 * @param string $plain
		 * @param string $hashed
		 *
		 * @return bool
		 *
		 * Verifies the hashed value
		 */
		public static function verify($plain, $hashed){
			return password_verify($plain, $hashed);
		}

	}