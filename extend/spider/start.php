<?php
$url = '127.0.0.1';
$content = file_get_contents($url);
$content = json_decode($content, true);