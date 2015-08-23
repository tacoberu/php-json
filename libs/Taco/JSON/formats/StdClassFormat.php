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
 * Format struct/stdClass.
 */
class StdClassFormat implements Serializer, Deserializer
{

	/**
	 * Returns the literal representation of a value.
	 *
	 * @param mixin $value
	 * @return literal
	 */
	function encode(Encoder $encoder, $value)
	{
		$value = (array)$value;
		foreach ($value as $k => $val) {
			$value[$k] = $encoder->toLiteral($val);
		}
		return (object)$value;
	}


	/**
	 * Returns the literal representation of a value.
	 *
	 * @param literal $literal
	 * @return mixin
	 */
	function decode(Decoder $decoder, $literal)
	{
		$literal = (array)$literal;
		foreach ($literal as $k => $val) {
			$literal[$k] = $decoder->fromLiteral($val);
		}
		return (object)$literal;
	}

}