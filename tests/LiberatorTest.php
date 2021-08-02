<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Pialechini\Liberator\Liberator;

class LiberatorTest extends TestCase
{
	/**
	 * @test
	 * @dataProvider dummyProviderForCallWithArguments
	 */
	public function callCanAcceptMethodArguments ($object, $method, $assert_returns, $argument)
	{
		$liberator = new Liberator($object);
		$this->assertEquals($assert_returns, $liberator->call($method, $argument));
	}

	/**
	 * @test
	 * @dataProvider dummyProviderForCall
	 */
	public function callCanInvokeAnyMethodOnObject ($object, $method, $assert_returns)
	{
		$liberator = new Liberator($object);
		$this->assertEquals($assert_returns, $liberator->call($method));
	}

	public function dummyProviderForCall ()
	{
		return [
			[ new DummyClass(), 'aPrivateMethod', 'private method' ],
			[ new DummyClass(), 'aProtectedMethod', 'protected method' ],
			[ new DummyClass(), 'aPublicMethod', 'public method' ],
		];
	}

	public function dummyProviderForCallWithArguments ()
	{
		return [
			[ new DummyClass(), 'aPrivateMethod', 'private method salt :D', ' salt :D' ],
			[ new DummyClass(), 'aProtectedMethod', 'protected method salt :)', ' salt :)' ],
			[ new DummyClass(), 'aPublicMethod', 'public method salt :))', ' salt :))' ],
		];
	}

	public function dummyProviderForGet ()
	{
		return [
			[ new DummyClass(), 1, 2, 3 ],
			[ new DummyClass('a', 'b'), 'a', 'b', 3 ],
		];
	}

	public function dummyProviderForRestore ()
	{
		return [
			[ new DummyClass(), 'property1', '1', '3' ],
			[ new DummyClass('a', 'something'), 'property2', 'something', 'something_else' ],
		];
	}

	public function dummyProviderForSet ()
	{
		return [
			[ '1', '2', '3', new DummyClass(), 10, 20, 30 ],
			[ 'a', 'b', 'c', new DummyClass('a', 'b', 'c'), 'x', 'y', 'z' ],
		];
	}

	/**
	 * @test
	 * @dataProvider dummyProviderForRestore
	 */
	public function ensureRestoreMethodWorksFine ($object, $property, $original_value, $new_value)
	{
		// make sure property has its original value
		$liberator = new Liberator($object);
		$this->assertEquals($original_value, $liberator->get($property));

		// set new value and ensure it is applied on property
		$liberator->set($property, $new_value);
		$this->assertEquals($new_value, $liberator->get($property));

		// call restore() method and get the new object
		$liberator->restore();

		// assert liberator shows the original value of property
		$this->assertEquals($original_value, $liberator->get($property));

		// make a new liberator on restored object and assert this liberator shows the original value too
		$new_liberator = new Liberator($object);
		$this->assertEquals($original_value, $new_liberator->get($property));
	}

	/**
	 * @test
	 * @dataProvider dummyProviderForGet
	 */
	public function getCanBeCalledOnProperties ($object, $value1, $value2, $value3)
	{
		$liberator = new Liberator($object);
		$this->assertEquals($value1, $liberator->get('property1'));
		$this->assertEquals($value2, $liberator->get('property2'));
		$this->assertEquals($value3, $liberator->get('property3'));
	}

	/**
	 * @test
	 * @dataProvider dummyProviderForSet
	 */
	public function setCanBeCalledOnProperties ($original_value1, $original_value2, $original_value3, $object, $value1,
		$value2, $value3)
	{
		$liberator = new Liberator($object);
		$this->getCanBeCalledOnProperties($object, $original_value1, $original_value2, $original_value3);

		$liberator->set('property1', $value1);
		$liberator->set('property2', $value2);
		$liberator->set('property3', $value3);

		$this->getCanBeCalledOnProperties($object, $value1, $value2, $value3);
	}
}
