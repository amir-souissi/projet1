<?php

namespace App\Controller\Admin;

use App\Entity\Photo;
use App\Form\PhotoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PhotoController extends AbstractController
{
    #[Route('/admin/photo', name: 'admin_photo')]
    public function index(Request $request,  SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo, [
            'method' => 'POST',
            'action' => $this->generateUrl('admin_photo'),
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('name')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $photo->setName($newFilename);
            }

            // ... persist the $product variable or any other work
            $em->persist($photo);
            $em->flush();

            return $this->redirectToRoute('admin_photo');
        }
        $photos = $em->getRepository(Photo::class)->findAll();
        return $this->render('admin/photo/index.html.twig', [
            'form' => $form->createView(),
            'photos' => $photos
        ]);
    }
}
