<?php

namespace MageSuite\ServerStatusLogger\Model\StatusResolver;

class IndexerState implements \MageSuite\ServerStatusLogger\Model\StatusResolverInterface, \MageSuite\ServerStatusLogger\Model\StatusRendererInterface
{
    use \MageSuite\ServerStatusLogger\View\TableRendererTrait;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection
    )
    {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @inheritDoc
     */
    public function getCurrentStatus()
    {
        $connection = $this->resourceConnection->getConnection();

        $statement = $connection->select();

        $indexerState = $connection->getTableName('indexer_state');

        $statement->from($indexerState);

        return $connection->fetchAll($statement);
    }

    /**
     * @inheritDoc
     */
    public function render(\Symfony\Component\Console\Output\OutputInterface $output, $data)
    {
        if(empty($data)) {
            return;
        }

        $this->renderTable('Indexer state', $data, $output);
    }
}
