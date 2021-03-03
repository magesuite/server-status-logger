<?php

namespace MageSuite\ServerStatusLogger\Api\Data;

interface StatusLogInterface
{
    /**
     * @return array
     */
    public function getLogData();

    /**
     * @param array $data
     * @return self
     */
    public function setLogData($data);
}