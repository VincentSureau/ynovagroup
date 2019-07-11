<?php

namespace App\Command;

use App\Entity\Post;
use App\Repository\RssRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportArticlesCommand extends Command
{
    protected static $defaultName = 'app:import-articles';

    public function __construct(EntityManagerInterface $em, PostRepository $postRepo, RssRepository $rssRepo)
    {
        $this->postRepository = $postRepo;
        $this->rssRepository = $rssRepo;
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Import articles from RSS feed')
            // ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command imports articles from every active RSS feed. Go to RSS administration page if you want to edit the list.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        // $arg1 = $input->getArgument('arg1');

        // if ($arg1) {
        //     $io->note(sprintf('You passed an argument: %s', $arg1));
        // }

        // if ($input->getOption('option1')) {
        //     // ...
        // }

        $oldPosts = $this->postRepository->findByAuthor(null);
        
        $activeRssFeeds = $this->rssRepository->findByIsActive(true);
        if(!empty($activeRssFeeds)) {
            $io->title('Articles will be imported from the following RSS feeds :');
            $listing = array_map(function($rss){ return $rss->getName();}, $activeRssFeeds);
            $io->listing($listing);

            $inactiveRssFeeds = $this->rssRepository->findByIsActive(false);
            $io->title('The following RSS feeds will be ignored :');

            if(!empty($inactiveRssFeeds)) {
                $listing = array_map(function($rss){ return $rss->getName();}, $inactiveRssFeeds);
                $io->listing($listing);
            } else {
                $io->text('No inactive RSS feed found');
                $io->newLine();
            }

            $articles = [];
            $total = 0;

            foreach($activeRssFeeds as $rss) {
                $feed = simplexml_load_file($rss->getLink());

                $io->text([
                    'Importing articles from ' . $rss->getName() .' :',
                ]);

                $progressBar = new ProgressBar($output);

                foreach($progressBar->iterate($feed->channel->item) as $item) {
                    $content = strip_tags($item->description);

                    if(isset($item->link)) {
                        $link = '
                            <br>
                            <p class="text-right">
                                <a class="btn btn-success btn-sm" href="%s" target="_blank">Lire la suite</a>
                            </p>
                        ';
                        $link = sprintf($link, $item->link);
                        $content .= $link;
                    }

                    $post = new Post();
                    $date = \DateTime::createFromFormat(\DateTimeInterface::RSS, $item->pubDate);
                    if ($date == false) {
                        $date = new \DateTime('first day of last month');
                    }

                    $post
                        ->setRssfeedname($feed->channel->title)
                        ->setTitle($item->title)
                        ->setContent($content)
                        ->setCreatedAt($date);

                    if (isset($item->enclosure)) {
                        $validFormat = [
                            'image/gif',
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/tiff',
                            'image/webp'
                        ];

                        if(in_array($item->enclosure['type'], $validFormat)) {                                                 
                            $extension = explode('/', $item->enclosure['type']);
                            $ch = curl_init($item->enclosure['url']);
                            
                            $my_save_dir = 'public/images/articles/';
                            $filename = md5(uniqid(rand(), true)) . '.' . $extension[1];
                            $complete_save_loc = $my_save_dir . $filename;
                            $fp = fopen($complete_save_loc, 'wb');
                            curl_setopt($ch, CURLOPT_FILE, $fp);
                            curl_setopt($ch, CURLOPT_HEADER, 0);
                            curl_exec($ch);
                            curl_close($ch);
                            fclose($fp);
    
                            $post->setPicture($filename);
                        }
                    }

                    $this->em->persist($post);
                    $articles[$rss->getName()][] = $post;
                    $total++;
                }

                $progressBar->finish();
                $io->newLine();
            }

            $this->em->flush();

            foreach($oldPosts as $post) {
                $this->em->remove($post);
            }

            $this->em->flush();

            $response = [$total . ' articles have been imported successfully :'];
            $list = array_map(function($key, $values) {
                return '* ' . count($values) .  ' article(s) from ' . $key;
            },array_keys($articles), $articles);
            $response = array_merge($response, $list);

            $io->newLine();
            $io->success($response);
        } else {
            $io->newLine();
            $io->warning('No active RSS feed available, to continue, please go to RSS administration page and set at least one RSS Feed to active');
        }
    }
}
