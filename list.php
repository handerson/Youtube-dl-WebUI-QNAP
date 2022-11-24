<!DOCTYPE html>
<?php
    require_once("config.php"); 
    require_once("sessions.php");
    require_once("utilities.php");
    require_once("commands.php");

    
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Youtube-dl WebUI - List of videos</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    <body >
        <div class="navbar navbar-default">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/index.php">Youtube-dl WebUI</a>
            </div>
            <div class="navbar-collapse  collapse navbar-responsive-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/index.php">Download</a></li>
                    <li class="active"><a href="<?php echo $listPage; ?>">List of videos</a></li>
                </ul>
            </div>
        </div>
        <div class="container">
        <div class="row">
<?php
if($security==0 || (isset($_SESSION['logged']) && $_SESSION['logged'] == 1))
{ 
?>
    <h2>List of available videos :</h2>

<?php if (isset($settings['popup']) && $settings['popup']['error']==false): ?>
    <div id="dialog_success" class="alert alert-success">
        <strong><?php echo $settings['popup']['message']; ?></strong>
    </div>
<?php endif; ?>

<?php if (isset($settings['popup']) && $settings['popup']['error']==true): ?>    
    <div id="dialog_err" class="alert alert-dismissable alert-danger">
        <strong><?php echo $settings['popup']['message']; ?></strong>
    </div>
<?php endif; ?>

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
            foreach(glob($folder."*") as $file)
            {
                $additional_data=processProcFile($file);
                if (isset($additional_data['deleted'])) continue;
                //print_r($additional_data);
                $filename = str_replace($folder, "", $file); // Need to fix accent problem with something like this : utf8_encode
                $dl_info = "";
                if (isset($additional_data['dl_string'])) $dl_info = " (".$additional_data['dl_string'].")";
                echo "<tr>"; //New line
                echo "<td height=\"30px\"><a href=\"$folder$filename\">$filename</a>$dl_info</td>"; //1st col
                echo "<td>".human_filesize(filesize($folder.$filename))."</td>"; //2nd col
                echo "<td><a href=\"".$listPage."?fileToDel=$filename\" class=\"text-danger\">Delete</a></td>"; //3rd col
                echo "</tr>"; //End line
            }
       
} 
else {
    echo '<div class="alert alert-danger"><strong>Access denied :</strong> You must sign in before !</div>';
} ?>
                    </tr>
                </tbody>
            </table>
            <br/>
            <a href="index.php">Back to download page</a>
        </div>
        </div><!-- End container -->
        <br>
        <footer>
            <div class="well text-center">
                <p></p>
            </div>
        </footer>
    </body>
</html>