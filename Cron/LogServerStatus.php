<?php

namespace MageSuite\ServerStatusLogger\Cron;

class LogServerStatus
{
    /**
     * @var \MageSuite\ServerStatusLogger\Model\GenerateCurrentStatus
     */
    protected $generateCurrentStatus;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \MageSuite\ServerStatusLogger\Model\StatusLogFactory
     */
    protected $statusLogFactory;

    public function __construct(
        \MageSuite\ServerStatusLogger\Model\GenerateCurrentStatus $generateCurrentStatus,
        \MageSuite\ServerStatusLogger\Model\StatusLogFactory $statusLogFactory
    )
    {
        $this->generateCurrentStatus = $generateCurrentStatus;
        $this->statusLogFactory = $statusLogFactory;
    }

    public function execute() {
        $statusLog = $this->statusLogFactory->create();
        $statusLog->setLogData($this->generateCurrentStatus->execute());
        $statusLog->save();
    }
}