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



use Nette\Utils,
	Nette\Reflection;
use RuntimeException,
	LogicException,
	Traversable;


/**
 * Returns the JSON representation of a value.
 */
class Encoder
{

	private $serializer = [];


	function __construct($serializer)
	{
		$this->serializer = array_merge([
				'stdClass' => new StdClassFormat(),
				//~ 'array' => new ArrayFormat(),
				//~ 'scalar' => new ScalarFormat(),
				], $serializer);
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
		if (! isset($this->serializer[$type])) {
			throw new RuntimeException("Serializer for type: `$type' is not found.");
		}
		$serializer = $this->serializer[$type];
		if (! $serializer instanceof Serializer) {
			throw new LogicException("Serializer for type: `$type' is not implemented of interface Serializer.");
		}
		return $serializer;
	}

}
