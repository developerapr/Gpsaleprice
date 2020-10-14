<?php
namespace Arunendra\Gpsaleprice\Plugin;

class PluginPriceHandler 
{
    protected $_resource;
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository,      
        \Magento\Framework\EntityManager\MetadataPool $metadataPool,
        \Magento\Framework\App\ResourceConnection $resource      
    ) {      
        $this->storeManager = $storeManager;
        $this->attributeRepository = $attributeRepository;   
        $this->metadataPoll = $metadataPool;
        $this->_resource = $resource;     
    }
    public function afterExecute(\Magento\Catalog\Model\Product\Attribute\Backend\TierPrice\UpdateHandler $subject,$result,$entity, $arguments = [])
    {
        $attribute = $this->attributeRepository->get('tier_price');
        $priceRows = $entity->getData($attribute->getName());
       
        $websiteId = (int)$this->storeManager->getStore($entity->getStoreId())->getWebsiteId();
        $isGlobal = $attribute->isScopeGlobal() || $websiteId === 0;
        $identifierField = $this->metadataPoll->getMetadata(\Magento\Catalog\Api\Data\ProductInterface::class)->getLinkField();
        $productId = (int)$entity->getData($identifierField);  
       
       /* if(count($priceRows)){
            foreach ($priceRows as $value) {     
                if(!empty($value['price_id']) ){
                    $this->SaveVale($value['gp_sale_price'],$value['price_id']);   
                }else{
                   # echo '<pre>'; print_r($value); die;
                }                                         
            }
        }*/
        return $result;
    }
    private function SaveVale($value,$valueId){
        $connection = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
         if(!empty($valueId) && !empty($value)){            
            $sql = "update spro_catalog_product_entity_tier_price set gp_sale_price=$value where value_id=$valueId";            
        } else{   
           $sql = "update spro_catalog_product_entity_tier_price set gp_sale_price=NULL where value_id=$valueId"; 
        } 
        $connection->query($sql);  
    }
    /**
     * Get generated price key based on price data
     *
     * @param array $priceData
     * @return string
     */
    private function getPriceKey(array $priceData): string
    {
        $qty = $this->parseQty($priceData['price_qty']);
        $key = implode(
            '-',
            array_merge([$priceData['website_id'], $priceData['cust_group']], [$qty])
        );

        return $key;
    }
      /**
     * Prepare old data to compare.
     *
     * @param array|null $origPrices
     * @return array
     */
    private function prepareOldTierPriceToCompare(?array $origPrices): array
    {
        $old = [];
        if (is_array($origPrices)) {
            foreach ($origPrices as $data) {
                $key = $this->getPriceKey($data);
                $old[$key] = $data;
            }
        }

        return $old;
    }

   

}