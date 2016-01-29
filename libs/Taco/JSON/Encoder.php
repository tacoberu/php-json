<?php
/**
 * @copyright 2016 Martin Takáč (http://martin.takac.name)
 * @license   https://opensource.org/licenses/MIT MIT
 */

namespace Taco\JSON;

use RuntimeException,
	LogicException,
	ArrayAccess,
	Traversable;


/**
 * Returns the JSON representation of a value.
 * @author    Martin Takáč <martin@takac.name>
 */
class Encoder
{

	/**
	 * @var array of Serializer
	 */
	private $serializer = [];


	/**
	 * @param $dict List of Serializer by key as type name if format ns.ns.ns.class.
	 */
	function __construct($dict)
	{
		if ( ! is_array($dict) && ! $dict instanceof ArrayAccess) {
			throw new LogicException("Serializer dict must by array or ArrayAccess.");
		}
		$this->serializer[] = $dict;
		$this->serializer[] = [
				'stdClass' => new StdClassFormat,
				//~ 'array' => new ArrayFormat(),
				//~ 'scalar' => new ScalarFormat(),
				];
	}



	/**
	 * @param $dict List of Serializer by key as type name if format ns.ns.ns.class.
	 */
	function add($dict)
	{
		if ( ! is_array($dict) && ! $dict instanceof ArrayAccess) {
			throw new LogicException("Serializer dict must by array or ArrayAccess.");
		}
		$default = array_pop($this->serializer);
		$this->serializer[] = $dict;
		$this->serializer[] = $default;
		return $this;
	}



	/**
	 * Returns the JSON representation of a value.
	 *
	 * @param mixin $value
	 * @param int $options
	 * @param int $depth
	 * @return string
	 */
	function encode($value, $options = 0, $depth = 512)
	{
		return json_encode($this->toLiteral($value), $options, $depth);
	}



	/**
	 * @param string $type
	 * @param literal $value
	 * @return stdClass
	 */
	function makeDefinition($type, $value)
	{
		return (object) [
				'#t' => $type,
				'#v' => $value,
				];
	}



	/**
	 * Prepare literal mark of object or scalar
	 *
	 * @param mixin $value
	 * @return scalar | array | stdclass
	 */
	function toLiteral($value)
	{
		if (is_object($value)) {
			return $this->lookupSerializerFor($value)->encode($this, $value);
		}
		if (is_array($value) || $value instanceof Traversable) {
			foreach ($value as $k => $val) {
				$value[$k] = $this->toLiteral($val);
			}
		}
		return $value;
	}



	/**
	 * @param string Type name of object.
	 * @return Serializer
	 */
	private function lookupSerializerFor($value)
	{
		$type = strtr(get_class($value), '\\', '.');
		foreach ($this->serializer as $dict) {
			if (isset($dict[$type])) {
				$serializer = $dict[$type];
				break;
			}
		}

		if ( ! isset($serializer)) {
			throw new RuntimeException("Serializer for type: `$type' is not found.");
		}

		if ( ! $serializer instanceof Serializer) {
			throw new LogicException("Serializer for type: `$type' is not implemented of interface Serializer.");
		}

		return $serializer;
	}

}
