<?php

namespace QuakeQuery;

class Parser
{
    public function parsePlayer($str)
    {
        list($STATE_SCORE, $STATE_PING, $STATE_NAME) = array(1, 2, 3);
        list($score, $ping, $name) = array('', '', '');
        list($i, $len, $state) = array(0, strlen($str), $STATE_SCORE);
        while ($i < $len) {
            switch ($state) {
            case $STATE_SCORE:
                if ($str[$i] === ' ') {
                    $state = $STATE_PING;
                } else {
                    $score .= $str[$i];
                }
                $i += 1;
                break;
            case $STATE_PING:
                if ($str[$i] === ' ') {
                    $state = $STATE_NAME;
                } else {
                    $ping .= $str[$i];
                }
                $i += 1;
                break;
            case $STATE_NAME:
                if ($str[$i] !== '"') {
                    $name .= $str[$i];
                }
                $i += 1;
                break;
            }
        }
        return new Player((int)$score, (int)$ping, $name);
    }

    public function parseInfo($str)
    {
        list($STATE_KEY, $STATE_VALUE) = array(1, 2);
        list($key, $value, $info) = array('', '', array());
        $len = strlen($str);
        $i = 0;
        $state = $STATE_KEY;
        /* skip over first slash */
        if ($str[$i] === '\\') {
            $i += 1;
        }
        while ($i < $len) {
            switch ($state) {
            case $STATE_KEY:
                if ($str[$i] === '\\') {
                    $state = $STATE_VALUE;
                } else {
                    $key .= $str[$i];
                }
                $i += 1;
                break;
            case $STATE_VALUE:
                if ($str[$i] === '\\') {
                    $state = $STATE_KEY;
                    $info[$key] = $value;
                    $key = '';
                    $value = '';
                } else {
                    $value .= $str[$i];
                }
                $i += 1;
                break;
            }
        }
        $info[$key] = $value;
        return $info;
    }

    public function parseStatus($str)
    {
        $chunks = explode("\x0A", $str);
        /* remove header */
        array_shift($chunks);
        /* remove trailing chunk */
        array_pop($chunks);
        /* extract info chunk */
        $info_chunk = array_shift($chunks);
        /* remaing chunks are player data */
        $player_chunk = $chunks;
        $info = $this->parseInfo($info_chunk);
        $players = array();
        foreach ($player_chunk as $p) {
            $players[] = $this->parsePlayer($p);
        }
        return new Status($info, $players);
    }       
}
