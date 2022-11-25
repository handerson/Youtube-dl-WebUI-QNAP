<!DOCTYPE html>
<?php
    require_once("config.php"); 
    require_once("sessions.php");
    require_once("utilities.php");
    require_once("commands.php");

    $current_page = "Settings";

    function hasPassword(){
        return (
            (isset($_POST['password']) && !empty($_POST['password'])) ||
             (isset($settings['password']) && !empty($settings['password']))
        );
    }

    if (httpMethod('post') && authorized()) {
        if(isset($_POST['folder'])) $settings['folder']=$_POST['folder'];
        if(isset($_POST['format'])) $settings['format']=$_POST['format'];
        if(isset($_POST['filename'])) $settings['filename']=$_POST['filename'];
        if(isset($_POST['ffmpeg'])) $settings['ffmpeg']=$_POST['ffmpeg'];
        if(isset($_POST['downloadLogFolder'])) $settings['downloadLogFolder']=$_POST['downloadLogFolder'];
        if(isset($_POST['mergeOutputFormat'])) $settings['mergeOutputFormat']=$_POST['mergeOutputFormat'];
        if(isset($_POST['proxy'])) $settings['proxy']=$_POST['proxy'];
        if(isset($_POST['security']) && $_POST['security'] == 'yes'){
            $settings['security'] = 'yes';
        }else{
            $settings['security'] = '';
        }
        
        if((isset($settings['security']) && $settings['security'] == "yes")){
            if(isset($_POST['password']) && !empty($_POST['password'])) {
                $settings['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            } else {
                $settings['security'] = "";
                $errors["password"] = "Password must be set if Require Password is checked.";
            }
        }  else {
            $settings['password'] = "";
        }
        if(isset($_POST['proxy'])) $settings['proxy']=$_POST['proxy'];

        if(empty($errors)){
            $encodedjson = json_encode($settings, JSON_PRETTY_PRINT);

            file_put_contents($GLOBALS['config_file'], $encodedjson);
        }
    }
    
?>
<html>
    <?php include("includes/head.php") ?>
    <body >
        <?php include("includes/navigation.php") ?>
        <div class="container">
        <div class="row">
            <?php
            if(authorized()) { 
            ?>
                <form id="submit_form" class="form-horizontal" action="/settings.php" method="post">
                    <fieldset>
                        <div class="form-group">
                            <label for="folder" class="col-sm-2 control-label">Download Folder</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="folder" placeholder="/share/CACHEDEV1_DATA/Multimedia/Web Videos/" name="folder" value="<?php echo $settings["folder"]; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="format" class="col-sm-2 control-label">Format</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="folder" placeholder="bestvideo[height<=480]+bestaudio/best[height<=480]" name="format" value="<?php echo $settings["format"]; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="filename" class="col-sm-2 control-label">Filename Template</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="filename" placeholder="%(uploader)s [%(uploader_id)s]/%(title)s[%(id)s].%(ext)" name="filename" value="<?php echo $settings["filename"]; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ffmpeg" class="col-sm-2 control-label">ffmpeg Path</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="ffmpeg" placeholder="ffmpeg" name="ffmpeg" value="<?php echo $settings["ffmpeg"]; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="downloadLogFolder" class="col-sm-2 control-label">Download Log Folder</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="downloadLogFolder" placeholder="<?php echo $settings["folder"]; ?>" name="downloadLogFolder" value="<?php echo $settings["downloadLogFolder"]; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="proxy" class="col-sm-2 control-label">Proxy</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="proxy" placeholder="" name="proxy" value="<?php echo $settings["proxy"]; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="proxy" class="col-sm-2 control-label">Merge Output Format</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="mergeOutputFormat" placeholder="mp4" name="mergeOutputFormat" value="<?php echo $settings["mergeOutputFormat"]; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                    <input type="checkbox" name="security" value="yes" <?php if ((isset($settings['security']) && $settings['security'] == "yes")) echo "checked";?>> Require Password
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group <?php if (isset($errors["password"])) echo "has-error";?>">
                            <label for="inputPassword" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                            <input type="password" class="form-control" name="password" id="inputPassword" placeholder="Password">
                            <?php if (isset($errors["password"])) echo '<span class="help-block">'.$errors["password"].'</span>';?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">Update</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            <?php
            }
            else{
                echo '<div class="alert alert-danger"><strong>Access denied :</strong> You must sign in before !</div>';
            } ?>

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