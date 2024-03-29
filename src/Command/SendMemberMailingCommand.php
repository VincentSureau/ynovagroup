<?php

namespace App\Command;

use App\Utils\SendMail;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMemberMailingCommand extends Command
{
    protected static $defaultName = 'app:send-mailing:members';
    private $postRepository;
    private $mailer;

    public function __construct(EntityManagerInterface $em, PostRepository $postRepo, UserRepository $userRepo, SendMail $mailer)
    {
        $this->postRepository = $postRepo;
        $this->userRepository = $userRepo;
        $this->em = $em;
        $this->mailer = $mailer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Send news mailing to members')
            ->setHelp('This command send news mailing to every active member')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Member mailing');

        $io->text('<info>Checking the database for new posts :</info>');

        $newPosts = $this->postRepository
                                ->createQueryBuilder('p')
                                ->where('p.deletedAt > CURRENT_TIMESTAMP()')
                                ->andWhere('p.createdAt > :date')
                                ->setParameter('date', new \Datetime('- 7 days'))
                                ->andWhere('p.author IS NOT NULL')
                                ->setMaxResults(5)
                                ->getQuery()
                                ->getResult()
        ;

        $nbPost = count($newPosts);

        if($nbPost > 0) {
            $io->text('=> ' .$nbPost . ' were found (max 5)');
            $io->newLine();
            $io->text('<info>Checking the database for active members :</info>');
            $users = $this->userRepository
                                ->createQueryBuilder('u')
                                ->where('u.isActive = true')
                                ->andWhere('u.roles LIKE :role')
                                ->setParameter('role', '%"ROLE_MEMBER"%')
                                ->getQuery()
                                ->getResult()
        ;

        $nbUsers = count($users);

        $io->text('=> ' .$nbUsers . ' active members were found');

            if($nbUsers > 0) {
                $io->newLine();
                $io->text('<info>Mailing in progress :</info>');
                $progressBar = new ProgressBar($output);
                
                foreach($progressBar->iterate($users) as $member) {
                    $this->mailer->sendNewsletter($member, $newPosts);
                }
                $io->newLine(2);
                $io->success($nbUsers . ' members were notified');
            } else {
                $io->newLine(2);
                $io->success('No active members found');
            }

        } else {
            $io->newLine(2);
            $io->success('No news found');
        }
    }
}
