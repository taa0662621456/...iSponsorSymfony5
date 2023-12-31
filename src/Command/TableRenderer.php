<?php

namespace App\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class TableRenderer extends Command
{
    protected static $defaultName = 'table-renderer';

    protected function renderTable(array $headers, array $rows, OutputInterface $output): void
    {
        $table = new Table($output);

        $table
            ->setHeaders($headers)
            ->setRows($rows)
            ->render();
    }
}