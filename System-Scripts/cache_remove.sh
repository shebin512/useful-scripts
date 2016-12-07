#!/bin/sh
#####################################################
## Author Shebin				    #
## Version 0.0.2		                                    #
## Purpose Delete cache and clear the Trash files   #
#####################################################

cd ~
cowthink -f elephant `fs quota`

echo "Removing Firefox cache..." && rm -rf ~/.mozilla/firefox/******.default/Cache/*;rm -rf ~/.cache/mozilla/firefox/****.default/Cache/ && echo "Removed Firefox cache.\n\r"

echo "Removing Google-chrome cache..." && rm -rf ~/.cache/google-chrome/Default/Cache/* && echo "Removed Google-chrome cache.\n\r"

echo "Removing Opera cache..." && rm -rf ~/.opera/cache/* && echo "Removed Opera cache.\n\r"

echo "Removing Trash files..." &&  rm -rf ~/.local/share/Trash/* && echo "Removed Trash files.\n\r"

echo `fs quota`
