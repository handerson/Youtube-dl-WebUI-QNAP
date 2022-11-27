<?php
    require_once("config.php"); 
    require_once("sessions.php");
    require_once("utilities.php");

$log_file_path = $GLOBALS['settings']['downloadLogFolder'].DIRECTORY_SEPARATOR.'yt-dl-progress.log';

function getLastLine(string $filepath): string {  
    $line = '';  

    if(!realpath($filepath)) {
        errorResponse('failed', 'Log file does not exist. Please check settings.');
    };

    $f = fopen($filepath, 'r');
    $cursor = -1;
    fseek($f, $cursor, SEEK_END);
    $char = fgetc($f);
    while ($char === "\n" || $char === "\r") {
        fseek($f, $cursor--, SEEK_END);
        $char = fgetc($f);
    }
    while ($char !== false && $char !== "\n" && $char !== "\r") {
        $line = $char . $line;
        fseek($f, $cursor--, SEEK_END);
        $char = fgetc($f);
    }
    return $line;
}

function errorResponse($type = 'failed', $message = NULL, $code = 400){
    header('Content-type: application/json');
    http_response_code($code);
    $response['error'] = true;
    $response['type'] = $type;
    if(isset($message)) $response['message'] = $message;
    echo json_encode($response);
    exit;
}

header('Content-type: application/json');

if(allowedPath($log_file_path)){
    $last_line = getLastLine($log_file_path);
    if(empty($last_line)){
        $response['error'] = false;
        $response['type'] = 'waiting';
    } else {
        preg_match('/(\S+)\s+((\d+\.?\d?)%\D+(\d+\.?\d?\w+)|.+)/i', $last_line, $last_line_array);

        if(is_array($last_line_array)) {
            switch($last_line_array[1]){
                case "[download]":
                    $response['type'] = 'download';
                    $response['progress'] = $last_line_array[3];
                    $response['error'] = false;
                    $response['message'] = $last_line_array[2];
                    break;
                case '[ffmpeg]':
                    $response['type'] = 'converting';
                    $response['error'] = false;
                    $response['message'] = $last_line_array[2];
                    break;
                default:
                    $response['error'] = false;
                    $response['type'] = 'waiting';
            } 
        } else{
            errorResponse();
        }
    }
} else {
    errorResponse('failed', 'Invalid log location. Please update settings.');
}

echo json_encode($response);