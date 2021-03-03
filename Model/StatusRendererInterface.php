<?php

namespace MageSuite\ServerStatusLogger\Model;

interface StatusRendererInterface
{
    /**
     * Render formatted log data
     */
    public function render(\Symfony\Component\Console\Output\OutputInterface $output, $data);
}