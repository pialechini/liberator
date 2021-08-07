<?php

namespace Pialechini\Liberator;

use Closure;

class Liberator
{
	/**
	 * @var mixed
	 */
	protected $object;

	/**
	 * @var string
	 */
	protected $class;

	/**
	 * @var mixed
	 */
	protected $backup;

	/**
	 * Liberator constructor.
	 *
	 * @param mixed $object
	 */
	public function __construct ($object)
	{
		$this->object = $object;
		$this->class = get_class($object);
		$this->backup();
	}

	/**
	 * Call a method in the object context
	 *
	 * @param $method
	 * @param ...$arguments
	 *
	 * @return mixed
	 */
	public function call ($method, ...$arguments)
	{
		return $this->invokeClosure(function () use ($method, $arguments) {
			return $this->$method(...$arguments);
		});
	}

	/**
	 * Get a property
	 *
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function get ($property)
	{
		return $this->invokeClosure(function () use ($property) {
			return $this->$property;
		});
	}

	/**
	 * Restore original object and return it
	 */
	public function restore ()
	{
		$backup = $this->backup;
		$this->invokeClosure(function () use ($backup) {
			foreach (get_object_vars($this) as $key => $value)
			{
				$this->$key = $backup[ $key ];
			}
		});
	}

	/**
	 * Set a property
	 *
	 * @param string $property
	 * @param mixed  $value
	 */
	public function set ($property, $value)
	{
		$this->invokeClosure(function () use ($property, $value) {
			$this->$property = $value;
		});
	}

	/**
	 * Make a backup from current object
	 */
	protected function backup ()
	{
		$this->backup = $this->invokeClosure(function () {
			foreach (get_object_vars($this) as $key => $value)
			{
				$backup[ $key ] = is_object($value) ? clone $value : $value;
			}
			return $backup ?? [];
		});
	}

	/**
	 * Bind the given closure to the object
	 *
	 * @param Closure $closure
	 *
	 * @return Closure|false
	 */
	protected function bindClosure (Closure $closure)
	{
		return $closure->bindTo($this->object, $this->class);
	}

	/**
	 * Invoke the given closure in the object context
	 *
	 * @param Closure $closure
	 * @param         ...$arguments
	 *
	 * @return mixed
	 */
	protected function invokeClosure (Closure $closure, ...$arguments)
	{
		return $this->bindClosure($closure)(...$arguments);
	}
}
