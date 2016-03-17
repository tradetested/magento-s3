<?php
require_once './abstract.php';

class Arkade_S3_Shell_Config extends Mage_Shell_Abstract
{
    protected function _validate()
    {
        if (!isset($this->_args['h']) && !isset($this->_args['help'])) {
            $errors = [];
            if (empty($this->getArg('access-key'))) {
                $errors[] = 'You must specify the "access-key" parameter.';
            }
            if (empty($this->getArg('secret-key'))) {
                $errors[] = 'You must specify the "secret-key" parameter.';
            }
            if (empty($this->getArg('bucket'))) {
                $errors[] = 'You must specify the "bucket" parameter.';
            }
            if (empty($this->getArg('region'))) {
                $errors[] = 'You must specify the "region" parameter.';
            } else {
                /** @var Arkade_S3_Helper_S3 $helper */
                $helper = Mage::helper('arkade_s3/s3');
                if (!$helper->isValidRegion($this->getArg('region'))) {
                    $errors[] = sprintf('The region "%s" is invalid.', $this->getArg('region'));
                }
            }
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo $error . "\n";
                }

                echo "\nusage: php s3_config.php [options]\n\n";
                echo "    --access-keyid <access-key-id> a valid AWS access key ID\n";
                echo "    --secret-key <secret-key>      a valid AWS secret access key\n";
                echo "    --bucket <bucket>              an S3 bucket name\n";
                echo "    --region <region>              an S3 region, e.g. us-east-1\n";
                echo "    -h, --help\n\n";
                die();
            }

            parent::_validate();
        }
    }

    public function run()
    {
        Mage::getConfig()->saveConfig('arkade_s3/general/access_key', $this->getArg('access-key'));
        Mage::getConfig()->saveConfig('arkade_s3/general/secret_key', $this->getArg('secret-key'));
        Mage::getConfig()->saveConfig('arkade_s3/general/bucket', $this->getArg('bucket'));
        Mage::getConfig()->saveConfig('arkade_s3/general/region', $this->getArg('region'));

        echo "You have successfully updated your S3 credentials.\n";

        return $this;
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
\033[1mDESCRIPTION\033[0m
    Allows the developer to configure which S3 bucket they want to use with
    their Magento installation.

\033[1mSYNOPSIS\033[0m
    php s3_config.php [--access-key-id <access-key-id>]
                      [--secret-key <secret-key>]
                      [--bucket <bucket>]
                      [--region <region>]
                      [-h] [--help]

\033[1mOPTIONS\033[0m
    --access-key-id <access-key-id>
        You must provide a valid AWS access key ID. You can generate access keys
        using the AWS IAM (https://console.aws.amazon.com/iam/home).

    --secret-key <secret-key>
        You must also provide the secret access key that corresponds to the
        access key ID that you have just generated.

    --bucket <bucket>
        You must provide a valid S3 bucket name that you want media files to be
        uploaded to.

    --region <region>
        You must provide a valid S3 region. As 2016-03-17, S3 has the following
        regions:

        us-east-1
        us-west-1
        us-west-2
        eu-west-1
        eu-central-1
        ap-southeast-1
        ap-southeast-2
        ap-northeast-1
        ap-northeast-2
        sa-east-1

        You can review all valid S3 regions via the AWS documentation
        (http://docs.aws.amazon.com/general/latest/gr/rande.html#s3_region).


USAGE;
    }
}

$shell = new Arkade_S3_Shell_Config();
$shell->run();
