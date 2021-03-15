<?php

function yield_range( $start, $end ){
    while( $start <= $end ){
        $ret = yield $start;
        $start++;
        echo "yield receive : ".$ret.PHP_EOL;
    }
}
$generator = yield_range( 1, 10 );

foreach( $generator as $item ){
    $value = $generator->current();
    $generator->send( $value * 10 );
}