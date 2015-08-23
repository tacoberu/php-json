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
 * @author     Martin Takáč (martin@takac.name)
 */

namespace Taco\JSON;


/**
 * Format by callback.
 */
class AdhocFormat implements Serializer
{

	private $encode, $decode;


	/**
	 * @param callback
	 * @param callback
	 */
	function __construct($encode = Null, $decode = Null)
	{
		$this->encode = $encode;
		$this->decode = $decode;
	}


	/**
	 * Returns the literal representation of a value.
	 *
	 * @param mixin $value
	 * @return literal
	 */
	function encode(Encoder $encoder, $value)
	{
		$l = $this->encode;
		return $l($encoder, $value);
	}

}
