<?php

namespace MageSuite\ServerStatusLogger\View;

trait TableRendererTrait
{
    public function renderTable($sectionName, $tableData, $output) {
        $helper = new \Symfony\Component\Console\Style\SymfonyStyle(new \Symfony\Component\Console\Input\ArrayInput([]), $output);
        $helper->section($sectionName);

        $table = new \Symfony\Component\Console\Helper\Table($output);

        $table->setHeaders(array_keys($tableData[0]));
        $table->setRows($tableData);

        $table->render();
    }
}