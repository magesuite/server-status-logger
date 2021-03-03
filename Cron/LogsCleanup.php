<?php

namespace MageSuite\ServerStatusLogger\Cron;

class LogsCleanup
{
    /**
     * @var \MageSuite\ServerStatusLogger\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\ServerStatusLogger\Model\CleanLogs
     */
    protected $cleanLogs;

    public function __construct(
        \MageSuite\ServerStatusLogger\Helper\Configuration $configuration,
        \MageSuite\ServerStatusLogger\Model\CleanLogs $cleanLogs
    )
    {
        $this->configuration = $configuration;
        $this->cleanLogs = $cleanLogs;
    }

    public function execute()
    {
        $this->cleanLogs->execute($this->configuration->getLoggingRetentionPeriod());
    }
}
