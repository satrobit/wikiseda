<?php
namespace Wikiseda\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use Wikiseda\Helpers\Wikiseda;
use GuzzleHttp\Psr7\Response;

Class DownloadCommand extends Command {

    private $progressBar, $concurrent;

    protected function configure()
    {
        $this->setName('download');
        $this->setDescription('Download full albums');
        $this->addArgument('artist', InputArgument::REQUIRED, 'Artist ID');
        $this->addOption('output', null, InputOption::VALUE_OPTIONAL, 'Directory to store music files', 'output/');
        $this->addOption('concurrent', null, InputOption::VALUE_OPTIONAL, 'Number of concurrent workers', '4');
    }


    /**
     * @param array $files
     * @param string $output_dir
     * @return void
     */
    protected function downloadArtist($files, $output_dir)
    {
        if (!file_exists($output_dir)) {
            mkdir($output_dir);
        }

        $client = new Client();

        $requests = function ($files) use ($client, $output_dir) {
            foreach ($files as $file) {

                if (!file_exists($output_dir . '/' .$file['tags']['album'])) {
                    mkdir($output_dir . '/' .$file['tags']['album']);
                }

                yield function() use ($client, $file) {
                    return $client->getAsync($file['url'], ['sink' => $file['path']]);
                };
            }
        };

        $pool = new Pool($client, $requests($files), [
            'concurrency' => $this->concurrent,
            'fulfilled' => function(Response $response, $index) use ($files) {
                $this->progressBar->advance();
            },
            'rejected' => function($reason, $index) {
                $this->progressBar->advance();
            },
        ]);

        $pool->promise()->wait();
        $this->progressBar->finish();

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $artist = $input->getArgument('artist');
        $output_dir = $input->getOption('output');
        $this->concurrent = $input->getOption('concurrent');

        $Wikiseda = new Wikiseda;
        $response = $Wikiseda->artist($artist, 0, 'top', 'all');

        $downloadList = [];
        $singlesTrackNumber = 1;

        foreach ($response as $item)
        {
            if ($item['type'] == 'song') {

                $downloadList[] = [
                    'url' => $item['mp3'],
                    'path' => $output_dir . '/Singles/' . $item['filename'],
                    'tags' => [
                        'album' => 'Singles',
                        'date' => '',
                        'trackNumber' => $singlesTrackNumber,
                        'artist' => $item['artist'],
                        'title' => $item['songname']
                    ]
                ];

                $singlesTrackNumber++;

            } elseif ($item['type'] == 'album') {

                foreach ($item['albumtracks'] as $trackKey => $track)
                {

                    $downloadList[] = [
                        'url' => $track['mp3'],
                        'path' => $output_dir . '/' . $track['album'] . '/' . $track['filename'],
                        'tags' => [
                            'album' => $track['album'],
                            'date' => date('Y', $item['timestamp']),
                            'trackNumber' => $trackKey,
                            'artist' => $track['artist'],
                            'title' => $track['songname']
                        ]
                    ];
                }

            }

        }

        $this->progressBar = new ProgressBar($output, count($downloadList));

        $this->progressBar->setRedrawFrequency(1);

        $this->progressBar->setOverwrite(true);

        $this->progressBar->start();

        $this->downloadArtist($downloadList, $output_dir);

    }
}