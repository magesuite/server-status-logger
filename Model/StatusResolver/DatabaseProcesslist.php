<?php

namespace MageSuite\ServerStatusLogger\Model\StatusResolver;

class DatabaseProcesslist implements \MageSuite\ServerStatusLogger\Model\StatusResolverInterface, \MageSuite\ServerStatusLogger\Model\StatusRendererInterface
{
    use \MageSuite\ServerStatusLogger\View\TableRendererTrait;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var int
     */
    protected $maximumQueryLength;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        $maximumQueryLength = 3000
    )
    {
        $this->resourceConnection = $resourceConnection;
        $this->maximumQueryLength = $maximumQueryLength;
    }

    /**
     * @inheritDoc
     */
    public function getCurrentStatus()
    {
        $connection = $this->resourceConnection->getConnection();

        $queries = $connection->fetchAll("SHOW FULL PROCESSLIST");

        foreach($queries as &$query) {
            $query['Info'] = substr($query['Info'], 0, $this->maximumQueryLength);
        }

        return $queries;
    }

    /**
     * @inheritDoc
     */
    public function render(\Symfony\Component\Console\Output\OutputInterface $output, $data)
    {
        if(empty($data)) {
            return;
        }

        $this->renderTable('Database processlist', $data, $output);
    }
}