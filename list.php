<!DOCTYPE html>
<?php
    require_once("config.php"); 
    require_once("sessions.php");
    require_once("utilities.php");
    require_once("commands.php");
    $current_page = "List";

    
?>
<html>
    <?php include("includes/head.php") ?>
    <body >
        <?php include("includes/navigation.php") ?>
        <div class="container">
        <div class="row">
    <?php
    if(authorized())
    { 
    ?>
    <h2>List of available videos :</h2>

        <table class="table table-striped table-hover ">
            <thead>
                <tr>
                    <th style="min-width:800px; height:35px">Title</th>
                    <th style="min-width:80px">Size</th>
                    <th style="min-width:110px">Remove link</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <?php
                    $files=array();
                    foreach( new RecursiveIteratorIterator( new RecursiveDirectoryIterator($settings['folder'], RecursiveDirectoryIterator::KEY_AS_PATHNAME ), RecursiveIteratorIterator::CHILD_FIRST ) as $file => $info ) {
                        if( $info->isFile() && $info->isReadable() ){
                            $type = mime_content_type($info->getPathname());
                            if(preg_match("/video/i", $type)){
                                $path = $info->getRealPath();
                                $filename = $info->getFilename();
                                $relative_path = str_replace($settings['folder'], "", $path);
                                $deletion_warning = addslashes($filename);
                                
                                echo "<tr>\n"; //New line
                                echo "<td height=\"30px\"><a href=\"download.php?file=".urlencode($relative_path)."\">$filename</a></td>\n"; //1st col
                                echo "<td>".human_filesize($info->getSize())."</td>\n"; //2nd col
                                echo "<td><form class=\"form-horizontal\" method=\"post\" role=\"form\" action=\"list.php\" onsubmit=\"return confirm('Are you sure you want to delete $deletion_warning?');\"><input type=\"hidden\" name=\"_method\" value=\"delete\"><input type=\"hidden\" name=\"file\" value=\"$relative_path\"><button type=\"submit\" class=\"btn btn-xs btn-danger\">Delete</button></form></td>\n"; //3rd col
                                echo "</tr>\n"; //End line
                            }
                        }
                    }      
                } 
                else {
                    echo '<div class="alert alert-danger"><strong>Access denied :</strong> You must sign in before !</div>';
                } 
                ?>
                    </tr>
                </tbody>
            </table>
            <br/>
            <a href="index.php">Back to download page</a>
        </div>
        </div><!-- End container -->
        <?php include("includes/footer.php") ?>
    </body>
</html>