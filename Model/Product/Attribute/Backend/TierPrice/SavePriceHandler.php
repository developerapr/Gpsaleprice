<?php
namespace Arunendra\Gpsaleprice\Model\Product\Attribute\Backend\TierPrice;
use Magento\Catalog\Model\Product\Attribute\Backend\TierPrice\SaveHandler;

class SavePriceHandler extends SaveHandler
{

   /**
    * Get additional tier price fields.
    *
    * @param array $objectArray
    * @return array
    */
   public function getAdditionalFields(array $objectArray): array
   {
       $percentageValue = $this->getPercentage($objectArray);

       return [
           'value' => $percentageValue ? null : $objectArray['price'],
           'percentage_value' => $percentageValue ?: null,
           'gp_sale_price'=> $this->getSecondaryUnit($objectArray),
       ];
   }

   /**
    * @param array $priceRow
    * @return mixed|null
    */
   public function getSecondaryUnit(array  $priceRow)
   {
       return isset($priceRow['gp_sale_price']) && !empty($priceRow['gp_sale_price'])
           ? $priceRow['gp_sale_price']
           : null;
   }
}