<?php namespace linshaoneng\Sensitive;

/**
 * linshaoneng
 * email: linshaoneng@sina.cn
 *
 * Class TreeNode
 * @package linshaoneng\Sensitive
 */
class TreeNode
{
	public $isEnd = 0;
	public $value = null;

	private $letterList = [];

	public function get($letter)
	{
		return isset($this->letterList[$letter]) ? $this->letterList[$letter] : null;
	}

	public function set($letter, $nextNode)
	{
		$letterTable               = LetterTable::instance();
		$letterObject              = $letterTable->get($letter);
		$nextNode->value           = $letterObject;
		$this->letterList[$letter] = $nextNode;
	}

	public function hasNext()
	{
		return !empty($this->letterList);
	}
}