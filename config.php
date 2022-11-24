<?php 
    // Read the JSON file 
    $config_file = 'config.json';
    if(file_exists($config_file)){
        $json = file_get_contents($config_file);
    }else{
        $json = file_get_contents('config.json.example');
    }

    // Decode the JSON file
    $settings = json_decode($json, true);
?>
