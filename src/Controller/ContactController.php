<?php

namespace App\Controller;

use App\Entity\Contant;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        /*if($request->getMethod() === 'POST') {
            if($request->get('message')) {
                $contact = new Contant();
                $contact->setName($request->get('name'));
                $contact->setEmail($request->get('email'));
                $contact->setSubject($request->get('subject'));
                $contact->setMessage($request->get('message'));
                $contact->setCreatedAt(new \DateTime());
                $em->persist($contact);
                $em->flush();
                $this->addFlash('success', 'Votre message a été bien reçu ! Merci!');
            }else{
                $this->addFlash('danger', 'Le champ message est obligatoire');
            }
        }*/
        $contact = new Contant();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $contact->setCreatedAt(new \DateTime());
            $em->persist($contact);
            $em->flush();
            $this->addFlash('success', 'Votre message a été bien reçu ! Merci!');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
