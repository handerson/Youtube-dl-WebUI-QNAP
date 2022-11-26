# phpYoutubeDLWebUI

## Description
This is a small web interface for youtube-dl command. Designed to be used on a QNAP NAS, but should work on anywhere [youtube-dl](https://github.com/rg3/youtube-dl), [FFmpeg](https://www.qnapclub.eu/en/qpkg/379), and [PHP](https://www.php.net/) can run.

## Features
- Options can be set in the web ui:
    - download resolution/format (`--format`)
    - download folder
    - filename template (`--output`)
    - proxy (`--proxy`)
    - merge output format (`--merge-output-format`)
    - ffmpeg path (`--ffmpeg-location`)
- Download runs in background
- Videos can be deleted
- Videos can be downloaded from the server

## Requirements
- HTTP server that supports PHP
    - such as a QNAP NAS with web server enabled and Python and PHP
- [youtube-dl](https://github.com/rg3/youtube-dl)
- [FFmpeg](https://www.qnapclub.eu/en/qpkg/379)

## How to install?
### 1. Install youtube-dl
1. Download youtube-dl file the from the last release [here](https://github.com/ytdl-org/youtube-dl/releases/) 
2. Copy it in **/usr/bin** folder, give permissions with **chmod 777 youtube-dl**

### 2. Install ffmpeg
1. Download latest ffmpeg release [here](https://www.qnapclub.eu/en/qpkg/379) according your QNAP model
2. Install the **.qpkg** file manually with the **App Center**
3. You will find the new version of *ffmpeg* here: **/opt/ffmpeg**

### 3. Install webpages
1. Clone this repo in your web folder (ex: /share/Web/youtube-dl).
2. Create the video folder and check the permissions.
3. Access your page (ie: http://(my_nas_IP)/youtube-dl/index.php)

### 4. Configuration
1. Open http://(my_nas_IP)/youtube-dl/index.php in your browser
2. Edit and Save settings

### 5. Environment Variables

#### ALLOWED_DIRECTORIES

`ALLOWED_DIRECTORIES` is an optional env var that limits the locations that this web app is allowed to write and delete. It must be a comma separated list of aboslute paths.

Examples:
- `/Share/Multimedia,/Share/Web/Logs`
- `/Share/Multimedia/Web`

## CSS Theme
[Flatly](http://bootswatch.com/flatly/)

## License
GPL v3 see LICENSE
