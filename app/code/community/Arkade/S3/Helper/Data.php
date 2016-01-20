<?php

class Arkade_S3_Helper_Data extends Mage_Core_Helper_Data
{
    private $client = null;

    /**
     * @return Zend_Service_Amazon_S3
     */
    public function getClient()
    {
        if (is_null($this->client)) {
            $this->client = new Zend_Service_Amazon_S3(
                $this->getAccessKey(),
                $this->getSecretKey(),
                $this->getRegion()
            );
        }
        return $this->client;
    }

    /**
     * @param string $filePath
     * @param string $prefix
     * @return string
     */
    public function getObjectKey($filePath, $prefix = null)
    {
        if (!is_null($prefix)) {
            $filePath = $prefix . '/' . $filePath;
        }
        return $this->getBucket() . '/' . $filePath;
    }

    /**
     * Returns the AWS access key.
     *
     * @return string
     */
    public function getAccessKey()
    {
        return Mage::getStoreConfig('arkade_s3/general/access_key');
    }

    /**
     * Returns the AWS secret key.
     *
     * @return string
     */
    public function getSecretKey()
    {
        return Mage::getStoreConfig('arkade_s3/general/secret_key');
    }

    /**
     * Returns the AWS region that we're using, e.g. ap-southeast-2.
     *
     * @return string
     */
    public function getRegion()
    {
        return Mage::getStoreConfig('arkade_s3/general/region');
    }

    /**
     * Returns the S3 bucket where we want to store all our images.
     *
     * @return string
     */
    public function getBucket()
    {
        return Mage::getStoreConfig('arkade_s3/general/bucket');
    }
}
