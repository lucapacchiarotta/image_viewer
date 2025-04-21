<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Image;
use App\Entity\User;
use App\Service\FilesUploader;
use App\Service\FindingParametersBuilder;
use App\Service\ImagesRetriever;
use App\Service\ResultPaginator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    public const int CURRENT_USER_ID = 1;
    private User $currentUser;

    /**
     * @throws ORMException
     */
    public function __construct(
        private readonly ImagesRetriever $imagesRetriever,
        private readonly FilesUploader $filesUploader,
        private readonly EntityManagerInterface $entityManager,
        private readonly FindingParametersBuilder $findingParametersBuilder,
    ) {
        $this->currentUser = $this->entityManager->find(User::class, self::CURRENT_USER_ID);
    }

    #[Route(path: '/', name: 'homepage', methods: ['GET'])]
    public function index(Request $request, ResultPaginator $paginator): Response
    {
        $session = $request->getSession();
        $findingParametersDTO = $this->findingParametersBuilder->build($request->query->all(), $session);
        $imagesQuery = $this->imagesRetriever->getImages($this->currentUser, $findingParametersDTO);

        $paginator->paginate($imagesQuery, $request->query->getInt('page', 1));

        return $this->render('images-list.html.twig', [
            'paginator' => $paginator,
            'images' => $paginator->getItems(),
            'show' => $session->get('show') ?? 'table',
            'searchTerms' => $findingParametersDTO->searchTerms,
            'showExcludedImages' => $findingParametersDTO->showExcludedImages,
            'sortField' => $findingParametersDTO->sortField,
            'sortDirection' => $findingParametersDTO->sortDirection,
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

    #[Route(
        path: '/imageexclusion/{image}/{operation}',
        name: 'imageexclusion',
        requirements: [
            'image' => '\d+',
            'operation' => 'add|del',
        ],
        methods: ['GET'])
    ]
    public function manageImageExclusion(Image $image, string $operation): Response
    {
        if ('add' === $operation) {
            $this->currentUser->addExcludedImage($image);
        }

        if ('del' === $operation) {
            $this->currentUser->removeExcludedImage($image);
        }

        $this->entityManager->flush();

        return $this->redirectToRoute('homepage');
    }
}
