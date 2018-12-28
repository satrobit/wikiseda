<?php
namespace Wikiseda\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Wikiseda\Helpers\Wikiseda;

Class SearchCommand extends Command {

    protected function configure()
    {
        $this->setName('search');
        $this->setDescription('Search through artists');
        $this->addArgument('query', InputArgument::REQUIRED, 'Query');
        $this->addOption('type', null, InputOption::VALUE_OPTIONAL, 'Type of the result', 'artist');
        $this->addOption('page', null, InputOption::VALUE_OPTIONAL, 'Custom result page', 0);
        $this->addOption('order', null, InputOption::VALUE_OPTIONAL, 'Custom result order', 'top');
        }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $query = $input->getArgument('query');
        $type = $input->getOption('type');
        $page = $input->getOption('page');
        $order = $input->getOption('order');

        $Wikiseda = new Wikiseda;
        $response = $Wikiseda->search($query, $page, $order, $type);

        $table = new Table($output);
        if ($type === 'artist')
        {
            $table->setHeaders(['ID', 'Name', 'Albums', 'Tracks']);

            foreach ($response as $item)
            {
                $table->addRow([$item['id'], $item['artist'], $item['albums'], $item['tracks']]);
            }

        } elseif ($type === 'album') {
            $table->setHeaders(['ID', 'Album']);

            foreach ($response as $item)
            {
                $table->addRow([$item['id'], $item['album']]);
            }
        } else {
            $table->setHeaders(['ID']);

            foreach ($response as $item)
            {
                $table->addRow([$item['id']]);
            }

        }

        $table->render();
    }
}