<?php

class Arkade_S3_Model_Captcha_Zend extends Mage_Captcha_Model_Zend
{
    private $s3Helper = null;

    protected function _generateImage($id, $word)
    {
        parent::_generateImage($id, $word);

        if ($this->getS3Helper()->checkS3Usage()) {
            $this->getS3Helper()->saveFile($this->getImgDir() . $this->getId() . $this->getSuffix());
        }
    }

    /**
     * @return Arkade_S3_Helper_Core_File_Storage_Database
     */
    protected function getS3Helper()
    {
        if (is_null($this->s3Helper)) {
            $this->s3Helper = Mage::helper('arkade_s3/core_file_storage_database');
        }
        return $this->s3Helper;
    }
}
