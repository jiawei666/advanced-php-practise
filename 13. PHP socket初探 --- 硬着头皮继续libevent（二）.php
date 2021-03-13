<?php

/**
 * event用法初体验
 */
//// 初始化一个EventConfig（舰岛），虽然是个仅用于演示的空配置
//$eventConfig = new EventConfig();
//// 根据EventConfig初始化一个EventBase（辽宁舰，根据舰岛配置下辽宁舰）
//$eventBase = new EventBase( $eventConfig );
//// 初始化一个定时器event（歼15，然后放到辽宁舰机库中）
//$timer = new Event( $eventBase, -1, Event::TIMEOUT, function(){
//    echo microtime( true )." : 歼15，滑跃，起飞！".PHP_EOL;
//} );
//// tick间隔为0.05秒钟，我们还可以改成0.5秒钟甚至0.001秒，也就是毫秒级定时器
//$tick = 0.01;
//// 将定时器event添加（将歼15拖到甲板加上弹射器）
//$timer->add( $tick );
//// eventBase进入loop状态（辽宁舰！走你！）
//$eventBase->loop();

/**
 * event进阶（接收处理信号）
 */
//// 依然是照例行事，尽管暂时没什么实际意义上的配置
//$eventConfig = new EventConfig();
//// 初始化eventBase
//$eventBase = new EventBase( $eventConfig );
//// 初始化event
//$event = new Event( $eventBase, SIGTERM, Event::SIGNAL |  Event::PERSIST, function(){
//    echo "signal term.".PHP_EOL;
//} );
//// 挂起event对象
//$event->add();
//// 进入循环
//echo "进入循环".PHP_EOL;
//$eventBase->loop();


/**
 * event进阶（i/o多路复用方法配置）
 */
//// 查看当前系统平台支持的IO多路复用的方法都有哪些？
//$method = Event::getSupportedMethods();
//print_r( $method );
//// 查看当前用的方法是哪一个？
//$eventBase = new EventBase();
//echo "当前event的方法是：".$eventBase->getMethod().PHP_EOL;
//// 跑了许久龙套的config这次也得真的露露手脚了
//$eventConfig = new EventConfig;
//// 避免使用方法kqueue
//$eventConfig->avoidMethod('kqueue');
//// 利用config初始化event base
//$eventBase = new EventBase( $eventConfig );
//echo "当前event的方法是：".$eventBase->getMethod().PHP_EOL;


/**
 * event进阶（config配置）
 */
$base = new EventBase();
echo "特性：".PHP_EOL;
$features = $base->getFeatures();
// 看不到这个判断条件的，请反思自己“位运算”相关欠缺
if( $features & EventConfig::FEATURE_ET ){
    echo "边缘触发".PHP_EOL;
}
if( $features & EventConfig::FEATURE_O1 ){
    echo "O1添加删除事件".PHP_EOL;
}
if( $features & EventConfig::FEATURE_FDS ){
    echo "任意文件描述符，不光socket".PHP_EOL;
}