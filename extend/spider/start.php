<?php
/*$url = '127.0.0.1';
$content = file_get_contents($url);
$content = json_decode($content, true);

dump( $content);die;*/

$fp = @fopen("C:/www/test.txt", "a+");
fwrite($fp, "自动播报时间：\n" . date("Y-m-d H:i:s"));
fclose($fp);
