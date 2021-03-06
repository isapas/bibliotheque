<?php

namespace App\Repository;

use App\Entity\Books;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Books|null find($id, $lockMode = null, $lockVersion = null)
 * @method Books|null findOneBy(array $criteria, array $orderBy = null)
 * @method Books[]    findAll()
 * @method Books[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BooksRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Books::class);
    }

    /**
     * @return Books[] Returns an array of Books objects
     */
    public function findByCategory(Category $category = NULL)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.category', 'c')
            ->addSelect('c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $category->getId())
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    
    // public function findBorrowerByUserId(Users $users = NULL): ?Books
    // {
    //     return $this->createQueryBuilder('b')
    //         ->addSelect('u')
    //         ->leftJoin('b.borrower', 'u')
    //         ->andWhere('u.id = :id')
    //         ->setParameter('id', $id)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }

}
