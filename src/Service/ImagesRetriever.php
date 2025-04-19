<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\ImagesRepository;

class ImagesRetriever
{
    public function __construct(private readonly ImagesRepository $imagesRepository)
    {
    }

    public function getImages(int $userId): array
    {
        $images = $this->imagesRepository->getImagesByUser($userId);

        return $images;
    }
}
