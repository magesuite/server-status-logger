<?php

namespace MageSuite\ServerStatusLogger\Helper;

class Configuration
{
    const XML_PATH_SERVER_STATUS_LOGGER_CONFIGURATION = 'system/server_status_logger';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $config = null;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
    ) {
        $this->scopeConfig = $scopeConfigInterface;
    }

    public function getLoggingRetentionPeriod()
    {
        return $this->getConfig()->getLoggingRetentionPeriod();
    }

    protected function getConfig()
    {
        if ($this->config === null) {
            $config = $this->scopeConfig->getValue(self::XML_PATH_SERVER_STATUS_LOGGER_CONFIGURATION);

            if(!is_array($config) || $config === null) {
                $config = [];
            }

            $this->config = new \Magento\Framework\DataObject($config);
        }

        return $this->config;
    }
}
