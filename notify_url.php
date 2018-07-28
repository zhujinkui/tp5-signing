<?php
header("Content-Type: Text/Html;Charset=UTF-8");
$data = file_get_contents('php://input', 'r');
var_dump($data);