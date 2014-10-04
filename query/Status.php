<?php

namespace QuakeQuery;

class Status
{
    public $info;
    public $players;

    public function __construct($info, $players)
    {
        $this->info = $info;
        $this->players = $players;
    }
}
