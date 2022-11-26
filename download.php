<?php
require_once("config.php"); 
require_once("sessions.php");
require_once("utilities.php");

if(!authorized()){
    http_response_code(401);
    return;
}

if (!empty($_GET['file'])) {
    $fileName = basename($_GET['file']);
} else {
    http_response_code(400);
    return;
}

$filePath = validatePath($GLOBALS['settings']['folder'].$_GET['file']);
if (!file_exists($filePath)) {
    http_response_code(404);
    return;
}

$type = mime_content_type($fileName);
header('Content-Description: File Transfer');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Content-Disposition: attachment; filename="'.$fileName.'"' );
header("Content-type: $type");
header('Content-Length: ' . filesize($filePath));

readfile($filePath);
?>