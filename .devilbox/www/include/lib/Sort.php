<?php

namespace devilbox;

class Sort
{

	/**
	 * Default sort name
	 * @var string
	 */
	private $_default_sort;

	/**
	 * Default order name
	 * @var string
	 */
	private $_default_order;

	/**
	 * Array of allowed sort names
	 * @var string[]
	 */
	private $_allowedSorts;

	/**
	 * Array of allowed order names
	 * @var string[]
	 */
	private $_allowedOrders;

	/**
	 * Name of $_GET key for current sort
	 * @var string
	 */
	private $_GET_sort;

	/**
	 * Name of $_GET key for current order
	 * @var string
	 */
	private $_GET_order;


	/**
	 * Constructor
	 *
	 * @param mixed[] $defaults      Array of default sort/order values
	 * @param mixed[] $allowedSorts  Array of allowed sorts
	 * @param mixed[] $allowedOrders Array of allowed orders
	 * @param mixed[] $GET_sortKeys  Array of sort/order $_GET key names
	 */
	public function __construct($defaults, $allowedSorts, $allowedOrders, $GET_sortKeys)
	{
		// Default sort/order
		$this->_default_sort = $defaults['sort'];
		$this->_default_order = $defaults['order'];

		// Allowed sort/order array
		$this->_allowedSorts = $allowedSorts;
		$this->_allowedOrders = $allowedOrders;

		// $_GET keys for sort and order
		$this->_GET_sort = $GET_sortKeys['sort'];
		$this->_GET_order = $GET_sortKeys['order'];
	}


	/**
	 * Get active or default sort value.
	 *
	 * @return string
	 */
	public function getSort()
	{
		$sort = null;

		// Get current sort value from $_GET
		if (isset($_GET[$this->_GET_sort])) {
			$sort = $_GET[$this->_GET_sort];
		}

		// If sort is not allowed, reset to default
		if (!in_array($sort, $this->_allowedSorts)) {
			$sort = $this->_default_sort;
		}

		// All good, return sort
		return $sort;
	}

	/**
	 * Get active or default order value.
	 *
	 * @return string
	 */
	public function getOrder()
	{
		$order = null;

		// Get current order value from $_GET
		if (isset($_GET[$this->_GET_order])) {
			$order = $_GET[$this->_GET_order];
		}

		// If order is not allowed, reset to default
		if (!in_array($order, $this->_allowedOrders)) {
			$order = $this->_default_order;
		}

		// All good, return order
		return $order;
	}


	/**
	 * Are we using default sort/order?
	 *
	 * @param  string $sort  Sort value
	 * @param  string $order Order value
	 * @return boolean
	 */
	public function isDefault($sort, $order)
	{
		return ($this->_default_sort == $sort && $this->_default_order == $order);
	}
}
