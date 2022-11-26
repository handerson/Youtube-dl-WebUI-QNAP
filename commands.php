<?php
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

    function setOptionValue($item, $hasArgs = true){
        $value = (empty($item)  ? NULL : $item);
        
        if(isset($value)){
            return $hasArgs ?  $value : '';
        }else{
            return NULL;
        }
    }

    function getYoutubeDLCMD($url) {
        $url = escapeshellarg($url);
        $options = [
            ["option"=> "add-metadata", "arg"=> setOptionValue($GLOBALS['settings']['addMetadata'], false)],
            ["option"=> "write-info-json", "arg"=> setOptionValue($GLOBALS['settings']['writeInfoJson'], false)],
            ["option"=> "format", "arg"=> setOptionValue($GLOBALS['settings']['format'])],
            ["option"=> "write-thumbnail", "arg"=> setOptionValue($GLOBALS['settings']['writeThumbnail'], false)],
            ["option"=> "merge-output-format", "arg"=> setOptionValue($GLOBALS['settings']['mergeOutputFormat'])],
            ["option"=> "output", "arg"=> $GLOBALS['settings']['folder'].$GLOBALS['settings']['filename']],
            ["option"=> "proxy", "arg"=> setOptionValue($GLOBALS['settings']['proxy'])],
            ["option"=> "ffmpeg-location", "arg"=> setOptionValue($GLOBALS['settings']['ffmpeg'])]
        ];
        $options_string = array_reduce($options, "buildOptions", "");
        $progress_log_folder =  (empty($GLOBALS['settings']['downloadLogFolder'])) ? $GLOBALS['settings']['folder'] : $GLOBALS['settings']['downloadLogFolder'];
        $progress_log = escapeshellarg($progress_log_folder."yt-dl-progress.log");
        return "youtube-dl $url $options_string > $progress_log &";
    }

    function downloadVideo($url) {
        $cmd = getYoutubeDLCMD($url);
        $data = array();    
        $GLOBALS['download_attempted'] = true;
        exec($cmd, $output, $ret);
        if($ret == 0)
        {
            $data['error'] = false;
            $data['download'] = true;
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
            $GLOBALS['flash'] = array();
            $fileToDel = validatePath($GLOBALS['settings']['folder'].$_POST['file']);
            if(file_exists($fileToDel))
            {
                $type = mime_content_type($fileToDel);
                if(preg_match("/video/i", $type)){
                    if(unlink($fileToDel))
                    {
                        $GLOBALS['flash']['error'] = false;
                        $GLOBALS['flash']['message'] = "File deleted successfully.";
                    }
                    else{
                        $GLOBALS['flash']['error'] = true;
                        $GLOBALS['flash']['message'] = "Error in deleting file.";
                    }
                }
                else{
                    $GLOBALS['flash']['error'] = true;
                    $GLOBALS['flash']['message'] = "Only video files may be deleted.";
                }
                
            } else {
                $GLOBALS['flash']['error'] = true;
                $GLOBALS['flash']['message'] = "The file does not exists.";
            }
        }
    }
    commandsHandler();
?>