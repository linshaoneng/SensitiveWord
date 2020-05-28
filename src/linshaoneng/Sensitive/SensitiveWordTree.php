<?php namespace linshaoneng\Sensitive;

/**
 * linshaoneng
 * email: linshaoneng@sina.cn
 *
 * 使用dfa算法检查是否包含敏感词
 *
 * $Sensitive = new \App\libraries\Sensitive\SensitiveWordTree();
 * $badWords = ['发票','口罩'];
 * $Sensitive->addWordstoTree( $badWords );
 * $text1 = '发票，发票要吗？口罩也有';
 * $text2 = '团结友善';
 * d( $Sensitive->checkBadWords($text1));
 * d( $Sensitive->checkBadWords($text2));
 *
 * Class SensitiveWordTree
 * @package App\libraries\Sensitive
 */
class SensitiveWordTree
{
	public $tree = null;

	/**
	 * 添加多个关键词到树
	 *
	 * @param array $words
	 *
	 * @return bool
	 */
	public function addWordstoTree(array $words)
	{
		if (!$words || count($words) == 0)
		{
			return false;
		}
		foreach ($words as $word)
		{
			$this->addWordToTree($word);
		}
	}

	/**
	 * 检查包含的关键字信息
	 *
	 * @param string $text
	 *
	 * @return array
	 */
	public function checkBadWords(string $text)
	{
		if (!$text || mb_strlen($text) == 0)
		{
			return [];
		}
		$result = $this->search($text);

		$words = array_column($result, 'word');
		$words = array_unique($words);
		sort($words);

		return $words;
	}

	/**
	 * 把单个关键词放到树里面
	 *
	 * @param $word
	 */
	public function addWordToTree($word)
	{
		$len = mb_strlen($word);
		if (is_null($this->tree))
		{
			$tree        = new TreeNode();
			$tree->isEnd = 0;
		}
		else
		{
			$tree = $this->tree;
		}
		$tmp = $tree;

		for ($i = 0; $i < $len; $i ++)
		{
			$nowLetter = mb_substr($word, $i, 1);

			$letterTable = LetterTable::instance();
			$letterTable->set($nowLetter);

			$nowTree = $tree->get($nowLetter);

			if (!is_null($nowTree))
			{
				$tree = $nowTree;
			}
			else
			{
				$newTree        = new TreeNode();
				$newTree->isEnd = 0;
				$tree->set($nowLetter, $newTree);
				$tree = $newTree;
			}

			if ($i == ($len - 1))
			{
				$tree->isEnd = 1;
			}
		}
		$this->tree = $tmp;
	}

	public function search($string)
	{
		$len         = mb_strlen($string);
		$result      = [];
		$stack       = [];
		$letterTable = LetterTable::instance();

		$tmpTree = $this->tree;

		for ($i = 0; $i < $len; $i ++)
		{
			$nowLetterA = mb_substr($string, $i, 1);
			if ($letterTable->isExists($nowLetterA) && ($i != ($len - 1)))
			{
				if (!is_null($tmpTree->get($nowLetterA)))
				{
					array_push($stack, $i);
				}
			}
			else
			{
				$end = $i;
				while (count($stack) > 0)
				{
					$curIndex = array_pop($stack);
					$start    = $curIndex;
					$tmpWord  = '';
					$tree     = $tmpTree;
					for ($j = $curIndex; $j < $end; $j ++)
					{
						$nowLetter = mb_substr($string, $j, 1);
						$nowTree   = $tree->get($nowLetter);

						if (!is_null($nowTree))
						{
							$tmpWord .= $nowLetter;
							if ($nowTree->isEnd)
							{
								array_push($result,
								           [
									           'word'        => $tmpWord,
									           'startOffset' => $start,
									           'endOffset'   => $j + 1,
								           ]);
								if ($nowTree->hasNext())
								{
									$tree = $nowTree;
								}
								else
								{
									$start   = $j;
									$tmpWord = '';
									$tree    = $tmpTree;
								}
							}
							else
							{
								$tree = $nowTree;
							}
						}
						else
						{
							$start   = $j;
							$tmpWord = '';
							$tree    = $tmpTree;
						}
					}
				}
			}
		}

		return $result;
	}
}