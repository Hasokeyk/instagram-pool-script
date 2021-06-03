<?php
    
    session_start();
    ob_start();
    
    global $keybmin;
    
    require "48186.php";
    
    use Keybmin\keybmin;
    $keybmin = new keybmin('insta_pool', [
        'test'    => true,
        'lang'    => 'tr_TR',
        'db_type' => 'mysqli',
        'db_name' => 'insta_pool',
        'db_user' => 'root',
        'db_pass' => '',
    ]);