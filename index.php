<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once("config.php"); 
    require_once("sessions.php");
    require_once("utilities.php");
    require_once("commands.php");
    $current_page = "Download";


    if(isset($_POST['passwd']) && !empty($_POST['passwd'])) startSession($_POST['passwd']);
    if(isset($_GET['logout']) && $_GET['logout'] == 1) endSession();
?>
<!DOCTYPE html>
<html>
    <?php include("includes/head.php") ?>
    <body >
        <?php include("includes/navigation.php") ?>
        <div class="container">
            <h1>Download</h1>
            <?php if(authorized()) {  ?>
                <form id="submit_form" class="form-horizontal" method="post" action="/index.php">
                    <fieldset>
                        <div class="form-group">
                            <div class="col-lg-10">
                                <input class="form-control" id="url" name="url" placeholder="Link" type="text">
                            </div>
                            <div class="col-lg-2">
                            <button type="submit" class="btn btn-primary">Download</button>
                            </div>
                        </div>
                        
                    </fieldset>
                </form>
                <br>
                <?php destFolderExists($settings['folder']);?>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="panel panel-info">
                            <div class="panel-heading"><h3 class="panel-title">Info</h3></div>
                            <div class="panel-body">
                                <p>Download folder : <?php echo $settings['folder'] ;?></p>
                            </div>
                        </div>
                        <div id="dialog_loading" class="panel panel-info hidden">
                            <div class="panel-heading"><h3 class="panel-title">Progress</h3></div>
                            <div class="panel-body">
                                <div class="card">
                                    <div class="progress">
                                        <div id="progressbar" class="progress-bar bg-info" role="progressbar" aria-valuenow="0"
                                            aria-valuemin="0" aria-valuemax="100"><span id="progress-string"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="dialog_err" class="alert alert-dismissable alert-danger hidden">
                            <strong>An error occurred!</strong>Error message:<br>
                            <span id="dialog_err_msg"></span>
                        </div>
                        <div id="dialog_success" class="alert alert-success hidden">
                            <strong>Download succeeded!</strong> <a href="list.php" class="alert-link">Link to the video</a>.<br>
                            <span id="dialog_success_msg"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="panel panel-info">
                            <div class="panel-heading"><h3 class="panel-title">Help</h3></div>
                            <div class="panel-body">
                                <p><b>How does it work ?</b></p>
                                <p>Simply paste your video link in the field and click "Download"</p>
                                <p><b>With which sites does it works ?</b></p>
                                <p><a href="http://rg3.github.io/youtube-dl/supportedsites.html">Here</a> is the list of the supported sites</p>
                                <p><b>How can I download the video on my computer ?</b></p>
                                <p>Go to "List of videos", choose one, right click on the link and do "Save target as ..." </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <form class="form-horizontal" action="/index.php" method="POST" >
                    <fieldset>
                        <legend>You need to login first</legend>
                        <div class="form-group">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-4">
                                <input class="form-control" id="passwd" name="passwd" placeholder="Password" type="password">
                            </div>
                            <div class="col-lg-4"></div>
                        </div>
                    </fieldset>
                </form>
            <?php  } if(authorized()) echo '<p><a href="index.php?logout=1">Logout</a></p>'; ?>
        </div><!-- End container -->
        <footer>
            <div class="well text-center">
                <p></p>
            </div>
        </footer>
    </body>
<script>

    $(document).ready(function() {
        setInterval(function () {
                $.ajax({
                    url: 'get-progress.php',
                    success: function (data) {
                        if(data['type'] === 'waiting'){
                            $('#dialog_loading').addClass('hidden');
                            $('#dialog_err').addClass('hidden');
                        }else if(data['type'] === "converting"){
                            if(!$('#dialog_loading').hasClass('hidden')){ 
                                $("#dialog_success").removeClass('hidden');
                                $('#dialog_loading').addClass('hidden');
                                $('#dialog_err').addClass('hidden');
                                $('#dialog_success_msg').html(data['message']);
                            }
                        } else {
                            $('#progress-string').html(`${data["progress"]}%`);
                            $('#dialog_loading').removeClass('hidden');
                            $('#dialog_err').addClass('hidden');
                            $('#progressbar').attr('aria-valuenow', data).css('width', `${data["progress"]}%`);
                        };
                    },
                    error: function(data){
                        $('#dialog_err').removeClass('hidden');
                        $('#dialog_loading').addClass('hidden');
                        $('#dialog_err_msg').html(data['message']);
                    }
                });
            }, 2000);
    });
</script>
</html>
