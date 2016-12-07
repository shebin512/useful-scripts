#!/bin/bash 
# function to remove the magento cache.

function mageCacheClear {

	local current_dir=$(pwd);
	#echo $current_dir
	#exit

	if [[ -f "$current_dir/app/Mage.php" ]]; then
		#echo "Inside Mage Doc root"
		rm -vrf var/{cache,full_page_cache}/ media/{js,css}/
	else
		echo -e "Not inside A magento 1.X's document root!"
		exit $?
	fi

}

mageCacheClear
