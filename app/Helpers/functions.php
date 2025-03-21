<?php

use Sqids\Sqids;

if (!function_exists('encode_id')) {
    /**
     * 编码id
     * @param int $id
     * @return string
     */
    function encode_id(int $id): string
    {
        $sqids = new Sqids(alphabet: env('SQIDS_ALPHABET'));
        return $sqids->encode([$id]);
    }
}

if (!function_exists('decode_id')) {
    /**
     * 解码id
     * @param string $hashId
     * @return int
     */
    function decode_id(string $hashId): int
    {
        $sqids = new Sqids(alphabet: env('SQIDS_ALPHABET'));
        $numbers = $sqids->decode($hashId);
        return $numbers[0] ?? 0;
    }
}
