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


/**
 * Sample object
 */
class Person
{
	public $name, $surname, $age;
	function __construct($name, $surname, $age = 0)
	{
		$this->name = $name;
		$this->surname = $surname;
		$this->age = $age;
	}
}

