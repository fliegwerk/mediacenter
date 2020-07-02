<p align="center">
  <img height="300" src="./branding/logo/logo.svg" alt="logo of fliegwerk mediacenter">
</p>

# FMC - The fliegwerk mediacenter

A simple mediacenter based on pure PHP without Javascript using only current browser features

## Installation

You need:
- any computing device with up-to-date software and network access
- a working web server with PHP support
- some *video* files

1. Sort and tag your *videos* described in the [configuration section](#configuration).
2. Copy the php files in your http directory:
   ```
   # cp index.php config.php search.php main.css /path/to/http
   ```
3. Link your prepared *video* directory with the following command:
   ```
   # ln -s /path/to/videos /path/to/http/movies
   ```
4. Install for security reasons the available `.htaccess` file or if your web server do not support such files
   configure it with the contents of this file.
   The configuration prevents access from non-local networks.
5. Enable the web server to follow symlinks and configure PHP that it can access your prepared video directory.

## Usage

Simply enter the URI or IP address of your device serving the website.
Now you will see all available video files placed in the prepared directory.
You can use the search bar at the top to search for specific titles,
and the search will give you titles partial matched with your title, too.

## Configuration

### Directory structure
The *video* directory has the following structure:
```text
/path/to/videos/
├── My awesome title/
|   ├── artwork.jpg
|   ├── cover.jpg
|   ├── data.json
|   └── video.mp4
├── My next awesome title/
|   ├── artwork.jpg
|   ├── cover.jpg
|   ├── data.json
|   └── video.mp4
├── ...
```
In the root directory are one directory for each title named as you like.
In these directories there must be the following files:
- an `artwork.jpg` image in wide-format which will show before the video metadata loaded in the browser
- a `cover.jpg` image in upright-format which will show in the video list for this title
- a `data.json` metadata file which contains all important information for the title (see [Metadata format](#metadata-format))
- a `video.mp4` video file which actually delivers the video

### Metadata format
The `data.json` file for every title has the following format:
```json
{
  "title": "Your video title which is also the string to search for",
  "subtitle": "Nice subtitle to your video",
  "release": "The release of your video in ISO time-format e.g. 2009-10-01T06:58:00Z",
  "director": "Who directed your video?",
  "description": "A nice description placed below your video",
  "genre": [
    "genre",
    "of",
    "video"
  ],
  "copyright": "Some nice copyright information to your video"
}
```
All information will be parsed for every title and showed on the website.

## Useful tools

### Simple installer
If you do not want to copy files around you can use the simple installer:
```
# ./installer.sh
```

### Artwork compressor
If you have many titles with artwork and covers in raw size the browser have to download each image.
This is bandwidth consuming.
So you can use another simple tool that compresses all artwork and cover images to a more useful level.
Please install `imagemagick` with your favorite package manager before you use this tool.
Go to your prepared *video* directory and execute:
```
$ cd /path/to/video
$ /path/to/git/repo/tools/optimize-thumbnails
```
Or even better copy this tool to your local binary directory and make it executable:
```
# cp tools/optimize-thumbnails /usr/local/bin/
# chmod 755 /usr/local/bin/optimize-thumbnails
```
So you do not need the git repo at all.

The thumbnail optimizer runs over every directory in your current working directory and looks for `[folder]/artwork.jpg`
and `[folder]/cover.jpg` and compresses it to the given size.

No fear! The tool backups every image before compression and adds a `-full` to the filename,
for example `artwork.jpg -> artwork-full.jpg`.

If an image already has the target size no further compression will occur, and the tool will skip it.

After you added a title directory to your video directory and prepared it, simply call the tool
and only the new images will be compressed.

### Video file tagging
This tool automatically extracts information from a prepared video directory and imbeds them as mp4 atoms in the video file.

Please install:
- `node` for server side javascript,
- `imagemagick` for cover compression, 
- `AtomicParsley` for mp4 metadata editing

with your favorite package manager.

Copy the script to your local binary directory:
```
# cp tools/tag-video-file /usr/local/bin/
# chmod 755 /usr/local/bin/tag-video-file
```

After installation go into a prepared directory and execute:
```
$ cd /path/to/videos/my-awesome-title/
$ tag-video-file
```
Finished!

## Issues and Contributing

If you have any issues or suggestions, feel free to open an [issue](https://github.com/fliegwerk/mediacenter/issues)
or write us: <https://www.fliegwerk.com/contact>

## Project Information

This is a project by fliegwerk: <https://www.fliegwerk.com/>
