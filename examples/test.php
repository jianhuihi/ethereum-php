<?php

require_once __DIR__.'/../vendor/autoload.php';

// 实例化以太坊客户端
$client = new Ethereum\Client(
    // JSON RPC 地址
    'https://api.infura.io/v1/jsonrpc/ropsten',
    // 以太坊网络 ID
    3,
    // 节点账户的 Keystore
    '',
    // Keystore 的密码
    '',
    // 存储实例，用来保存一些状态值，可以通过实现 \Ethereum\StorageInterface 接口使用你自己的存储
    new \Ethereum\Storage
);

// 添加合约
$client->contracts
    ->add(
        // 合约别名
        'test_contract',
        // 合约地址
        '',
        // 合约 ABI
        ''
    );

// 监听一个事件，这里的事件名称是你在合约中定义的事件名称。注意，监听事件需要通过定时器执行 $client->synchronizer->sync() 方法来轮询以太坊节点
$client->contracts->test_contract->watch('Event1', function (\Ethereum\Types\Event $data) {
    var_dump($data);
});

// 调用合约中的的方法
var_dump($client->contracts->test_contract->call('test_function', 'test_arg_1', 'test_arg_2'));

// 如果你使用 Swoole，可以通过 Swoole 的定时器来来轮询
swoole_timer_tick(1000, function() use ($client) {
    $client->synchronizer->sync();
});

// 调用 JSON API
echo $client->eth()->protocolVersion();
echo $client->web3()->clientVersion();
echo $client->net()->version();
