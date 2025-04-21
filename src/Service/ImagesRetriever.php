<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\FindingParametersDTO;
use App\Entity\User;
use App\Repository\ImagesRepository;

class ImagesRetriever
{
    public function __construct(private readonly ImagesRepository $imagesRepository)
    {
    }

    public function getImages(User $user, FindingParametersDTO $findingParametersDTO): array
    {
        return $this->imagesRepository->getImagesByUser($user, $findingParametersDTO);
    }
}
