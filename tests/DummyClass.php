<?php

namespace Tests;

class DummyClass
{
	private $property1;
	protected $property2;
	public $property3;

	public function __construct ($property1 = '1', $property2 = '2', $property3 = '3')
	{
		$this->property1 = $property1;
		$this->property2 = $property2;
		$this->property3 = $property3;
	}

	public function aPublicMethod ($salt = '')
	{
		return 'public method' . $salt;
	}

	public function getProperty1 ()
	{
		return $this->property1;
	}

	public function getProperty2 ()
	{
		return $this->property2;
	}

	protected function aProtectedMethod ($salt = '')
	{
		return 'protected method' . $salt;
	}

	private function aPrivateMethod ($salt = '')
	{
		return 'private method' . $salt;
	}
}
