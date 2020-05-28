# SensitiveWord
使用dfa算法检查是否包含敏感词
Using DFA algorithm to check whether sensitive words are included

使用方法 $badWords 为需要检查的敏感词

```php
$Sensitive = new \App\libraries\Sensitive\SensitiveWordTree();
$badWords = ['发票','口罩'];
$Sensitive->addWordstoTree( $badWords );
$text1 = '发票，发票要吗？口罩也有';
$text2 = '团结友善';
print_r( $Sensitive->checkBadWords($text1));
print_r( $Sensitive->checkBadWords($text2));
```
返回如下
```php
包含敏感词信息：
Array
(
    [0] => 发票
    [1] => 口罩
)
并无敏感词信息
Array(

)
```
