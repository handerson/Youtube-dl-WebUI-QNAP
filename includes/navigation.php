<div class="navbar navbar-default">
    <div class="navbar-header">
        <a class="navbar-brand" href="index.php"><span>php</span>YoutubeDLWeb</a>
    </div>
    <div class="navbar-collapse">
        <ul class="nav navbar-nav">
            <li <?php if ($current_page == "Download") echo 'class="active"';?>><a href="index.php">Download</a></li>
            <li <?php if ($current_page == "List") echo 'class="active"';?>><a href="list.php">List of videos</a></li>
            <li <?php if ($current_page == "Settings") echo 'class="active"';?>><a href="settings.php">Settings</a></li>
        </ul>
    </div>
</div>

<?php if (isset($flash)): ?>
    <div class="alert <?php echo $flash["error"] ? "alert-danger" : "alert-success"; ?> alert-dismissable">
        <strong><?php echo $flash['message']; ?></strong>
    </div>
<?php endif; ?>