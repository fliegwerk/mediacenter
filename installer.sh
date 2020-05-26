#!/bin/sh

set -e

## installer for fliegwerk/mediacenter
## by fliegwerk
## (c) 2020. MIT License

INSTALL_FILES="index.php config.php search.php main.css"
HTACCESS=".htaccess"

[ "$(id -u)" -eq 0 ] || { printf "Root privileges required\n"; exit 1; }

printf "Path to http directory: "
read -r HTTP_PATH
[ -d "$HTTP_PATH" ] || { printf "Path to http directory not found\n"; exit 1; }

printf "Copy files\n"
cp $INSTALL_FILES "${HTTP_PATH}/"

printf "Path to video directory: "
read -r VIDEO_PATH
[ -d "$VIDEO_PATH" ] || { printf "Path to video directory not found\n"; exit 1; }

printf "Link video directory"
ln -s "$VIDEO_PATH" "${HTTP_PATH}/movies"

printf "Would you like to install the .htaccess file? (y/n) "
read -r CHOICE

case "$CHOICE" in
  y|Y)
    printf "Copy configuration\n"
    cp "$HTACCESS" "${HTTP_PATH}/"
    ;;
  *)
    printf "Skipping\n"
    ;;
esac

printf "Installation finished\n"
