<?php
/**
 * @copyright 2016 Martin Takáč (http://martin.takac.name)
 * @license   https://opensource.org/licenses/MIT MIT
 */

namespace Taco\JSON;


/**
 * Format struct/stdClass.
 * @author    Martin Takáč <martin@takac.name>
 */
class StdClassFormat implements Serializer, Deserializer
{

	/**
	 * Returns the literal representation of a value.
	 *
	 * @param Encoder $encoder
	 * @param mixin $value
	 * @return literal
	 */
	function encode(Encoder $encoder, $value)
	{
		$value = (array) $value;
		foreach ($value as $k => $val) {
			$value[$k] = $encoder->toLiteral($val);
		}
		return (object) $value;
	}



	/**
	 * Returns the literal representation of a value.
	 *
	 * @param Decoder $decoder
	 * @param literal $literal
	 * @return mixin
	 */
	function decode(Decoder $decoder, $literal)
	{
		$literal = (array) $literal;
		foreach ($literal as $k => $val) {
			$literal[$k] = $decoder->fromLiteral($val);
		}
		return (object) $literal;
	}

}
