<?php

if (!function_exists('hashDecodeId')) {
    /**
     * Function decode a hash
     *
     * @param string $id
     * @return Integer
     */
    function hashDecodeId(
        string $identifier
    ) {
        return \Vinkla\Hashids\Facades\Hashids::decode($identifier);
    }
}

if (!function_exists('hashEncodeId')) {
    /**
     * Function hash encode id
     *
     * @param integer $id
     * @return String
     */
    function hashEncodeId(
        int $identifier
    ) {
        return \Vinkla\Hashids\Facades\Hashids::encode($identifier);
    }
}
