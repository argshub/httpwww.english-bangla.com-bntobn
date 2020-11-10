<?php
$data = file_get_contents("php://input");
$file = fopen("btob.txt", "a");
fwrite($file, $data . "\n");