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
        <script type="text/javascript">var downloadStarted = <?php echo json_encode(downloadStarted()); ?>;</script>
        <?php include("includes/navigation.php") ?>
        <div class="container">
            <h1>Download</h1>
            <?php if(authorized()) {  ?>
                <form id="submit_form" class="form" method="post" action="index.php">
                    <div class="row">
                    <div class="form-group col-sm-9">
                        <label class="sr-only" for="url">URL to download</label>
                        <input class="form-control input-lg" id="url" name="url" placeholder="URL" type="text">
                    </div>
                        <div class="form-group col-sm-3">
                        <button type="submit" class="btn btn-lg">Download</button>
                    </div>
                    </div>
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
                        <div id="dialog_loading" class="panel panel-info <?php  if(!downloadStarted()) echo 'hidden'; ?>">
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
                                <p>Go to <a href="list.php">List of videos</a> and click on the title of the video you want to save" </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <form class="form-horizontal" role="form" action="index.php" method="POST">
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                        <input type="password" class="form-control" id="passwd" name="passwd" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Sign in</button>
                        </div>
                    </div>
                </form>
            <?php  } if(secured() && loggedIn()) echo '<p><a href="index.php?logout=1">Logout</a></p>'; ?>
        </div><!-- End container -->
        <?php include("includes/footer.php") ?>
    </body>
<script>
    function showError(message){
        $('#dialog_err').removeClass('hidden');
        $('#dialog_loading').addClass('hidden');
        $('#dialog_err_msg').html(message);
    };
    function getProgress(downloadStarted) {
        $.ajax({
            url: 'get-progress.php',
            success: function (data) {
                if(data['type'] === 'waiting' && !downloadStarted) {
                    $('#dialog_loading').addClass('hidden');
                    $('#dialog_err').addClass('hidden');
                } else if(data['type'] === "converting") {
                    if(!$('#dialog_loading').hasClass('hidden')){ 
                        $("#dialog_success").removeClass('hidden');
                        $('#dialog_loading').addClass('hidden');
                        $('#dialog_success_msg').html(data['message']);
                    }
                    $('#dialog_err').addClass('hidden');
                } else if(data['type'] === "download") {
                    $('#progress-string').html(`${data["progress"]}%`);
                    $('#dialog_loading').removeClass('hidden');
                    $('#dialog_err').addClass('hidden');
                    $('#progressbar').attr('aria-valuenow', data).css('width', `${data["progress"]}%`);
                } else if(downloadStarted) {
                    showError("Download could not be started");
                };
            },
            error: function(data){
                showError(data['message'])
            }
        });
    };
    $(document).ready(function() {
        setInterval(getProgress, 4000, downloadStarted);
    });
</script>
</html>
