<?php

function uuid2bin($string)
{
    return pack('H*', str_replace('-', '', $string));
}

function bin2uuid($binary)
{
    return preg_replace(
        '/([0-9a-f]{8})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{12})/',
        '$1-$2-$3-$4-$5',
        unpack('H*', $binary)[1]
    );
}

function bin2literal($binary)
{
    return '0x' . bin2hex($binary);
}

function uuid2literal($string)
{
    return '0x' . str_replace('-', '', $string);
}