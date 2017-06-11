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

	namespace Locrian\Collections;

	class ListNode{

		/**
		 * @var ListNode previous node
		 */
		private $prev;


		/**
		 * @var ListNode next node
		 */
		private $next;


		/**
		 * @var mixed data
		 */
		private $data;


		/**
		 * Node constructor.
		 *
		 * @param $data
		 */
		public function __construct($data){
			$this->data = $data;
			$this->next = null;
			$this->prev = null;
		}


		/**
		 * @return ListNode
		 */
		public function getPrev(){
			return $this->prev;
		}


		/**
		 * @param ListNode $prev
		 */
		public function setPrev($prev){
			$this->prev = &$prev;
		}


		/**
		 * @return ListNode
		 */
		public function getNext(){
			return $this->next;
		}


		/**
		 * @param ListNode $next
		 */
		public function setNext($next){
			$this->next = &$next;
		}


		/**
		 * @return mixed
		 */
		public function getData(){
			return $this->data;
		}


		/**
		 * @param mixed $data
		 */
		public function setData($data){
			$this->data = $data;
		}

	}