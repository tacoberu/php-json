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
 * Serialize phpobject to literal
 */
interface Serializer
{

	/**
	 * Returns the literal representation of a value.
	 *
	 * @param mixin $value
	 * @return literal
	 */
	function encode(Encoder $encoder, $value);

}


/**
 * Deserialize phpobject from literal.
 */
interface Deserializer
{

	/**
	 * Returns the literal representation of a value.
	 *
	 * @param literal $literal
	 * @return mixin
	 */
	function decode(Decoder $decoder, $literal);

}
