<?php namespace linshaoneng\Sensitive;

/**
 * linshaoneng
 * email: linshaoneng@sina.cn
 * Class LetterObject
 * @package Sensitive
 */
class LetterObject
{
	public $value;
	public $frequency;

	public function __construct($value)
	{
		$this->value     = $value;
		$this->frequency = 1;
	}
}