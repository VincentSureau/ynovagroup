<?php

namespace App\Command;

use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeletePostsCommand extends Command
{
    protected static $defaultName = 'app:delete-posts';

    public function __construct(EntityManagerInterface $em, PostRepository $postsRepo)
    {
        $this->postsRepository = $postsRepo;
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Delete outdated posts form the database')
            ->setHelp('This command delete every posts where deletedAt date is passed, to change the date of deletion of a posts, go the backend dashboard.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Deletion of outdated posts');

        $io->text('Checking the database for outated posts :');

        $outdatedposts = $this->postsRepository
                                ->createQueryBuilder('f')
                                ->where('f.deletedAt < CURRENT_TIMESTAMP()')
                                ->getQuery()
                                ->getResult()
        ;

        $nbPost = count($outdatedposts);

        if($nbPost > 0) {
            $io->text('=> ' .$nbPost . ' post(s) to delete');

            $progressBar = new ProgressBar($output);

            $io->text('Deletion in progress :');
            
            foreach($progressBar->iterate($outdatedposts) as $file) {
                $this->em->remove($file);
            }

            $this->em->flush();
            
            $io->success($nbPost . ' post(s) were successfully deleted');
        } else {
            $io->success('Nothing to delete');
        }
    }
}
