<?php

namespace MageSuite\ServerStatusLogger\Model;

class GenerateCurrentStatus
{
    /**
     * @var \MageSuite\ServerStatusLogger\Model\StatusResolversPool
     */
    protected $statusResolversPool;

    public function __construct(\MageSuite\ServerStatusLogger\Model\StatusResolversPool $statusResolversPool)
    {
        $this->statusResolversPool = $statusResolversPool;
    }

    public function execute() {
        $status = [];

        foreach($this->statusResolversPool->getResolvers() as $resolverIdentifier => $resolver) {
            $status[$resolverIdentifier] = $resolver->getCurrentStatus();
        }

        return $status;
    }
}
