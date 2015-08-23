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
 * Takes a JSON encoded string and converts it into a PHP variable.
 */
class Decoder
{

	private $deserializer = [];


	function __construct($deserializer)
	{
		$this->deserializer = array_merge([
				'stdClass' => new StdClassFormat(),
				//~ 'array' => new ArrayFormat(),
				//~ 'scalar' => new ScalarFormat(),
				], $deserializer);
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
	 * @return Serializer
	 */
	private function lookupDeserializerFor($type)
	{
		if (! isset($this->deserializer[$type])) {
			throw new RuntimeException("Deserializer for type: `$type' is not found.");
		}
		$deserializer = $this->deserializer[$type];
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
