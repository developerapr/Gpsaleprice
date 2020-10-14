<?php
namespace Arunendra\Gpsaleprice\Model\ResourceModel\Product\Attribute\Backend;
class DataColumnUpdate extends \Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Tierprice
{
   /**
    * @param array $columns
    * @return array
    */
   protected function _loadPriceDataColumns($columns)
   {
       $columns = parent::_loadPriceDataColumns($columns);
       $columns['gp_sale_price'] = 'gp_sale_price';
       return $columns;
   }
}