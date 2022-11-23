<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once("config.php"); 
    require_once("sessions.php");
    require_once("utilities.php");
    require_once("commands.php");


    if(isset($_POST['passwd']) && !empty($_POST['passwd'])) startSession($_POST['passwd']);
    if(isset($_GET['logout']) && $_GET['logout'] == 1) endSession();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Youtube-dl WebUI</title>
        <link rel="stylesheet" href="css/bootstrap.css" media="screen">
        <link rel="stylesheet" href="css/bootswatch.min.css">
        <script src="js/jquery-3.6.0.min.js"></script>
        <style>
        .result-box {
            border: 1px solid #dadada;
            clear: both;
            padding: 0;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 28px;
            width: 100%;
            box-shadow: 0 1px 0 0 #e9e9e9;
            display: inline-block;
        }
        .result-box .info-box {
            border: none;
            float: left;
            font-family: Roboto,Arial,Helvetica,sans-serif;
            font-size: 17px;
            font-weight: 300;
            position: relative;
            padding: 18px 26px;
            width: 70%;
            min-height: 115px;
        }
        .result-box .meta {
            color: #3e3e3e;
            min-height: 46px;
        }
        .result-box .meta .row {
            color: #a5a5a5;
            margin-top: 0;
            margin-bottom: 4px;
        }
        .result-box .meta .title {
            color: #3e3e3e;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .result-box .link-box {
            font-weight: 400;
            margin-top: 32px;
            *zoom: 1;
        }
        .result-box .link-box .def-btn-box {
            color: #fff;
            background-color: #00a129;
            border: 2px solid #00a129;
            display: block;
            float: left;
            padding: 0 10px;
            margin: 0;
        }
        .result-box .link-box .drop-down-box {
            display: block;
            float: left;
            position: relative;
        }
        .result-box .link-box .def-btn-box a {
            color: #fff;
            display: inline-block;
            text-decoration: none;
        }
        .result-box .link-box .def-file-info {
            color: #a5a5a5;
            font-weight: 300;
            display: block;
            float: left;
        }
        .result-box .thumb-box {
            border: none;
            float: left;
            position: relative;
            margin-left: -1px;
            margin-top: -1px;
            margin-bottom: -1px;
            width: 30%;
            padding: 0;
        }
        .thumb-box, .thumb-box {
            overflow: hidden;
            position: relative;
        }
        .result-box.video .thumb-box {
            background-color: #222;
        }
        .thumb-box .thumb {
            display: block;
            z-index: 1;
        }
        .thumb {
            background-size: cover;
            background-repeat: no-repeat;
            background-position: 50% 50%;
            height: 150px;
        }
        </style>
    </head>
    <body>
        <div class="navbar navbar-default">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo $mainPage; ?>">Youtube-dl WebUI</a>
            </div>
            <div class="navbar-collapse collapse navbar-responsive-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="<?php echo $mainPage; ?>">Download</a></li>
                    <!--li><a href="<?php echo $GLOBALS['listPage']; ?>">List of videos</a></li-->
                </ul>
            </div>
        </div>
        <div class="container">
            <h1>Download</h1>
<?php

    if($GLOBALS['security']==0 || (isset($_SESSION['logged']) && $_SESSION['logged'] == 1)) { 
?>
            <form id="submit_form" class="form-horizontal" action="#">
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

            <div class="row">
                <div class="col-lg-10">

                <div id="dialog_success" class="alert alert-success">
                    <strong>Download succeed !</strong> <a href="list.php" class="alert-link">Link to the video</a>.
                </div>

                <div id="dialog_err" class="alert alert-dismissable alert-danger">
                    <strong>Oh snap!</strong> Something went wrong. Error message:<br>
                    <span id="dialog_err_msg"></span>
                </div>

                <div id="dialog_preview" class="result-box video">
                    <div class="thumb-box"><div class="thumb"></div></div>
                    <div class="info-box">
                        <div class="meta">
                            <div class="row title"></div>
                            <div class="row duration"></div>
                        </div>
                        <div class="link-box">
                            <div class="def-btn-box"><a class="link link-download subname download-icon" data-quality="720" data-type="mp4" href="#">Download</a></div>
                        </div>
                    </div>
                </div>
    

                <div id="dialog_loading" class="result-box video">
                    <div style="text-align: center;"><img src="img/loading.gif"></div>
                </div>

                </div>
            </div>

            <?php destFolderExists($GLOBALS['folder']);?>
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-info">
                        <div class="panel-heading"><h3 class="panel-title">Info</h3></div>
                        <div class="panel-body">
                            <p>Download folder : <?php echo $GLOBALS['folder'] ;?></p>
                        </div>
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
<?php
    }
    else{ ?>
        <form class="form-horizontal" action="<?php echo $mainPage; ?>" method="POST" >
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
<?php
        }
    if(isset($_SESSION['logged']) && $_SESSION['logged'] == 1) echo '<p><a href="index.php?logout=1">Logout</a></p>';
?>
        </div><!-- End container -->
        <footer>
            <div class="well text-center">
                <p></p>
            </div>
        </footer>
    </body>

<script>

$(document).ready(function() {
    $('#dialog_preview').hide(); 
    $('#dialog_loading').hide();  
    $('#dialog_success').hide();
    $('#dialog_err').hide();
 });

$("#submit_form").submit(function(e)
{
    var postData = $(this).serializeArray();
    var formURL = $(this).attr("action");
    $('#dialog_loading').show(); 
    $('#dialog_preview').hide(); 
    $('#dialog_success').hide();
    $('#dialog_err').hide();
    $.ajax({
        url : formURL,
        type: "GET",
        data : postData,
        dataType : "json",
        success:function(data, textStatus, jqXHR) {
            //alert(data.title + "," + this.url);
            if (data.error) {
                $('#dialog_err_msg').html(data.message);
                $('#dialog_err').show();     
            } else { 
                $('#dialog_preview .thumb-box .thumb').css('background-image', 'url(' + data.tmb_url + ')');
                $('#dialog_preview .title').text(data.title);
                $('#dialog_preview .duration').text(data.duration);
                $('#dialog_preview').show();  
            }
            $('#dialog_loading').hide();  
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            //if fails   
            alert('it didnt work');   
        }
    });
    e.preventDefault(); //STOP default action
    //e.unbind();
});

$( "a.download-icon" ).click(function() {
    $('#dialog_loading').show();  
    $('#dialog_success').hide();
    $('#dialog_err').hide();
    $.ajax({
        url : "#",
        type: "GET",
        data : {url: $("#url").val(), cmd: "dl"},
        dataType : "json",
        success:function(data, textStatus, jqXHR) {
            if (data.error) {
                $('#dialog_err_msg').html(data.message);
                $('#dialog_err').show();     
            } else { 
                $('#dialog_success').show(); 
            }
            $('#dialog_preview').hide();
            $('#dialog_loading').hide();
            $("#url").val("");
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            alert('it didnt work');   
        }
    });
    e.preventDefault(); //STOP default action
});
</script>
</html>
