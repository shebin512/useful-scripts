<?php
/**
* Apply catalogue rules via shell to improve performance.
*/

require_once 'app/Mage.php';
ini_set('display_errors', 1);
ini_set("memory_limit","11364M");
#Varien_Profiler::enable();

//print_r($argv);
if(count($argv)>1){

Mage::setIsDeveloperMode(true);
$time_start = microtime(true);
$premem=memory_get_usage(true);
Mage::app();
date_default_timezone_set('Asia/Calcutta');
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        try {
          Mage::getModel('catalogrule/rule')->applyRuleById($argv[1]);
            Mage::app()->removeCache('catalog_rules_dirty');
        $Report="Catalog Rules have been applied via cron job at ".date("d-m-y, H:i:s")."<br />".PHP_EOL;;
        } catch (Exception $e) {

                $Report="Catalog Rules failure when applied via cron job at ".date("d-m-y, H:i:s")."<br />".PHP_EOL;;
                $date=date("Y-m-d-H-i-s");
                $logname="catalog-rule-cron".$date.".log";
                Mage::log(print_r($e->getMessage()."\n".$e->getTraceAsString(),true),null,$logname);

        }

$postmem=memory_get_usage(true);
$time_end = microtime(true);

$tt=$time_end-$time_start;
$tmem=echo_readable_memory_usage($postmem-$premem);

echo "RULE ID ".$argv[1]."<br />".PHP_EOL;

$Report.="TOTAL EXECUTION TIME ".$tt."<br />".PHP_EOL;
$Report.= "PRE  MEM ".$premem."<br />".PHP_EOL;
$Report.= "POST MEM ".$postmem."<br />".PHP_EOL;
$Report.= "Total MEM Consumption ".$tmem."<br />".PHP_EOL;

echo $Report;
//$reciever = "balaraman.gunasekaran@borngroup.com";
//$cc=array('balaraman.gunasekaran@borngroup.com');
$reciever = "shebin.sheikh@embitel.com";
$cc = array('emb_test@mailinator.com');

$mail = new Zend_Mail();
    $mail->setFrom("cronjobs@aceturtle.com","Catalog Rules Cron" );
    $mail->addTo($reciever);
    foreach($cc as $c )
    {
        $mail->addCc($c);
    }
    $mail->setSubject("Catalog Rule Cron Status".date("d-m-y"));
    $mail->setBodyHtml($Report);
    $mail->send();

}else{
	echo "Please enter the rule ID as 3rd parameter!\n";
	exit;
}
    function echo_readable_memory_usage($mem_usage) {


        if ($mem_usage < 1024)
            return $mem_usage." bytes";
        elseif ($mem_usage < 1048576)
            return round($mem_usage/1024,2)." KB";
        else
            return round($mem_usage/1048576,2)."MB";


    }
