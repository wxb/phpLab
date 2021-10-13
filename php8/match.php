<?php

function testMatch($param)
{
    return match($param) {
        true => 'boolean',
        1 => 'int',
        1.1 => 'float',
    default=> 'unknown',
    };
}

echo testMatch(true);