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


use RuntimeException,
	LogicException,
	ArrayAccess;


/**
 * Takes a JSON encoded string and converts it into a PHP variable.
 */
class Decoder
{

	private $deserializer = [];


	/**
	 * @param $dict List of Deserializer by key as type name if format ns.ns.ns.class.
	 */
	function __construct($dict)
	{
		if (!is_array($dict) && ! $dict instanceof ArrayAccess) {
			throw new LogicException("Serializer dict must by array or ArrayAccess.");
		}
		$this->deserializer[] = $dict;
		$this->deserializer[] = [
				'stdClass' => new StdClassFormat(),
				//~ 'array' => new ArrayFormat(),
				//~ 'scalar' => new ScalarFormat(),
				];
	}



	/**
	 * @param $dict List of Serializer by key as type name if format ns.ns.ns.class.
	 */
	function add($dict)
	{
		if (!is_array($dict) && ! $dict instanceof ArrayAccess) {
			throw new LogicException("Serializer dict must by array or ArrayAccess.");
		}
		$default = array_pop($this->deserializer);
		$this->deserializer[] = $dict;
		$this->deserializer[] = $default;
		return $this;
	}



	/**
	 * Takes a JSON encoded string and converts it into a PHP variable.
	 *
	 * @param string $value
	 * @param int $depth
	 * @param int $options
	 * @return mixin
	 */
	function decode($value, $depth = 512, $options = 0)
	{
		return $this->fromLiteral(json_decode($value, False, $depth, $options));
	}



	/**
	 * Prepare literal mark of object or scalar
	 *
	 * @param mixin $value
	 * @return scalar | array | stdclass
	 */
	function fromLiteral($value)
	{
		if (is_object($value)) {
			if (self::isObjDefinition($value)) {
				list($type, $val) = self::getObjDefinition($value);
				return $this->lookupDeserializerFor($type)->decode($this, $val);
			}
			else {
				return $this->lookupDeserializerFor('stdClass')->decode($this, $value);
			}
		}
		if (is_array($value)) {
			foreach ($value as $k => $val) {
				$value[$k] = $this->fromLiteral($val);
			}
		}
		return $value;
	}



	/**
	 * @param string Type name of object.
	 * @return Deserializer
	 */
	private function lookupDeserializerFor($type)
	{
		foreach ($this->deserializer as $dict) {
			if (isset($dict[$type])) {
				$deserializer = $dict[$type];
				break;
			}
		}

		if (! isset($deserializer)) {
			throw new RuntimeException("Deserializer for type: `$type' is not found.");
		}

		if (! $deserializer instanceof Deserializer) {
			throw new LogicException("Deserializer for type: `$type' is not implemented of interface Deserializer.");
		}
		return $deserializer;
	}



	private static function isObjDefinition($value)
	{
		return (array_keys((array)$value) == ['#t', '#v']);
	}



	/**
	 * @return [<type>, <value>]
	 */
	private static function getObjDefinition($value)
	{
		return [$value->{'#t'}, $value->{'#v'}];
	}

}
