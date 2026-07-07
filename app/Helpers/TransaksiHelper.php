<?php

if (!function_exists('hitung_biaya_admin')) {
    function hitung_biaya_admin($total_harga)
    {
        if ($total_harga <= 20000000) {
            return $total_harga * 0.005;
        }
        return $total_harga * 0.0075;
    }
}

if (!function_exists('hitung_diskon_kupon')) {
    function hitung_diskon_kupon($total_harga, $kupon_code)
    {
        $kupon = [
            'HEMAT' => 0.15,
            'SUPER' => 0.20,
        ];

        $kode = strtoupper(trim($kupon_code));
        
        if ($kode !== '' && isset($kupon[$kode])) {
            return $total_harga * $kupon[$kode];
        }

        return 0;
    }
}

if (!function_exists('hitung_cashback')) {
    function hitung_cashback($total_harga)
    {
        if ($total_harga > 10000000) {
            return $total_harga * 0.02;
        }

        return 0;
    }
}
