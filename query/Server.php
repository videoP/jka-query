<?php

namespace QuakeQuery;

class Server
{
    public $host;
    public $port;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function getStatus($timeout = 2, SocketInterface $socket = null)
    {
        if ($socket === null) {
            $socket = Socket::create($this->host, $this->port);
        }
        $socket->write("\xFF\xFF\xFF\xFFgetstatus\x0A");
        $data = $socket->read($timeout);
        $parser = new Parser();
        return $parser->parseStatus($data);
    }
}
