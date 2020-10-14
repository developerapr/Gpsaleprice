<?php
namespace Arunendra\Gpsaleprice\Ui\DataProvider\Product\Form\Modifier;

use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Form\Element\Input;
 use Magento\Catalog\Api\Data\ProductAttributeInterface;
 use Magento\Ui\Component\Form\Field;
 use Magento\Ui\Component\Form\Element\DataType\Number;
class UpdateTierPricing extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{
   /**
    * @var ArrayManager
    * @since 101.0.0
    */
   protected $arrayManager;

   /**
    * @var string
    * @since 101.0.0
    */
   protected $scopeName;

   /**
    * @var array
    * @since 101.0.0
    */
   protected $meta = [];

   /**
    * UpdateTierPricing constructor.
    * @param ArrayManager $arrayManager
    */
   public function __construct(
       ArrayManager $arrayManager
   ) {
       $this->arrayManager = $arrayManager;
   }

   /**
    * @param array $data
    * @return array
    * @since 100.1.0
    */
   public function modifyData(array $data)
   {
       // TODO: Implement modifyData() method.
       return $data;
   }

   /**
    * @param array $meta
    * @return array
    * @since 100.1.0
    */
   public function modifyMeta(array $meta)
   {
       // TODO: Implement modifyMeta() method.
       $this->meta = $meta;

       $this->customizeTierPrice();

       return $this->meta;
   }

   /**
    * @return $this
    */
   private function customizeTierPrice()
   {
       $tierPricePath = $this->arrayManager->findPath(
           ProductAttributeInterface::CODE_TIER_PRICE,
           $this->meta,
           null,
           'children'
       );

       if ($tierPricePath) {
           $this->meta = $this->arrayManager->merge(
               $tierPricePath,
               $this->meta,
               $this->getTierPriceStructure()
           );
       }

       return $this;
   }

   /**
    * @return array
    */
   private function getTierPriceStructure()
   {
       return [
           'children' => [
               'record' => [
                   'children' => [
                       'gp_sale_price' => [
                           'arguments' => [
                               'data' => [
                                   'config' => [
                                       'formElement' => Input::NAME,
                                       'componentType' => Field::NAME,
                                       'dataType' => Number::NAME,
                                       'label' => __('Sale Price'),
                                       'dataScope' => 'gp_sale_price',
                                       'sortOrder' => 100,
                                   ],
                               ],
                           ],
                       ],
                   ],
               ],
           ],
       ];
   }
}
