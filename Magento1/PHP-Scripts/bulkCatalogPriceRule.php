<?php 
require_once 'app/Mage.php';
Mage::app();
try {
	Mage::getModel('categorypath/categorypath')->importCatalogPriceRules();
} catch (Exception $e) {
    Mage::printException($e);
}