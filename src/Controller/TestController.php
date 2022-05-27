<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class TestController extends AbstractController
{
    #[Route('/test', name: 'test')]
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/mailer', name: 'mailer')]
    public function mailer(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('noreply.isetkl@gmail.com')
            ->to('amirs01@yahoo.fr')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);
        return new Response('sent');
    }

    #[Route('/files')]
    public function createFile(): Response
    {
        $filesystem = new Filesystem();
        try {
            $filesystem->mkdir('./tmp/photos', 0700);
            //test if file to copy exists
            $check = $filesystem->exists(['./tmp/photos/bottle.png']);
            if ($check === true) {
                $filesystem->copy('./tmp/photos/bottle.png', './websites/bottle.png');
                $filesystem->mirror('./tmp', './mes sites');
                dd('done');
            }

            /*$filesystem->mkdir(
                Path::normalize(sys_get_temp_dir().'/'.random_int(0, 1000)),
            ); // permet de créer un dossier dans le dossier tmp du système*/
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your directory at " . $exception->getPath();
        }
        return new Response('File created');
    }
}
