<?php

namespace App\Repository;

use App\Entity\Bien;
use App\Entity\BienSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Knp\Component\Pager\PaginatorInterface;




/**
 * @extends ServiceEntityRepository<Bien>
 *
 * @method Bien|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bien|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bien[]    findAll()
 * @method Bien[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BienRepository extends ServiceEntityRepository
{
    /**
     * Undocumented variable
     *
     * @var $paginator
     */
    private $paginator;
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Bien::class);
        $this->paginator = $paginator;
    }

    /**
     * Cette methode permet de persister les entités dans la base de données
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Bien $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Cette methode permet de supprimer des entités 
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Bien $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * cette function permet de calculer le nbre de total de bien
     *
     * @return int
     */
    public function getNbreBien(): int
    {
        // 3. Query how many rows are there in the Articles table
        $nbBiens = $this->createQueryBuilder('d')
            ->select('count(d.id)')
            ->getQuery()
            ->getSingleScalarResult();
        return  $nbBiens;
    }



    /**
     * cette function permet de calculer le nbre de bien en favoris
     *
     * @return int
     */
    public function getNbreBienEnFavoris(): int
    {
        // 3. Query how many rows are there in the Articles table
        $nbBiens = $this->createQueryBuilder('d')
            ->select('count(d.id)')
            ->where('d.isFavoris=true')
            ->getQuery()
            ->getSingleScalarResult();
        return  $nbBiens;
    }

    /**
     * cette function permet de calculer le nbre de bien non en favoris
     *
     * @return int
     */
    public function getNbreBienNonFavoris(): int
    {
        // 3. Query how many rows are there in the Articles table
        $nbBiens = $this->createQueryBuilder('d')
            ->select('count(d.id)')
            ->where('d.isFavoris=false')
            ->getQuery()
            ->getSingleScalarResult();
        return  $nbBiens;
    }


    /**
     * Cette function permet de paginer les  biens et d'effectuer une recherche multicritère
     */

    public function paginateAllVisibleQuery(BienSearch $search, int $page)
    {
        $query = $this->findVisibleQuery();
        if ($search->getMaxPrice()) {
            $query = $query
                //andWhere traite une à une les requêtes
                // p.prix<=:maxprice signifie que le prix de notre bien soit inferieur au prix données
                ->andwhere('p.prix<=:maxprice')
                ->setParameter('maxprice', $search->getMaxPrice());
        }
        if ($search->getTitre()) {
            $query = $query
                //andWhere traite une à une les requêtes
                // p.nom<=:maxprice signifie que le nom de notre bien soit inferieur au prix données
                ->andwhere('p.titre  LIKE :titreBien')
                ->setParameter('titreBien', '%' . $search->getTitre() . '%');
        }
        if ($search->getMinSurface()) {
            $query = $query
                ->andwhere('p.surface>= :minsurface')
                ->setParameter('minsurface', $search->getMinSurface());
        }

        $biens = $this->paginator->paginate(
            $query->getQuery(), //// Requête contenant les données à paginer (ici nos properties)
            $page, //// Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            12
        );
        //  $pictures = $this->findForProperties($properties->getItems());
        return $biens;
    }

    /**
     * cette function permet de recuperer les 3 dernièrs biens
     *@param Bien $bien
     *@return Bien
     */
    public function findLatestBien(): array
    {
        return $this->findVisibleQuery()
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }


    /**
     * cette function permet de génerer la requete pour tous les enrégistrements visible et aficher les biens en favoris 
     *@param Bien $bien
     *@return Bien
     */
    public function getBienEnFavoris(): array
    {
        return $this->findBienEnFavoris()
            ->getQuery()
            ->getResult();
    }
    /**
     * cette function permet de génerer la requete pour tous les enrégistrements visible et aficher les biens en favoris avec querybuilder
     * @return QueryBuilder
     */
    private function findBienEnFavoris(): ORMQueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where('p.isFavoris=true');
    }

    /**
     * cette function permet de génerer la requete pour tous les enrégistrements visible et aficher les biens 
     *
     * @return QueryBuilder
     */
    private function findVisibleQuery(): ORMQueryBuilder
    {
        return $this->createQueryBuilder('p');
        //->where('p.isFavoris=false');
    }


    // /**
    //  * @return Bien[] Returns an array of Bien objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bien
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
