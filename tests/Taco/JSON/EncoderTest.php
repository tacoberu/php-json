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
require_once __dir__ . '/SampleBankByArrayAccess.php';


use PHPUnit_Framework_TestCase;
use DateTime;


/**
 * @call phpunit EncoderTest.php
 */
class EncoderTest extends PHPUnit_Framework_TestCase
{


	function testInt()
	{
		$encoder = new Encoder([]);
		$this->assertSame('42', $encoder->encode(42));
	}



	function testFloat()
	{
		$encoder = new Encoder([]);
		$this->assertSame('3.14', $encoder->encode(3.14));
	}



	function testBool()
	{
		$encoder = new Encoder([]);
		$this->assertSame('true', $encoder->encode(True));
		$this->assertSame('false', $encoder->encode(False));
	}



	function testString()
	{
		$encoder = new Encoder([]);
		$this->assertSame('"Lorem ipsum"', $encoder->encode('Lorem ipsum'));
	}



	function _testDateTimeFail()
	{
		$encoder = new Encoder([]);
		dump($encoder->encode(new DateTime()));
	}



	function testDateTime()
	{
		$map['DateTime'] = new DateTimeFormat();
		$encoder = new Encoder($map);
		$this->assertSame('{"#t":"DateTime","#v":"2015-08-23T13:46:37+02:00"}', $encoder->encode(new DateTime("2015-08-23T13:46:37+02:00") /*, JSON_PRETTY_PRINT*/));
	}



	function testAdhocFormat()
	{
		$map['Taco.JSON.Person'] = new AdhocFormat(function(Encoder $encoder, $value) {
			$data = (object) [
					'name' => $value->name,
					'surname' => $value->surname,
					];
			if (! empty($value->age)) {
				$data->age = $value->age;
			}
			return $encoder->makeDefinition('Taco.JSON.Person', $data);
		});
		$encoder = new Encoder($map);
		$this->assertSame('{"#t":"Taco.JSON.Person","#v":{"name":"John","surname":"Dee"}}', $encoder->encode(new Person('John', 'Dee')));
	}



	function testArray()
	{
		$encoder = new Encoder([]);
		$this->assertSame('[42,3.1415]', $encoder->encode([42, 3.1415]));
	}



	function testStruct()
	{
		$encoder = new Encoder([]);
		$this->assertSame('{"num":42,"real":3.1415}', $encoder->encode((object)['num' => 42, 'real' => 3.1415]));
	}



	function testComplexTest()
	{
		$map['DateTime'] = new DateTimeFormat();
		$map['Taco.JSON.Person'] = new AdhocFormat(function(Encoder $encoder, $value) {
			$data = (object) [
					'name' => $value->name,
					'surname' => $value->surname,
					];
			if (! empty($value->age)) {
				$data->age = $value->age;
			}
			return $encoder->makeDefinition('Taco.JSON.Person', $data);
		});
		$encoder = new Encoder($map);
		$this->assertEquals('{'
				. '"num":42,"real":3.1415,"nums":[1,2,3],"reals":[1.1,2,3.6],'
				. '"dates":[{"#t":"DateTime","#v":"2015-07-23T13:46:37+02:00"},{"#t":"DateTime","#v":"2015-08-23T13:46:37+02:00"}],'
				. '"author":{"#t":"Taco.JSON.Person","#v":{"name":"John","surname":"Dee"}},'
				. '"editor":{"#t":"Taco.JSON.Person","#v":{"name":"Nicholas","surname":"Flamel","age":412}}'
				. '}',
				$encoder->encode((object)[
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
						]));
	}



	function testArrayAccessBank()
	{
		$bank = new SampleBankByArrayAccess([
				'DateTime' => new DateTimeFormat()
				]);
		$encoder = new Encoder($bank);
		$this->assertSame('{"#t":"DateTime","#v":"2015-08-23T13:46:37+02:00"}', $encoder->encode(new DateTime("2015-08-23T13:46:37+02:00") /*, JSON_PRETTY_PRINT*/));
	}



	function testMatchInSeccondBank()
	{
		$bank = new SampleBankByArrayAccess([
				'DateTime' => new DateTimeFormat()
				]);
		$encoder = new Encoder($bank);
		$map = [];
		$map['Taco.JSON.Person'] = new AdhocFormat(function(Encoder $encoder, $value) {
			$data = (object) [
					'name' => $value->name,
					'surname' => $value->surname,
					];
			if (! empty($value->age)) {
				$data->age = $value->age;
			}
			return $encoder->makeDefinition('Taco.JSON.Person', $data);
		});
		$encoder->add($map);
		$this->assertSame('{"#t":"Taco.JSON.Person","#v":{"name":"John","surname":"Dee"}}', $encoder->encode(new Person('John', 'Dee')));
	}


}
