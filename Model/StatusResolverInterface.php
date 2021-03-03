<?php

namespace MageSuite\ServerStatusLogger\Model;

interface StatusResolverInterface
{
    /**
     * Return information about current status of specific service
     * @return mixed
     */
    public function getCurrentStatus();
}