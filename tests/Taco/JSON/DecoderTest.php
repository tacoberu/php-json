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


require_once __dir__ . '/../../../vendor/autoload.php';
require_once __dir__ . '/Person.php';


use PHPUnit_Framework_TestCase;
use DateTime;


/**
 * @call phpunit DecoderTest.php
 */
class DecoderTest extends PHPUnit_Framework_TestCase
{


	function testInt()
	{
		$decoder = new Decoder([]);
		$this->assertSame(42, $decoder->decode('42'));
	}



	function testFloat()
	{
		$decoder = new Decoder([]);
		$this->assertSame(3.14, $decoder->decode('3.14'));
	}



	function testArray()
	{
		$decoder = new Decoder([]);
		$this->assertSame([42,3.1415], $decoder->decode('[42, 3.1415]'));
	}



	function testStruct()
	{
		$decoder = new Decoder([]);
		$this->assertEquals((object)['num' => 42, 'real' => 3.1415], $decoder->decode('{"num":42,"real":3.1415}'));
	}



	function testDateTime()
	{
		$map['DateTime'] = new DateTimeFormat();
		$decoder = new Decoder($map);
		$this->assertEquals(new DateTime("2015-08-23T13:46:37+02:00"), $decoder->decode('{"#t":"DateTime","#v":"2015-08-23T13:46:37+02:00"}'));
	}



	function testAdhocFormat()
	{
		$map['Taco.JSON.Person'] = new AdhocFormat(Null, function(Decoder $decoder, $literal) {
			if (isset($literal->age)) {
				return new Person($literal->name, $literal->surname, $literal->age);
			}
			return new Person($literal->name, $literal->surname);
		});
		$decoder = new Decoder($map);
		$this->assertEquals(new Person('John', 'Dee'), $decoder->decode('{"#t":"Taco.JSON.Person","#v":{"name":"John","surname":"Dee"}}'));
	}



	function testComplexTest()
	{
		$map['DateTime'] = new DateTimeFormat();
		$map['Taco.JSON.Person'] = new AdhocFormat(Null, function(Decoder $decoder, $literal) {
			if (isset($literal->age)) {
				return new Person($literal->name, $literal->surname, $literal->age);
			}
			return new Person($literal->name, $literal->surname);
		});
		$decoder = new Decoder($map);
		$this->assertEquals((object)[
						'num' => 42,
						'real' => 3.1415,
						'nums' => [1,2,3],
						'reals' => [1.1,2.0,3.6],
						'dates' => [
								new DateTime("2015-07-23T13:46:37+02:00"),
								new DateTime("2015-08-23T13:46:37+02:00")
								],
						'author' => new Person('John', 'Dee'),
						'editor' => new Person('Nicholas', 'Flamel', 412)
						],
				$decoder->decode('{'
				. '"num":42,"real":3.1415,"nums":[1,2,3],"reals":[1.1,2,3.6],'
				. '"dates":[{"#t":"DateTime","#v":"2015-07-23T13:46:37+02:00"},{"#t":"DateTime","#v":"2015-08-23T13:46:37+02:00"}],'
				. '"author":{"#t":"Taco.JSON.Person","#v":{"name":"John","surname":"Dee"}},'
				. '"editor":{"#t":"Taco.JSON.Person","#v":{"name":"Nicholas","surname":"Flamel","age":412}}'
				. '}'));
	}


}
