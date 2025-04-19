<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Image;
use App\Service\FilesUploader;
use App\Service\FindingParametersBuilder;
use App\Service\ImagesRetriever;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    public const int CURRENT_USER_ID = 1;

    public function __construct(
        private readonly ImagesRetriever $imagesRetriever,
        private readonly FilesUploader $filesUploader,
        private readonly EntityManagerInterface $entityManager,
        private readonly FindingParametersBuilder $findingParametersBuilder,
    ) {
    }

    #[Route(path: '/', name: 'homepage', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        $findingParametersDTO = $this->findingParametersBuilder->build($request->query->all(), $session);
        $images = $this->imagesRetriever->getImages(self::CURRENT_USER_ID, $findingParametersDTO);

        return $this->render('images-list.html.twig', [
            'images' => $images,
            'show' => $session->get('show') ?? 'table',
            'searchTerms' => $findingParametersDTO->searchTerms,
        ]);
    }

    #[Route(path: '/show/{type}', methods: ['GET'])]
    public function setShowingMode(Request $request, string $type = ''): Response
    {
        if (in_array($type, ['grid', 'table'], true)) {
            $session = $request->getSession();
            $session->set('show', $type);
        }

        return $this->redirectToRoute('homepage');
    }

    #[Route(path: '/upload', name: 'uploadPhoto', methods: ['POST'])]
    public function uploadImage(Request $request): Response
    {
        $pathToSaveImage = $this->getParameter('kernel.project_dir').'/public/images/';

        try {
            $fileName = $this->filesUploader->uploadFile($request, 'photo', $pathToSaveImage);
            if (null !== $fileName) {
                $title = $request->request->get('title');
                $image = Image::createFromData($fileName, $title);
                $this->entityManager->persist($image);
                $this->entityManager->flush();
            }
        } catch (\Exception) {
        }

        return $this->redirectToRoute('homepage');
    }
}
