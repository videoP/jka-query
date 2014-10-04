<?php

namespace QuakeQuery;

class Player
{
    public $score;
    public $ping;
    public $name;

    public function __construct($score, $ping, $name)
    {
        $this->score = $score;
        $this->ping = $ping;
        $this->name = $name;
    }
}
