<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use App\Entity\UtilisateurSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Knp\Component\Pager\PaginatorInterface;



/**
 * @extends ServiceEntityRepository<Utilisateur>
 *
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    /**
     * Undocumented variable
     *
     * @var $paginator
     */
    private $paginator;
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Utilisateur::class);
        $this->paginator = $paginator;
    }

    public function save(Utilisateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Utilisateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Utilisateur) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }



    /**
     * cette function permet de calculer le nbre de user
     *
     * @return int
     */
    public function getNbreUser(): int
    {
        // 3. Query how many rows are there in the user table
        $nbUser = $this->createQueryBuilder('u')
            // Filter by some parameter if you want
            // ->where('a.published = 1')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
        return  $nbUser;
    }
    /**
     * cette function permet de paginer les users et rechercher 
     *
     * @param UtilisateurSearch $search
     * @param integer $page
     * @return void
     */
    public function paginateAllUtilisateurs(UtilisateurSearch $search, int $page)
    {
        $query = $this->findAllUtilisateurs();
        if ($search->getNomUtilisateur()) {
            $query = $query
                //andWhere traite une à une les requêtes
                // p.nom  LIKE :nom' signifie que le nom de notre user soit inferieur au user donnees 
                ->andwhere('p.nom  LIKE :nomUtilisateur')
                ->setParameter('nomUtilisateur', '%' . $search->getNomUtilisateur() . '%');
        }
        $Utilisateurs = $this->paginator->paginate(
            $query->getQuery(), //// Requête contenant les données à paginer (ici nos users)
            $page, //// Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 ///nbre de user à afficher
        );
        return $Utilisateurs;
    }


    /**
     * @return  Utilisateur[]
     * les 4 derniers users
     *
     */
    public function findLatestUtilisateurs(): array
    {
        return $this->findAllUtilisateurs()
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    /**
     * cette function permet de génerer la requete pour tous les enrégistrements visible
     *
     * @return QueryBuilder
     */
    private function findAllUtilisateurs(): ORMQueryBuilder
    {
        return $this->createQueryBuilder('p');
    }

    //    /**
    //     * @return Utilisateur[] Returns an array of Utilisateur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Utilisateur
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
