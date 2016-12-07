<?php
/**
* Script to Export product details to csv file
* Reference: http://magento.stackexchange.com/a/5900
*/

error_reporting(E_ALL | E_STRICT);
define('MAGENTO_ROOT', getcwd());
$mageFilename = MAGENTO_ROOT . '/app/Mage.php';
require_once $mageFilename;
Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);
Mage::app();

//$_arg_count = count($argv);


$attributesToSelect = array();

//$attributesToSelect = getAttributesCodesByAttributeSetName("Flyingmachine");
//$attributesToSelect = "sku,name,qty";
$attributesToSelect = "sku,name,qty,price,special_price,rule_price";

$attributesToSelect = explode(',', $attributesToSelect);

//insertInCsv($attributesToSelect,'FM_catalogV2.csv');
insertInCsv($attributesToSelect,'FM_catalogV6.csv');

function insertInCsv($attributesToSelect,$filename){
	$products = Mage::getModel("catalog/product")->getCollection();
	//$products->addStoreFilter(2);
	//$products->addAttributeToFilter('attribute_set_id','16');
	//$products->addAttributeToFilter('website_ids',array('finset' => "2"));// 1 for kapkids , 2 for FM
	//$products->addWebsiteFilter('2');
	//$products->addAttributeToFilter('style_code',array('neq' => ""));
	//$products->addAttributeToFilter('status', 1);//optional for only enabled products
	//$products->addAttributeToFilter('visibility', 4);//optional for products only visible in catalog and search
	//$products->addAttributeToFilter('style_code', array('neq' => 'NULL' ));
	$products->addAttributeToFilter('attribute_set_id',array('eq'=>getAttributeSetId('Flyingmachine')));
	
	//To get STOCK
	$products->joinField('qty',
                 'cataloginventory/stock_item',
                 'qty',
                 'product_id=entity_id',
                 '{{table}}.stock_id=1',
                 'left');
    //To get Catalogrule price
    $products->joinField('rule_price',
						'catalogrule_product_price',
						'rule_price',
						'product_id=entity_id',null,
						'left');
    
	//Created at Time filter
	/*
	$from = date('Y-m-d H:i:s',strtotime("2015-07-01 00:00:00"));
	$to = date('Y-m-d H:i:s',strtotime("2015-12-21 00:00:00"));// 00:00:00";
	$products->addAttributeToFilter('created_at', array('from' => $from, 'to' => $to,'datetime'=>true));
	*/
	
	//Specific attribute filtering
    $products->addAttributeToSelect($attributesToSelect);
	//$products->addAttributeToSelect('*');

	// foreach ($attributesToSelect as $attribute) {
	// 	//echo $attribute."\n";
	// 	$products->addAttributeToSelect($attribute);
	// 	//echo camelCase("get_".$attribute)."\n";
	// }

	//$products->getSelect()->limit(25);

	$fp = fopen($filename, 'w');
	$csvHeader = $attributesToSelect;
	fputcsv($fp, $csvHeader,",");

	$insert_string = array();

	$attributeText = array(
					'visibility',
					'fm_color',
					'fm_size',
					'fm_type',
					'msrp_enabled',
					'msrp_display_actual_price_type',
					'tax_class_id'
				);

	foreach ($products as $product) {
		//print_r($product->getData('rule_price'));
		//$stock = Mage::getModel('cataloginventory/stock_item');
		//$rules = Mage::getResourceModel('catalogrule/rule_collection');
		foreach ($attributesToSelect as $attribute) {
			if (in_array($attribute, $attributeText)) {
				$attributeValue = $product->getAttributeText($attribute);
			} else {
				$getValue = camelCase("get_".$attribute);
	 			$attributeValue = $product->$getValue();
			}
			
	 		$insert_string[] = is_array($attributeValue)?implode(",", $attributeValue):$attributeValue;
		 }
		 $insert_string[]=
		 fputcsv($fp, $insert_string, ",");
		 //print_r($insert_string);
		 $insert_string = null;
	}

	fclose($fp);
}

function camelCase($str, array $noStrip = [])
{
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
        $str = trim($str);
        // uppercase the first character of each word
        $str = ucwords($str);
        $str = str_replace(" ", "", $str);
        $str = lcfirst($str);

        return $str;
}

function getAttributesCodesByAttributeSetName($attributeSetName){
	$attributeSetId = getAttributeSetId($attributeSetName);
	$attributes = Mage::getModel('catalog/product_attribute_api')->items($attributeSetId);
	$attributesTofetch = array();
	foreach($attributes as $_attribute){
    	//print_r($_attribute);
    	$attributesTofetch [] = $_attribute['code'];
	}

	//echo implode(',', $attributesTofetch);
	return implode(',', $attributesTofetch);
}

function getAttributeSetId($attributeSetName){
	$entityTypeId = Mage::getModel('eav/entity')
                ->setType('catalog_product')
                ->getTypeId();
	//$attributeSetName   = 'Default';
	$attributeSetId     = Mage::getModel('eav/entity_attribute_set')
                    ->getCollection()
                    ->setEntityTypeFilter($entityTypeId)
                    ->addFieldToFilter('attribute_set_name', $attributeSetName)
                    ->getFirstItem()
                    ->getAttributeSetId();
	//echo $attributeSetId;
	return $attributeSetId;
}
