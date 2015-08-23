<?php
/**
 * This file is part of the Taco Projects.
 *
 * Copyright (c) 2004, 2013 Martin Takáč (http://martin.takac.name)
 *
 * For the full copyright and license information, please view
 * the file LICENCE that was distributed with this source code.
 *
 * PHP version 5.3
 *
 * @author	 Martin Takáč (martin@takac.name)
 */

namespace Taco\JSON;


use ArrayAccess;


class SampleBankByArrayAccess implements ArrayAccess
{
	private $container = [];

	function __construct($container)
	{
		$this->container = $container;
	}

	function offsetSet($offset, $value)
	{}

	function offsetExists($offset)
	{
		return isset($this->container[$offset]);
	}

	function offsetUnset($offset)
	{}

	function offsetGet($offset)
	{
		return isset($this->container[$offset]) ? $this->container[$offset] : null;
	}
}

