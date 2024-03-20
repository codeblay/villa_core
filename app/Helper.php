<?php

if (! function_exists('rupiah')) {
    function rupiah(float $number) {
        echo "Rp " . number_format($number, 0, ",", ".");
    }
}