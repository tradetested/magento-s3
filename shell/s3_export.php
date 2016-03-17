<?php
require_once './abstract.php';

class Arkade_S3_Shell_Export extends Mage_Shell_Abstract
{
    public function run()
    {
        /** @var Mage_Core_Helper_File_Storage $helper */
        $helper = Mage::helper('core/file_storage');

        // Stop S3 from syncing to itself
        if (Arkade_S3_Model_Core_File_Storage::STORAGE_MEDIA_S3 == $helper->getCurrentStorageCode()) {
            return $this;
        }

        /** @var Mage_Core_Model_File_Storage_File|Mage_Core_Model_File_Storage_Database $sourceModel */
        $sourceModel = $helper->getStorageModel();

        /** @var Arkade_S3_Model_Core_File_Storage_S3 $destinationModel */
        $destinationModel = $helper->getStorageModel(Arkade_S3_Model_Core_File_Storage::STORAGE_MEDIA_S3);

        $offset = 0;
        while (($files = $sourceModel->exportFiles($offset, 1)) !== false) {
            foreach ($files as $file) {
                echo $file['directory'] . '/' . $file['filename'] . "\n";
            }
            if (!$this->getArg('dry-run')) {
                $destinationModel->importFiles($files);
            }
            $offset += count($files);
        }
        unset($files);

        if (!$this->getArg('dry-run')) {
            Mage::getConfig()->saveConfig('system/media_storage_configuration/media_storage', 2);
        }

        return $this;
    }
}

$shell = new Arkade_S3_Shell_Export();
$shell->run();
