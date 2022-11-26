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

    // Environment Variables

    // ALLOWED_DIRECTORIES
    // comma separated list of aboslute paths that this app is allowed 
    // to write and delete inside of 
    $allowed_dirs = explode(",", getenv("ALLOWED_DIRECTORIES"));
?>
