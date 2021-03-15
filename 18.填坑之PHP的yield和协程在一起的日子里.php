<?php

$ch1 = curl_init();
// 这个地址中的php，我故意sleep了5秒钟，然后输出一坨json
curl_setopt( $ch1, CURLOPT_URL, "http://www.selfctrler.com/index.php/test/test1" );
curl_setopt( $ch1, CURLOPT_HEADER, 0 );
$mh = curl_multi_init();
curl_multi_add_handle( $mh, $ch1 );

function gen1( $mh, $ch1, &$stopFlag) {
    do {
        $mrc = curl_multi_exec( $mh, $running );
        // 请求发出后，让出cpu
        yield;
    } while( $running > 0 );
    $ret = curl_multi_getcontent( $ch1 );
    echo $ret.PHP_EOL;
    $stopFlag = true;
    return false;
}
function gen2(&$stopFlag) {
    $i = 1;
    do {
        sleep(1);
        echo "gen2 : {$i}".PHP_EOL;
        file_put_contents( "./yield.log", "gen2".$i . PHP_EOL, FILE_APPEND );
        $i++;
        yield;
    } while( !$stopFlag );

    return false;
}
$stopFlag = false;
$gen1 = gen1( $mh, $ch1,  $stopFlag);
$gen2 = gen2($stopFlag);
do {
    echo $gen1->current();
    echo $gen2->current();
    $gen1->next();
    $gen2->next();
} while ($gen1->valid() and $gen2->valid());
