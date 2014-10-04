<?php

namespace QuakeQuery;

interface SocketInterface
{
    /**
     * @param string $data
     * @throws QuakeQuery\SocketException
     */
    function write($data);

    /**
     * @param int $timeout in seconds
     * @throws QuakeQuery\SocketException
     * @throws QuakeQuery\TimeoutException
     * @return string
     */
    function read($timeout = 2);
}
