#!/bin/bash -x

#magento2 cache clean and upgrade.

function mage2CacheClearUpgrade {
	current_working_dir=$(pwd)
	#echo $current_working_dir
	#exit $?
	if [ -f $current_working_dir"/app/bootstrap.php" ] && [ -f $current_working_dir"/bin/magento" ] && [ -d $current_working_dir"/vendor" ]; then
		rm -rf $current_working_dir"/var/"$({cache,page_cache,generation,di})"/*"
		chmod a+w  $current_working_dir"/var/"{cache,page_cache,generation,di}"/" $current_working_dir"/pub/static/"
		$(which php) $current_working_dir"/bin/magento" setup:upgrade
		chmod a+w  $current_working_dir"/var/"{cache,page_cache,generation,di}"/" $current_working_dir"/pub/static/"
	else
		echo "Not a Magento2 Doc Root!"
		exit $?
	fi
}

echo eval {cache,page_cache,generation,di}
exit $?
#mage2CacheClearUpgrade
