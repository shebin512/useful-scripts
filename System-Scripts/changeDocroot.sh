#!/bin/bash

#echo -n "\nEnter the target Directory: "
read -p "Enter the target Directory (Absolute path to target): " targetDir

#read targetDir

if [[ -e $targetDir ]]&&[[ -d $targetDir ]]&&[[ ${targetDir:0:1} == "/" ]]

#if [[ $# -eq 1 ]] && [[ -d $1 ]] && [[ ${1:0:1} == "/" ]]
	then
#		targetDir="${1}"
		find /home/bob/ -mindepth 1 -maxdepth 1 -type l -name "public_html" -exec rm -fv {} \; && ln -sv $targetDir /home/bob/public_html && chown -h nobody:nogroup /home/bob/public_html && ls -ld /home/bob/public_html && ls -ld $targetDir
	else
		echo -e "\n\tNo target dir specified or target not absolute path to a directory.\n\n\tPlease check the target specified.\n"
#Use the format $0 <absolute path to dir>"
fi
