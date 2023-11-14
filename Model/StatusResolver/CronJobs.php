<?php

namespace MageSuite\ServerStatusLogger\Model\StatusResolver;

class CronJobs implements \MageSuite\ServerStatusLogger\Model\StatusResolverInterface, \MageSuite\ServerStatusLogger\Model\StatusRendererInterface
{
    use \MageSuite\ServerStatusLogger\View\TableRendererTrait;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    protected $views = null;

    /**
     * @var \Magento\Framework\Mview\View\Collection
     */
    protected $viewsCollection;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\Mview\View\Collection $viewsCollection
    )
    {
        $this->resourceConnection = $resourceConnection;
        $this->viewsCollection = $viewsCollection;
    }

    /**
     * @inheritDoc
     */
    public function getCurrentStatus()
    {
        $connection = $this->resourceConnection->getConnection();

        $statement = $connection->select();

        $cronSchedule = $connection->getTableName('cron_schedule');

        $statement->from($cronSchedule);
        $statement->where('status = ?', 'running');
        $statement->orWhere('finished_at >= ?', new \Zend_Db_Expr('DATE_SUB(CURRENT_TIMESTAMP(), INTERVAL 2 MINUTE)'));
        $statement->order('status', 'ASC');

        $select = (string)$statement;

        return $connection->fetchAll($select);
    }

    /**
     * @inheritDoc
     */
    public function render(\Symfony\Component\Console\Output\OutputInterface $output, $data)
    {
        if(empty($data)) {
            return;
        }

        $this->renderTable('Cron jobs', $data, $output);
    }
}
