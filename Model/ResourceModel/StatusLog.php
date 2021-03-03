<?php

namespace MageSuite\ServerStatusLogger\Model\ResourceModel;

class StatusLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('server_status_log', 'id');
    }
}