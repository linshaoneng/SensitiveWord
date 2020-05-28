<?php
/**
 * created by linshaoneng
 * email: linshaoneng@sina.cn
 */
require_once "./vendor/autoload.php";

use linshaoneng\Sensitive\SensitiveWordTree;

$Sensitive = new SensitiveWordTree();

$badWords = ['发票','口罩'];
$Sensitive->addWordstoTree( $badWords );
$text1 = '发票，发票要吗？口罩也有';
$text2 = '团结友善';
print_r( $Sensitive->checkBadWords($text1));
print_r( $Sensitive->checkBadWords($text2));