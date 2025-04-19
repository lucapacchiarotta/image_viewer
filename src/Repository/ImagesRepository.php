<?php

namespace App\Repository;

use App\DTO\FindingParametersDTO;
use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Image>
 */
class ImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    public function getImagesByUser(int $userId, FindingParametersDTO $findingParametersDTO): array
    {
        $qb = $this->createQueryBuilder('i')
            ->select('i');

        if (null !== $findingParametersDTO->searchTerms) {
            $qb
                ->andWhere('i.title LIKE :searchTerms')
                ->setParameter('searchTerms', '%'.$findingParametersDTO->searchTerms.'%');
        }

        return $qb
            ->orderBy("i.{$findingParametersDTO->sortField}", $findingParametersDTO->sortDirection)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
