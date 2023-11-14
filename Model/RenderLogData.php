<?php

namespace MageSuite\ServerStatusLogger\Model;

class RenderLogData
{
    /**
     * @var \MageSuite\ServerStatusLogger\Model\StatusResolversPool
     */
    protected $statusResolversPool;

    public function __construct(\MageSuite\ServerStatusLogger\Model\StatusResolversPool $statusResolversPool)
    {
        $this->statusResolversPool = $statusResolversPool;
    }

    public function execute(\Symfony\Component\Console\Output\OutputInterface $output, $data) {
        $status = [];

        foreach($this->statusResolversPool->getResolvers() as $resolverIdentifier => $resolver) {
            if(!$resolver instanceof StatusRendererInterface) {
                continue;
            }

            $resolver->render($output, $data[$resolverIdentifier]);
        }

        return $output;
    }
}
