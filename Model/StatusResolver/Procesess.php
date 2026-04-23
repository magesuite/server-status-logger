<?php

namespace MageSuite\ServerStatusLogger\Model\StatusResolver;

class Procesess implements \MageSuite\ServerStatusLogger\Model\StatusResolverInterface, \MageSuite\ServerStatusLogger\Model\StatusRendererInterface
{
    use \MageSuite\ServerStatusLogger\View\TableRendererTrait;

    public const COLUMNS_COUNT = 4;
    public const PROCESSLIST_SHELL_COMMAND = 'ps axww -o %p, -o %t, -o %C, -o cmd';
    public const FNMATCH_MAX_FILENAME_LENGTH = 4096;

    public function __construct(
        protected \Magento\Framework\Shell\Driver $shell,
        protected array $ignoredProcesessPatterns = []
    ) {}

    /**
     * @inheritDoc
     */
    public function getCurrentStatus()
    {
        $processes = $this->shell->execute(self::PROCESSLIST_SHELL_COMMAND, []);
        $processes = explode(PHP_EOL, $processes->getOutput());

        $header = [];
        $result = [];

        foreach ($processes as $index => $process) {
            if (empty($header)) {
                $header = explode(',', $process, self::COLUMNS_COUNT);
                $header = array_map('trim', $header);
                continue;
            }

            $process = explode(',', $process, self::COLUMNS_COUNT);
            $process = array_map('trim', $process);

            $row = [];

            foreach ($header as $columnIndex => $columnName) {
                $row[$columnName] = $process[$columnIndex] ?? null;
            }

            if (!isset($row['CMD'])) {
                unset($processes[$index]);
                continue;
            }

            if (!$this->shouldBeLogged($row['CMD'])) {
                unset($processes[$index]);
                continue;
            }

            $result[] = $row;
        }

        return $result;
    }

    protected function shouldBeLogged(string $process): bool
    {
        if (strlen($process) > self::FNMATCH_MAX_FILENAME_LENGTH) {
            return true;
        }

        foreach ($this->ignoredProcesessPatterns as $pattern) {
            if (fnmatch($pattern, $process)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function render(\Symfony\Component\Console\Output\OutputInterface $output, $data): void
    {
        $this->renderTable('Server processes', $data, $output);
    }
}
