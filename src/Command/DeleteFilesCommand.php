<?php

namespace App\Command;

use App\Repository\FilesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteFilesCommand extends Command
{
    protected static $defaultName = 'app:delete-files';

    public function __construct(EntityManagerInterface $em, FilesRepository $filesRepo)
    {
        $this->filesRepository = $filesRepo;
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Delete outdated files form the database')
            ->setHelp('This command delete every files where deletedAt date is passed, to change the date of deletion of a files, go the backend dashboard.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Deletion of outdated files');

        $io->text('Checking the database for outated files :');

        $outdatedFiles = $this->filesRepository
                                ->createQueryBuilder('f')
                                ->where('f.deletedAt < CURRENT_TIMESTAMP()')
                                ->getQuery()
                                ->getResult()
        ;

        $nbFile = count($outdatedFiles);

        if($nbFile > 0) {
            $io->text('=> ' .$nbFile . ' to delete');

            $progressBar = new ProgressBar($output);

            $io->text('Deletion in progress :');
            
            foreach($progressBar->iterate($outdatedFiles) as $file) {
                $this->em->remove($file);
            }

            $this->em->flush();
            
            $io->success($nbFile . ' files were successfully deleted');
        } else {
            $io->success('Nothing to delete');
        }
    }
}
