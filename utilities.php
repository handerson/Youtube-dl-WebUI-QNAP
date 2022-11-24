<?php

// Test if destination folder exists
function destFolderExists($destFolder)
{
    if(!file_exists($destFolder))
    {
        echo '<div class="alert alert-danger">
                <strong>Error : </strong> Destination folder doesn\'t exist or is not found here. 
            </div>';
    }
}

// Convert bytes to a more user-friendly value
function human_filesize($bytes, $decimals = 0)
{
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

function httpMethod($method){
    $method = strtoupper($method);

    if(in_array($method, ['DELETE', 'PUT', 'POST', 'PATCH'])) {
        return $_SERVER['REQUEST_METHOD'] == $method || (isset($_POST['_method']) && strtoupper($_POST['_method']) == $method);
    } elseif($method == 'get'){
        return $_SERVER['REQUEST_METHOD'] == $method;
    } else{
        return false;
    }
}

function var_error_log( $object=null ){
    ob_start();                    // start buffer capture
    var_dump( $object );           // dump the values
    $contents = ob_get_contents(); // put the buffer into a variable
    ob_end_clean();                // end capture
    error_log( $contents );        // log contents of the result of var_dump( $object )
}
?>