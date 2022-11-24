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
            <li <?php if ($current_page == "Download") echo 'class="active"';?>><a href="/index.php">Download</a></li>
            <li <?php if ($current_page == "List") echo 'class="active"';?>><a href="/list.php">List of videos</a></li>
            <li <?php if ($current_page == "Settings") echo 'class="active"';?>><a href="/settings.php">Settings</a></li>
        </ul>
    </div>
</div>