<?php

namespace QuakeQuery;

class Socket implements SocketInterface
{
    private $socket;

    private function __construct($socket)
    {
        $this->socket = $socket;
    }

    public function __destruct()
    {
        $this->close();
    }

    public function close()
    {
        if ($this->socket !== null) {
            fclose($this->socket);
            $this->socket = null;
        }
    }

    public static function create($host, $port)
    {
        $socket = fsockopen("udp://{$host}", $port, $errno, $errstr);
        if (!$socket) {
            throw new SocketException($errstr, $errno);
        }
        return new self($socket);
    }

    private static function makeException($socket = null)
    {
        $errcode = socket_last_error($socket);
        $errstr = socket_strerror($errcode);
        return new SocketException($errstr, $errcode);
    }

    public function write($data)
    {
        $result = fwrite($this->socket, $data, strlen($data));
        if ($result === false) {
            throw self::makeException($this->socket);
        }
    }

    private function select($timeout)
    {
        $read = array($this->socket);
        $write = array();
        $except = array();
        $result = stream_select($read, $write, $except, $timeout);
        if ($result === false) {
            throw self::makeException($this->socket);
        }
        return $result;
    }

    public function read($timeout = 2)
    {
        $result = $this->select($timeout);
        if ($result > 0) {
            $data = fread($this->socket, 8192);
            if ($data === false) {
                throw self::makeException($this->socket);
            } elseif ($data === '') {
                throw new TimeoutException();
            } else {
                return $data;
            }
        } else {
            throw new TimeoutException();
        }
    }
}
