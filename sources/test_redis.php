<?php
$redis = new Redis();
$redis->connect('web-redis', 6379);

$redis->set("mykey", "Hello Redis from PHP!");
echo $redis->get("mykey");
?>