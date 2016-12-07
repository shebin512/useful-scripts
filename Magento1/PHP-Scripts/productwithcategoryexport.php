<?php

/**
* Product Export with category details.
*/
    require_once 'app/Mage.php';
    Mage::app('fm');
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', 1);
    
    $productsData[0] = array(
    //'ProductId',
    'Productsku',
    'Categoryids',
    'Producttype' 
    );
    
    $i = 1;
    $file = fopen('FM_product_category.csv', 'a');
    fputcsv($file, $productsData[0]);
$productCollection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('type_id','configurable'); 
 //   $productCollection = Mage::getModel('catalog/product')->getCollection();
    
    foreach ($productCollection as $product) 
    {
        $storeIds = $product->getStoreIds();
        if($storeIds[0] == '2')
        {
  echo 'processing '. $product->getSku().'store Id:'.$storeIds[0].PHP_EOL;

             //$productsData[$i]['ProductId'] = $product->getId();
             $productsData[$i]['Productsku'] = $product->getSku();
             $categoryids = implode(";",$product->getCategoryIds());   
             $productsData[$i]['CategoryIds'] = $categoryids; 
             $productsData[$i]['Producttype'] = $product->getTypeId();          
             fputcsv($file, $productsData[$i]);
             $i++; 
        }   
    }
    echo "Report Generated Successfully";
?>
