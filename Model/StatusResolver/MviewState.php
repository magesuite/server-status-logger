<?php

namespace MageSuite\ServerStatusLogger\Model\StatusResolver;

class MviewState implements \MageSuite\ServerStatusLogger\Model\StatusResolverInterface, \MageSuite\ServerStatusLogger\Model\StatusRendererInterface
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

        $mviewState = $connection->getTableName('mview_state');

        $statement->from($mviewState);

        $indexers = $connection->fetchAll($statement);

        $views = $this->getViews();

        foreach($indexers as &$index) {
            $viewId = $index['view_id'];

            if(!isset($views[$viewId])) {
                continue;
            }

            $index['pending_count'] = $this->getPendingCount($views[$viewId]);
        }

        return $indexers;
    }

    public function getViews() {
        if($this->views === null) {
            $views = $this->viewsCollection->getItems();

            foreach($views as $view) {
                $this->views[$view->getViewId()] = $view;
            }
        }

        return $this->views;
    }

    private function getPendingCount($view)
    {
        $changelog = $view->getChangelog();

        try {
            $currentVersionId = $changelog->getVersion();
        } catch (\Magento\Framework\Mview\View\ChangelogTableNotExistsException $e) {
            return 0;
        }

        $state = $view->getState();

        return count($changelog->getList($state->getVersionId(), $currentVersionId));
    }

    /**
     * @inheritDoc
     */
    public function render(\Symfony\Component\Console\Output\OutputInterface $output, $data)
    {
        $this->renderTable('MView State', $data, $output);
    }
}