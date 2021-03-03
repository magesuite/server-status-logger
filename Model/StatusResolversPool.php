<?php

namespace MageSuite\ServerStatusLogger\Model;

class StatusResolversPool
{
    /**
     * @var \MageSuite\ServerStatusLogger\Model\StatusResolverInterface[]
     */
    protected $resolvers;

    public function __construct(array $resolvers = [])
    {
        $this->resolvers = $resolvers;
    }

    /**
     * @return \MageSuite\ServerStatusLogger\Model\StatusResolverInterface[]
     */
    public function getResolvers() {
        return $this->resolvers;
    }
}