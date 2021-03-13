<?php

/**
 * 管道通信
 */
//// 管道文件绝对路径
//$pipe_file = __DIR__ . DIRECTORY_SEPARATOR . 'test.pipe';
//// 如果这个文件存在，那么使用posix_mkfifo()的时候是返回false，否则，成功返回true
//if (!file_exists($pipe_file)) {
//    if (!posix_mkfifo($pipe_file, 0666)) {
//        exit('create pipe error.' . PHP_EOL);
//    }
//}
//// fork出一个子进程
//$pid = pcntl_fork();
//if ($pid < 0) {
//    exit('fork error' . PHP_EOL);
//} else if (0 == $pid) {
//    // 在子进程中
//    // 打开命名管道，并写入一段文本
//    sleep(2);
//    $file = fopen($pipe_file, "w");
//    fwrite($file, "helo world ipc.");
//    sleep(2);
//    fwrite($file, "helo aaa ipc.");
//    exit;
//} else if ($pid > 0) {
//    // 在父进程中
//    // 打开命名管道，然后读取文本
//    echo '111';
//    $content = file_get_contents($pipe_file);
//    echo '222';
////    $file = fopen($pipe_file, "r");
////     注意此处fread会被阻塞
////    $content = fread($file, 1024);
//    echo $content . PHP_EOL;
//    // 注意此处再次阻塞，等待回收子进程，避免僵尸进程
//    pcntl_wait($status);
//}


/**
 * 消息队列通信
 */
// 使用ftok创建一个键名，注意这个函数的第二个参数“需要一个字符的字符串”
/*
共享内存，消息队列，信号量它们三个都是找一个中间介质，来进行通信的，这种介质多的是。
就是怎么区分出来，就像唯一一个身份证来区分人一样。你随便来一个就行，就是因为这。
只要唯一就行，就想起来了文件的设备编号和节点，它是唯一的，但是直接用它来作识别好像不太好，不过可以用它来产生一个号。ftok()就出场了。
*/

$key = ftok( __DIR__, 'a' );
// 然后使用msg_get_queue创建一个消息队列
$queue = msg_get_queue( $key, 0666 );
// 使用msg_stat_queue函数可以查看这个消息队列的信息，而使用msg_set_queue函数则可以修改这些信息
//var_dump( msg_stat_queue( $queue ) );
// fork进程
$pid = pcntl_fork();
if( $pid < 0 ){
    exit( 'fork error'.PHP_EOL );
} else if( $pid > 0 ) {
    // 在父进程中
    // 使用msg_receive()函数获取消息
    msg_receive( $queue, 0, $msgtype, 1024, $message );
    echo $message.PHP_EOL;
    // 用完了记得清理删除消息队列
    msg_remove_queue( $queue );
    pcntl_wait( $status );
} else if( 0 == $pid ) {
    // 在子进程中
    // 向消息队列中写入消息
    // 使用msg_send()向消息队列中写入消息，具体可以参考文档内容
    msg_send( $queue, 1, "helloword" );
    exit;
}
