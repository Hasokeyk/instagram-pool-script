<?php
    
    //HATALARI GÖSTERME
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    //HATALARI GÖSTERME
    
    //YEREL SAAT
    date_default_timezone_set('Europe/Istanbul');
    //YEREL SAAT
    
    require "vendor/autoload.php";
    require "keyb/keybmin.php";
    
    //GEREKLİ DEĞİŞKENLER
    define('ROOT',(__DIR__));
    define('THEMEDIR',ROOT.'/themes/');
    define('KEYBDIR',ROOT.'/keyb/');
    define('UPLOADDIR',ROOT.'/uploads/');
    define('LANGDIR',ROOT.'/langs/');
    //GEREKLİ DEĞİŞKENLER