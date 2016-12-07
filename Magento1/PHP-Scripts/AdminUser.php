<?php 

/**
* Get Admin users and Roles in FM_KK (FlyingMachine and KapKids)
*/

require_once 'app/Mage.php'; 
ini_set('display_errors', 1);
ini_set("memory_limit","11364M");
#Varien_Profiler::enable();

Mage::setIsDeveloperMode(true);
Mage::app();
$adminUserModel = Mage::getModel('admin/user');
$userCollection = $adminUserModel->getCollection()->load(); 
//echo Mage::getBaseUrl()."\n";die();

$baseUrl;
try {
	$baseUrl = Mage::getBaseUrl();	
} catch (Exception $e) {
	$e->getTrace();
}
//$FM = FALSE;// stristr($baseUrl,"fm.")||stristr($baseUrl,"flyingmachine.")||stristr($baseUrl,"kapkids.")?TRUE:FALSE;
$FM = stristr($baseUrl,"fm.")||stristr($baseUrl,"flyingmachine.")||stristr($baseUrl,"kapkids.")?TRUE:FALSE;
//Mage::log($userCollection->getData());

$store = Mage::app()->getStore();
$name = $store->getName();
$date = date("d-m-Y");
$file = fopen("adminUsers_".$name."_".$date.".csv", "w");


foreach ($userCollection as $key => $adminUser) {
    #print_r($adminUser);die();
    $user= array();
	# code...
	$user[] = $adminUser->getData('username');
        $user[] = $adminUser->getData('email');
	$user[] = $adminUser->getRole()->getData('role_name');
	//echo $adminUser->getRole()->getData('role_type')."\n";
	//echo $adminUser->getRole()->getData('resource_id')."\n";

	#Additional data fetch for FM_KK
        //print_r( $adminUser->getRole());
          //      die();
        if($FM){
            $user[] =$adminUser->getRole()->getData('gws_websites');
            $user[] =$adminUser->getRole()->getData('gws_is_all');
            //echo $adminUser->getRole()->getData('gws_store_groups');
        }
        
        if($adminUser->getData('is_active')){
            $user[] = "active";
        }else{
            $user[] = "inactive";
        }
        //echo "\n";
        fputcsv($file, $user);
}
fclose($file);
