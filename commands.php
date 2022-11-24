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
            ["option"=> "verbose", "arg"=> ""],
            ["option"=> "add-metadata", "arg"=> ""],
            ["option"=> "write-info-json", "arg"=> ""],
            ["option"=> "format", "arg"=> $GLOBALS['settings']['format']],
            ["option"=> "write-thumbnail", "arg"=> ""],
            ["option"=> "merge-output-format", "arg"=> "mp4"],
            ["option"=> "output", "arg"=> $GLOBALS['settings']['folder'].$GLOBALS['settings']['filename']],
            ["option"=> "proxy", "arg"=> NULL],
            ["option"=> "ffmpeg-location", "arg"=> NULL]
        ];
        $options_string = array_reduce($options, "buildOptions", "");
        $progress_log = escapeshellarg($GLOBALS['settings']['folder']."yt-dl-progress.log");
        
        // return "nohup /opt/homebrew/bin/youtube-dl $url $options_string 2>&1";
        return "nohup /opt/homebrew/bin/youtube-dl $url $options_string > $progress_log &";
    }

    // function getInfos($url) {
    //     $cmd = getYoutubeDLCMD($url).'-s --restrict-filenames --get-title --get-thumbnail --get-duration --get-format  2>&1';
    //     $data = array();
    //     // exec($cmd, $output, $ret);
    //     if($ret == 0) {
    //         $data['error'] = false; $i=0;
    //         if (strpos($output[$i], 'WARNING') !== false) $i++;
    //         $data['title'] = $output[$i];
    //         $data['tmb_url'] = $output[$i+1];
    //         $data['duration'] = $output[$i+2];
    //         $data['format'] = $output[$i+3];
    //     }
    //     else {
    //         $data['error'] = true;
    //         foreach($output as $out) {
    //             $data['message'] .= $out . '<br>'; 
    //         }
    //     }
    //     return $data;
    // }


    function downloadVideo($url) {
        $cmd = getYoutubeDLCMD($url);
        $data = array();    
        exec($cmd, $output, $ret);
        // $output = system($cmd, $ret);
        // $ret = 1;
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
            // foreach($output as $out) $data['message'] .= $out . '<br>'; 
        }
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