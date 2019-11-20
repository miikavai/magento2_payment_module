<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Piimega\Maksuturva\Model\ResourceModel;
 
class Method extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('maksuturva_payment_method', 'code');
    }

    public function insert($object){

        $connection = $this->getConnection();
        
        /**
         * fix local development exception
         * check for missing imageurl
         */
        if (!property_exists($object, "imageurl"))
        {   
            error_log("Object imageurl is missing. Skipping object insert.");                                                                                                                                                                                                           error_log("Imageurl is missing. Skipping object.");
            return false;
        }   

        /**
         * save detail
         */
        $detail = [
            'code' => $object->code,
            'displayname' => $object->displayname,
            'imageurl' => $object->imageurl
        ];
        $select = $connection->select()->from($this->_mainTable, 'code')->where('code = :code');
        $code = $connection->fetchOne($select, [':code' => $object->code]);

        if ($code) {
            $condition = ["code = ?" => $code];
            $connection->update($this->_mainTable, $detail, $condition);
        } else {
            $connection->insert($this->_mainTable, $detail);
        }
        return $this;
    }
}
