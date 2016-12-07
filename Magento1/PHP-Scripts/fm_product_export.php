<?php

/**
* FlyingMachine product export.
*/
    require_once 'app/Mage.php';
    Mage::app('fm');
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', 1);

    $productsData[0] = array(
    'ProductId',
    'Productsku',
    'Categoryids'
    );

    $i = 1;
    $file = fopen('FM_configurable_product_category.csv', 'a');
    fputcsv($file, $productsData[0]);
    //$store_id = 2;
    $productCollection = Mage::getModel('catalog/product')->getCollection();
    
    foreach ($productCollection as $product) 
    {
        $storeIds = $product->getStoreIds();
        if($storeIds[0] == '2')
        {               
            $aproducts = '';
            if($product->getTypeId() == "configurable"):
               // $aproducts = getConfigurableAssociatedProducts($product);
                $productsData[$i]['ProductId'] = $product->getId();
                $productsData[$i]['Productsku'] = $product->getSku();
                $productsData[$i]['Categoryids'] = $product->getCategoryIds();
                       
                fputcsv($file, $productsData[$i]);
                $i++; 
            endif;
             
        } 
    }
    echo 'Exported Product Datas Successfully';
    function getConfigurableAssociatedProducts($product)
    {
        $conf = Mage::getModel('catalog/product_type_configurable')->setProduct($product);
        $cassociated = array();
        $simple_collection = $conf->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();
        foreach($simple_collection as $simple_product){
            array_push($cassociated,$simple_product->getSku());
        }
        $cassociated = implode(";", $cassociated);
        return $cassociated; 
        
    }

    ?>
