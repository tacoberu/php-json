<?php
/**
 * @copyright 2016 Martin Takáč (http://martin.takac.name)
 * @license   https://opensource.org/licenses/MIT MIT
 */

namespace Taco\JSON;


/**
 * Serialize phpobject to literal
 * @author    Martin Takáč <martin@takac.name>
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
 * @author    Martin Takáč <martin@takac.name>
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
