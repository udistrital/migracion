<?php

$length=6;

$source = '';
$source .= 'abcdefghijklmnopqrstuvwxyz';
$source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$source .= '1234567890';
//$source .= '|@#~$%()=^*+[]{}-_';
if ($length > 0) {
    $rstr = "";
    $source = str_split($source, 1);
    for ($i = 1; $i <= $length; $i++) {
        mt_srand((double) microtime() * 1000000);
        $num = mt_rand(1, count($source));
        $rstr .= $source[$num - 1];
    }
}
?>
