<?php
namespace Wikiseda\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Wikiseda\Helpers\Wikiseda;

Class ArtistCommand extends Command {

    protected function configure()
    {
        $this->setName('artist');
        $this->setDescription('Artists details');
        $this->addArgument('artist', InputArgument::REQUIRED, 'Artist ID');
        $this->addOption('type', null, InputOption::VALUE_OPTIONAL, 'Type of the result', 'album');
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
        $artist = $input->getArgument('artist');
        $type = $input->getOption('type');
        $page = $input->getOption('page');
        $order = $input->getOption('order');

        $Wikiseda = new Wikiseda;
        $response = $Wikiseda->artist($artist, $page, $order, $type);

        $table = new Table($output);
        if ($type === 'album')
        {
            $table->setHeaders(['ID', 'Album', 'Type', 'Date']);

            foreach ($response as $item)
            {
                if ($item['type'] !== 'album') continue;
                $table->addRow([$item['id'], $item['album'], $item['type'], $item['date']]);
            }

        } elseif ($type === 'song') {
            $table->setHeaders(['ID', 'Song', 'Date']);

            foreach ($response as $item)
            {
                if ($item['type'] !== 'song') continue;
                $table->addRow([$item['id'], $item['songname'], $item['date']]);
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