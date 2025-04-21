<?php

namespace App\Repository;

use App\DTO\FindingParametersDTO;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Image>
 */
class ImagesRepository extends ServiceEntityRepository
{
    private FindingParametersDTO $findingParametersDTO;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    public function getImagesByUser(User $user, FindingParametersDTO $findingParametersDTO): Query
    {
        $qb = $this->createQueryBuilder('i')
            ->select('i', 'u')
            ->leftJoin('i.excludedUsers', 'u');

        if (null !== $findingParametersDTO->searchTerms) {
            $qb
                ->andWhere('i.title LIKE :searchTerms')
                ->setParameter('searchTerms', '%'.$findingParametersDTO->searchTerms.'%');
        }

        if ('0' === $findingParametersDTO->showExcludedImages) {
            $qb->andWhere('u.username IS NULL');
        }

        if (!empty($findingParametersDTO->sortField) && !empty($findingParametersDTO->sortDirection)) {
            $qb->orderBy("i.{$findingParametersDTO->sortField}", $findingParametersDTO->sortDirection);
        }

        return $qb
            ->setMaxResults(10)
            ->getQuery();
    }

    public function save(Image $image, bool $flush = false): void
    {
        $this->getEntityManager()->persist($image);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }
}
