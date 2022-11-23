<?php 

/*** Config file ***/

//Rename it only if you change index.php to downloader.php for example
$mainPage = "index.php";

// -> with "/" <- at the end. Directory where you videos are downloaded
$GLOBALS['folder'] = "/share/CACHEDEV1_DATA/Multimedia/Web Videos/"; 

//Empty if dont need it
//$GLOBALS['proxy'] = "localhost:7890"; 

//Rename it only if you change list.php to myvideos.php for example
$GLOBALS['listPage'] = "list.php";

// Enable password to access the panel
// 1 -> enable 0 -> disable
$GLOBALS['security'] = 0; 

// PHP::md5(); You can use md5.php to generate an other one
// default : root
$secretPassword = "63a9f0ea7bb98050796b649e85481845";

?>
