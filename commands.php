<?php
    function getVideoID($url) {
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);
        return $params['v'];
    }

    function buildOptions($carry, $item) {
        if(isset($item["arg"])){
            if(empty($item["arg"])) {
                $carry = "$carry --".$item['option']." ";
            } else{
                $carry = "$carry --".$item['option']." ".escapeshellarg($item['arg'])." ";
            }
        }
        return $carry;
    }

    function getYoutubeDLCMD($url) {
        $url = escapeshellarg($url);
        $options = [
            ["option"=> "add-metadata", "arg"=> ""],
            ["option"=> "verbose", "arg"=> ""],
            ["option"=> "write-info-json", "arg"=> ""],
            ["option"=> "format", "arg"=> $GLOBALS['settings']['format']],
            ["option"=> "write-thumbnail", "arg"=> ""],
            ["option"=> "merge-output-format", "arg"=> (empty($GLOBALS['settings']['mergeOutputFormat']))  ? NULL : $GLOBALS['settings']['mergeOutputFormat']],
            ["option"=> "output", "arg"=> $GLOBALS['settings']['folder'].$GLOBALS['settings']['filename']],
            ["option"=> "proxy", "arg"=> (empty($GLOBALS['settings']['proxy']))  ? NULL : $GLOBALS['settings']['proxy']],
            ["option"=> "ffmpeg-location", "arg"=> (empty($GLOBALS['settings']['ffmpeg']))  ? NULL : $GLOBALS['settings']['ffmpeg']]
        ];
        $options_string = array_reduce($options, "buildOptions", "");
        $progress_log_folder =  (empty($GLOBALS['settings']['downloadLogFolder']))  ? $GLOBALS['settings']['folder'] : $GLOBALS['settings']['downloadLogFolder'];
        $progress_log = escapeshellarg($progress_log_folder."yt-dl-progress.log");
        return "nohup youtube-dl $url $options_string > $progress_log &";
    }

    function downloadVideo($url) {
        $cmd = getYoutubeDLCMD($url);
        $data = array();    
        exec($cmd, $output, $ret);
        if($ret == 0)
        {
            $data['error'] = false;
            $data['dowload'] = true;
            $data['cmd'] = $cmd;
        }
        else{
            $data['error'] = true;
            $data['ret'] = $ret;
            $data['message'] = "";
            $data['output'] = $output;
            $data['cmd'] = $cmd;
            foreach($output as $out) $data['message'] .= $out . '<br>'; 
        }
        print_r($cmd);
        var_error_log($output);
        var_error_log($data);
        return $data;
    }


    function commandsHandler() {
        if(isset($_GET['logout']) && $_GET['logout'] == 1) endSession();
        if(httpMethod('post') && isset($_POST['url']) && !empty($_POST['url']) && (authorized()) )
        {
            $url = $_POST['url'];
            if (isset($_POST['url'])) {
                downloadVideo($url);
            };
        }
        if(isset($_POST['file']) && httpMethod('delete') && authorized()) {
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