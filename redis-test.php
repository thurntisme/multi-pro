<?php
require 'vendor/autoload.php';

$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host' => 'redis', // Use the service name from docker-compose
    'port' => 6379,
]);

$redis->set('key', 'Hello, Redis from PHP!');
echo $redis->get('key'); // Output: Hello, Redis!
