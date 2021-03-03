<?php

namespace MageSuite\ServerStatusLogger\Model;

class StatusLog extends \Magento\Framework\Model\AbstractModel implements \MageSuite\ServerStatusLogger\Api\Data\StatusLogInterface
{
    protected function _construct()
    {
        $this->_init(\MageSuite\ServerStatusLogger\Model\ResourceModel\StatusLog::class);
    }

    /**
     * @inheritDoc
     */
    public function getLogData()
    {
        return json_decode($this->getData('log_data'), true);
    }

    /**
     * @inheritDoc
     */
    public function setLogData($data)
    {
        return $this->setData('log_data', json_encode($data));
    }
}