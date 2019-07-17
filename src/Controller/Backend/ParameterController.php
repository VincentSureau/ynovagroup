<?php

namespace App\Controller\Backend;

use App\Entity\Config;
use App\Form\ConfigType;
use App\Repository\ConfigRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Routing\Annotation\Route;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ParameterController extends AbstractController
{
    /**
     * @Route("/configuration", name="parameters")
     */
    public function index(ConfigRepository $repo, Request $request, KernelInterface $kernel)
    {
        $consoleResponse = '';

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $config = $repo->findConfig();

        if (empty($config)) {
            $config = new Config();
        }

        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($config);
            $em->flush();

            if($config->getIsUnderMaintenance() == true) {
                $this->addFlash('danger', 'Le site est maintenant en cours de maintenance, il n\'est plus visible par les utilisateurs');
                $command = 'lexik:maintenance:lock';
            } elseif ($config->getIsUnderMaintenance() == false) {
                $this->addFlash('success', 'Le site est désormais en production, il est visible par les utilisateurs');
                $command = 'lexik:maintenance:unlock';
            }

            $input = new ArrayInput([
                'command' => $command,
                // (optional) define the value of command arguments
                //'fooArgument' => 'barValue',
                // (optional) pass options to the command
                '--no-interaction' => '',
            ]);

            // You can use NullOutput() if you don't need the output
            $output = new BufferedOutput(
                OutputInterface::VERBOSITY_NORMAL,
                true // true for decorated
            );
            $application->run($input, $output);

            // return the output, don't use if you used NullOutput()
            $converter = new AnsiToHtmlConverter();
            $content = $output->fetch();

            $consoleResponse = $converter->convert($content);
        }
        
        return $this->render('backend/parameter/index.html.twig', [
            'current' => 'config',
            'form' => $form->createView(),
            'consoleResponse' => $consoleResponse,
        ]);
    }
}
