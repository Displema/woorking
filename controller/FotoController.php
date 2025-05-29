<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Model\EFoto;

class FotoController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/foto/{id}', name: 'showphoto', requirements: ['id' => '\d+'])]
    public function showphoto(int $id)
    {
        //take the photo from the DB
        $foto = $this->em->getRepository(EFoto::class)->find($id);

        if (!$foto) {
            throw $this->createNotFoundException("Foto not found");

        }

        $blob = $foto->getContent();
        $mimeType = $foto->getMimeType();

        $response = new Response($blob);
        $response->headers->set('Content-Type', $mimeType);

        return $response;
    }
}
