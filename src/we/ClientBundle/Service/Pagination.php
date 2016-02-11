<?php

namespace we\ClientBundle\Service;

class Pagination
{
	/**
	 * Default parameters
	**/
	private $_params = array(
		'recordsPage' => 1,
		'recordsTotal' => 0,
		'currentPage' => 1,
	);

	/**
	 * Calc data
	 * @return Array
	**/
	private function _calc() {
		$recordsPage = $this->getParam('recordsPage');
		$recordsTotal = $this->getParam('recordsTotal');

		$pageTotal = intval(($recordsTotal - 1) / $recordsPage) + 1;
		$pageCurrent = intval($this->getParam('currentPage'));

		if(empty($pageCurrent) or $pageCurrent < 0)
			$pageCurrent = 1;

		if($pageCurrent > $pageTotal) $pageCurrent = $pageTotal;

		$recordsStart = $pageCurrent * $recordsPage - $recordsPage;

		return array(
			'current' => $pageCurrent,
			'total' => $pageTotal,
			'start' => $recordsStart,
			'records' => $recordsPage,
                        'link' => $this->getParam('link')
		);
	}

	/**
	 * Set parameters
	 * @var $params(type: Array)
	 * @return $this
	**/
	public function setParams(Array $params) {
		$this->_params = array_merge($this->_params, $params);

		return $this;
	}

	/**
	 * Set parameter
	 * @var $params(type: string)
	 * @var $value(type: string)
	 * @return $this
	**/
	public function setParam($param, $value) {
		$this->_params[$param] = $value;

		return $this;
	}

	/**
	 * Get parameter
	 * @var $params(type: string)
	 * @return string|NULL
	**/
	public function getParam($param) {
		if (array_key_exists($param, $this->_params)) {
			return $this->_params[$param];
		}

		return NULL;
	}

	/**
	 * Get pagination data
	 * @return Array
	**/
	public function get() {
		$calc = $this->_calc();

		return $calc;
	}
}