<?php

class Arkade_S3_Model_Adminhtml_System_Config_Source_Storage_Media_Storage extends Mage_Adminhtml_Model_System_Config_Source_Storage_Media_Storage
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = parent::toOptionArray();
        $options[] = [
            'value' => Arkade_S3_Model_Core_File_Storage::STORAGE_MEDIA_S3,
            'label' => Mage::helper('arkade_s3')->__('Amazon S3')
        ];
        return $options;
    }
}
