<?php

/**
 * To bulk add newly added attribiute to all the attribute sets present
 *
 * Author: Shebin Sheikh
 * Ref: http://www.hummingbirduk.com/bulk-add-attribute-to-attribute-sets-in-magento/
 * 
 */

$cmdline = php_sapi_name() === 'cli' ? 1 : 0;


if ($cmdline) {
   if ($argc < 4 ){
       exit("No arguments Passed\n\rUsage: ".$argv[0]." <attribute_code> <attribute_group> <sorting_order>\n\r");
   }else {
       $attribute_code = $argv[1];
       $attribute_group = $argv[2];
       $attribute_sort_order = $argv[3];
   }
}

if (!$cmdline && !empty($_GET)) {
    $attribute_code = trim($_GET['attribute_code']);
    $attribute_group = trim($_GET['attribute_group']);
    $attribute_sort_order = trim($_GET['sorting_order']);
    if ($attribute_code == '' || $attribute_group == '' || $attribute_sort_order == '') {
        exit (htmlentities('ERROR : Parameters missing...'). '<br>'.htmlentities('Usage: <base_url>/scripts/Add_Attribute_To_Attribute_Sets.php?attribute_code=<attribute_code>&attribute_group=<attribute_group>&sorting_order=<sorting_order>').'<br>');
    }
}

$fulldir = explode('scripts',dirname(__FILE__));
require_once  $fulldir[0].'/app/Mage.php';

umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$attSet = Mage::getModel('eav/entity_type')->getCollection()->addFieldToFilter('entity_type_code','catalog_product')->getFirstItem();
$attSetCollection = Mage::getModel('eav/entity_type')->load($attSet->getId())->getAttributeSetCollection();
$attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')
        ->setCodeFilter($attribute_code) //ATTRIBUTE CODE
        ->getFirstItem();
$attCode = $attributeInfo->getAttributeCode();
$attId = $attributeInfo->getId();
foreach ($attSetCollection as $a) {
	$set = Mage::getModel('eav/entity_attribute_set')->load($a->getId());
        $setId = $set->getId();
        $group = Mage::getModel('eav/entity_attribute_group')->getCollection()->addFieldToFilter('attribute_set_id',$setId)->addFieldToFilter('attribute_group_name', $attribute_group)->setOrder('attribute_group_id',ASC)->getFirstItem();//ATTRIBUTE GROUP NAME
        $groupId = $group->getId();
        $newItem = Mage::getModel('eav/entity_attribute');
        $newItem->setEntityTypeId($attSet->getId())
                  ->setAttributeSetId($setId)
                  ->setAttributeGroupId($groupId)
                  ->setAttributeId($attId)
                  ->setSortOrder($attribute_sort_order)//SORT ORDER
                  ->save();

        echo "Attribute ".$attribute_code." Added to Attribute Set ".$set->getAttributeSetName()." in Attribute Group ".$group->getAttributeGroupName()."<br>\n";
    }
