<?php

namespace App\Command;

use App\Entity\Post;
use App\Repository\RssRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
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
        $oldPosts = $this->postRepository->findByAuthor(null);
        foreach($oldPosts as $post) {
            $this->em->remove($post);
        }

        $this->em->flush();
        
        $activeRssFeeds = $this->rssRepository->findByIsActive(true);
        $output->writeln('<comment>Articles will be imported from the following RSS feeds :</comment>');
        foreach($activeRssFeeds as $rss) {
            $output->writeln([
                '- ' . $rss->getName(),
            ]);
        }
        $inactiveRssFeeds = $this->rssRepository->findByIsActive(false);
        $output->writeln('<comment>The following RSS feeds will be ignored :</comment>');
        foreach($inactiveRssFeeds as $rss) {
            $output->writeln([
                '- ' . $rss->getName(),
            ]);
        }

        $articles = [];
        $total = 0;
        foreach($activeRssFeeds as $rss) {
            $feed = simplexml_load_file($rss->getLink());
            foreach($feed->channel->item as $item) {
                $post = new Post();
                $post
                    ->setTitle($item->title)
                    ->setContent($item->description)
                    ->setCreatedAt(\DateTime::createFromFormat('D, d M Y H:i:s e', $item->pubDate));
                
                $this->em->persist($post);
                $articles[$rss->getName()][] = $post;
                $total++;
            }
        }
        $this->em->flush();

        $io = new SymfonyStyle($input, $output);
        // $arg1 = $input->getArgument('arg1');

        // if ($arg1) {
        //     $io->note(sprintf('You passed an argument: %s', $arg1));
        // }

        // if ($input->getOption('option1')) {
        //     // ...
        // }

        $response = [$total . ' articles have been imported successfully :'];
        foreach($articles as $source => $posts) {
            $format = '- %s article(s) from %s';
            $format = sprintf($format, count($posts), $source);
            $response[] = $format;
        }
        $io->success($response);
    }
}
