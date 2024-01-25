<?php

namespace MageSuite\ServerStatusLogger\Model\StatusResolver;

class Procesess implements \MageSuite\ServerStatusLogger\Model\StatusResolverInterface, \MageSuite\ServerStatusLogger\Model\StatusRendererInterface
{
    use \MageSuite\ServerStatusLogger\View\TableRendererTrait;

    const COLUMNS_COUNT = 4;
    const PROCESSLIST_SHELL_COMMAND = 'ps axww -o %p, -o %t, -o %C, -o cmd';

    /**
     * @var \Magento\Framework\Shell\Driver
     */
    protected $shell;

    /**
     * @var array
     */
    protected $ignoredProcesessPatterns;

    public function __construct(
        \Magento\Framework\Shell\Driver $shell,
        $ignoredProcesessPatterns = []
    )
    {
        $this->shell = $shell;
        $this->ignoredProcesessPatterns = $ignoredProcesessPatterns;
    }

    /**
     * @inheritDoc
     */
    public function getCurrentStatus()
    {
        $processes = $this->shell->execute(self::PROCESSLIST_SHELL_COMMAND, []);
        $processes = explode(PHP_EOL, $processes->getOutput());

        $header = [];
        $result = [];

        foreach($processes as $index => $process) {
            if(empty($header)) {
                $header = explode(',', $process, self::COLUMNS_COUNT);
                $header = array_map('trim', $header);
                continue;
            }

            $process = explode(',', $process, self::COLUMNS_COUNT);
            $process = array_map('trim', $process);

            $row = [];

            for($i =0; $i < count($header); $i++) {
                $row[$header[$i]] = $process[$i];
            }

            if(!isset($row['CMD'])) {
                unset($processes[$index]);
                continue;
            }

            if(!$this->shouldBeLogged($row['CMD'])) {
                unset($processes[$index]);
                continue;
            }

            $result[] = $row;
        }

        return $result;
    }

    protected function shouldBeLogged($process)
    {
        foreach($this->ignoredProcesessPatterns as $pattern) {
            if(fnmatch($pattern, $process)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function render(\Symfony\Component\Console\Output\OutputInterface $output, $data)
    {
        $this->renderTable('Server processes', $data, $output);
    }
}
