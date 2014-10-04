<?php

require 'query/Parser.php';
require 'query/Player.php';
require 'query/Server.php';
require 'query/SocketInterface.php';
require 'query/Socket.php';
require 'query/SocketException.php';
require 'query/Status.php';
require 'query/TimeoutException.php';
require 'query/Util.php';

$server = new QuakeQuery\Server(getenv('192.223.26.151'), getenv('29070'));
$status = $server->getStatus();

echo 'Hostname: ', $status->info['sv_hostname'], PHP_EOL;
echo '=== players ===', PHP_EOL;

foreach ($status->players as $player) {
    echo $player->score, ' ', $player->ping, ' ', $player->name, PHP_EOL;
}
