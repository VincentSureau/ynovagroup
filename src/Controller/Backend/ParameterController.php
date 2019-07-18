<?php

namespace App\Controller\Backend;

use App\Entity\Config;
use App\Form\ConfigType;
use App\Form\ChangePasswordType;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ParameterController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/configuration", name="parameters")
     */
    public function index(ConfigRepository $repo, Request $request, KernelInterface $kernel)
    {
        // creation and handling of change password form
        $user = $this->getUser();
        $oldPassword = $user->getPassword();
        $passwordForm = $this->createForm(ChangePasswordType::class, $user);
        if ($request->request->has('change_password')) {

            $passwordForm->handleRequest($request);
    
            if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
                $plainPassword = $passwordForm->get('plainPassword')->getData();
                if(is_null($plainPassword)){
                    $encodedPassword = $oldPassword;
                } else {
                    $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
                }

                $user->setPassword($encodedPassword);
    
                $em = $this->getDoctrine()->getManager();
                $em->flush();
    
                $this->addFlash('success','Votre mot de passe a bien été mis à jour');
            }        
        }

        // creation and handling of maintenance mode
        $consoleResponse = '';

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $config = $repo->findConfig();

        if (empty($config)) {
            $config = new Config();
        }

        $form = $this->createForm(ConfigType::class, $config);

        if ($request->request->has('config')) {
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

                $output = new BufferedOutput(
                    OutputInterface::VERBOSITY_NORMAL,
                    true // true for decorated
                );
                $application->run($input, $output);

                // return the output
                $converter = new AnsiToHtmlConverter();
                $content = $output->fetch();

                $consoleResponse = $converter->convert($content);
            }
        }
        
        return $this->render('backend/parameter/index.html.twig', [
            'current' => 'config',
            'passwordForm' => $passwordForm->createView(),
            'form' => $form->createView(),
            'consoleResponse' => $consoleResponse,
        ]);
    }
}
