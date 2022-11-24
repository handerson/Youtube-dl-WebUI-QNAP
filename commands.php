<?php
    function getVideoID($url) {
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);
        return $params['v'];
    }

    function getYoutubeDLCMD() {
        $proxy_cmd="";
        if (!empty($GLOBALS['settings']['proxy'])) $proxy_cmd = "--proxy ".$GLOBALS['settings']['proxy'];
        return 'youtube-dl '.$proxy_cmd.' --ffmpeg-location /opt/ffmpeg ';
    }

    function getInfos($url) {
        $cmd = getYoutubeDLCMD().'-s --restrict-filenames --get-title --get-thumbnail --get-duration --get-format ' . escapeshellarg($url) . ' 2>&1';
        $data = array();
        exec($cmd, $output, $ret);
        if($ret == 0) {
            $data['error'] = false; $i=0;
            if (strpos($output[$i], 'WARNING') !== false) $i++;
            $data['title'] = $output[$i];
            $data['tmb_url'] = $output[$i+1];
            $data['duration'] = $output[$i+2];
            $data['format'] = $output[$i+3];
        }
        else {
            $data['error'] = true;
            foreach($output as $out) {
                $data['message'] .= $out . '<br>'; 
            }
        }
        return $data;
    }


    function downloadVideo($url) {
        //$video_id = getVideoID($url);
        $cmd = 'youtube-dl ' . escapeshellarg($url) . ' --ffmpeg-location /opt/ffmpeg --add-metadata --no-playlist --write-info-json --format \'bestvideo[height<=480]+bestaudio/best[height<=480]\' --write-thumbnail --merge-output-format mp4 -o ' . escapeshellarg($GLOBALS['settings']['folder'].'%(uploader)s [%(uploader_id)s]/%(title)s[%(id)s].%(ext)s') ;
        //$cmd = getYoutubeDLCMD().' -f \'bestvideo[height<=480]+bestaudio/best[height<=480]\' --merge-output-format mp4  -o ' . escapeshellarg($GLOBALS['settings']['folder'].'%(title)s-%(uploader)s.%(ext)s') . ' ' . escapeshellarg($url) . ' > '.$GLOBALS['settings']['folder'].$video_id.'.proc &';
        //$cmd = getYoutubeDLCMD().' --merge-output-format mp4  -o ' . escapeshellarg($GLOBALS['settings']['folder'].'%(title)s-%(uploader)s.%(ext)s') . ' ' . escapeshellarg($url) . ' > '.$GLOBALS['settings']['folder'].$video_id.'.proc &';
        $data = array();    
        exec($cmd, $output, $ret);
        //$output[] = $cmd; $output[] = $video_id; $ret = 1;
        if($ret == 0)
        {
            $data['error'] = false;
        }
        else{
            $data['error'] = true;
            $data['ret'] = $ret;
            $data['message'] = "";
            $data['output'] = $output;
            $data['cmd'] = $cmd;
            foreach($output as $out) $data['message'] .= $out . '<br>'; 
        }
        return $data;
    }


    function commandsHandler() {
        if(isset($_GET['logout']) && $_GET['logout'] == 1) endSession();
        if(isset($_GET['url']) && !empty($_GET['url']) && (authorized()) )
        {
            $url = $_GET['url'];
            header('Content-type: application/json');
            if (isset($_GET['url'])) {
                echo json_encode(downloadVideo($url));
            };
            exit;
        }
        if(isset($_POST['file']) && strcasecmp($_POST['_method'], "delete") == 0 && authorized()) {
            $fileToDel = $_POST['file'];
            $data = array();    
            if(file_exists($GLOBALS['settings']['folder'].$fileToDel))
            {
                $type = mime_content_type($GLOBALS['settings']['folder'].$fileToDel);
                if(preg_match("/video/i", $type)){
                    if(unlink($GLOBALS['settings']['folder'].$fileToDel))
                    {
                        $data['error'] = false;
                        $data['message'] = "File deleted successfully.";
                    }
                    else{
                        $data['error'] = true;
                        $data['message'] = "Error in deleting file.";
                    }
                }
                else{
                    $data['error'] = true;
                    $data['message'] = "Only video files may be deleted.";
                }
                
            } else {
                $data['error'] = true;
                $data['message'] = "The file does not exists.";
            }
        }
    }

    commandsHandler();

?>