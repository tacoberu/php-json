<?php
/**
 * @copyright 2016 Martin Takáč (http://martin.takac.name)
 * @license   https://opensource.org/licenses/MIT MIT
 */

namespace Taco\JSON;


/**
 * Format by callback.
 * @author    Martin Takáč <martin@takac.name>
 */
class AdhocFormat implements Serializer, Deserializer
{

	/**
	 * @var callback
	 */
	private $encode;

	/**
	 * @var callback
	 */
	private $decode;


	/**
	 * @param callback
	 * @param callback
	 */
	function __construct($encode = NULL, $decode = NULL)
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



	/**
	 * Returns the literal representation of a value.
	 *
	 * @param literal $literal
	 * @return mixin
	 */
	function decode(Decoder $decoder, $literal)
	{
		$l = $this->decode;
		return $l($decoder, $literal);
	}

}
