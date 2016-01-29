<?php
/**
 * @copyright 2016 Martin Takáč (http://martin.takac.name)
 * @license   https://opensource.org/licenses/MIT MIT
 */

namespace Taco\JSON;

use DateTime;


/**
 * Format for DateTime.
 * @author    Martin Takáč <martin@takac.name>
 */
class DateTimeFormat implements Serializer, Deserializer
{

	/**
	 * Returns the literal representation of a value.
	 *
	 * @param mixin $value
	 * @return literal
	 */
	function encode(Encoder $encoder, $value)
	{
		return $encoder->makeDefinition('DateTime', $value->format('c'));
	}



	/**
	 * Returns the literal representation of a value.
	 *
	 * @param literal $literal
	 * @return mixin
	 */
	function decode(Decoder $decoder, $literal)
	{
		return new DateTime($literal);
	}

}
