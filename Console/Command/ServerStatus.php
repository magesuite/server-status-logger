<?php

namespace MageSuite\ServerStatusLogger\Console\Command;

class ServerStatus extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \MageSuite\ServerStatusLogger\Model\GenerateCurrentStatusFactory
     */
    protected $generateCurrentStatusFactory;

    /**
     * @var \MageSuite\ServerStatusLogger\Model\RenderLogDataFactory
     */
    protected $renderLogDataFactory;

    /**
     * @var \MageSuite\ServerStatusLogger\Model\StatusLogFactory
     */
    protected $statusLogFactory;

    public function __construct(
        \MageSuite\ServerStatusLogger\Model\GenerateCurrentStatusFactory $generateCurrentStatusFactory,
        \MageSuite\ServerStatusLogger\Model\RenderLogDataFactory $renderLogDataFactory,
        \MageSuite\ServerStatusLogger\Model\StatusLogFactory $statusLogFactory
    )
    {
        parent::__construct();

        $this->generateCurrentStatusFactory = $generateCurrentStatusFactory;
        $this->renderLogDataFactory = $renderLogDataFactory;
        $this->statusLogFactory = $statusLogFactory;
    }

    protected function configure()
    {
        $this
            ->setName('server:status')
            ->setDescription('Display historical server status (processes, indexes status, cron jobs, database queries) based on log id, or current one when log id is not passed.');

        $this->addOption(
            'log_id',
            'i',
            \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
            'Log id from server_status_log table'
        );
    }

    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
        $logId = $input->getOption('log_id');

        if(empty($logId)) {
            $logData = $this->generateCurrentStatusFactory->create()
                ->execute();
        }
        else {
            $statusLog = $this->statusLogFactory->create();
            $statusLog->load($logId, 'id');

            $logData = $statusLog->getLogData();

            $helper = new \Symfony\Component\Console\Style\SymfonyStyle($input, $output);
            $helper->title(sprintf('Logged at: %s', $statusLog->getCreatedAt()));
        }

        $renderLogData = $this->renderLogDataFactory->create();
        $renderLogData->execute($output, $logData);
    }
}
