<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Twilio\Rest\Client;

/**
 * @extends ServiceEntityRepository<Evenement>
 *
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    public function save(Evenement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Evenement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function getParticipationByEvenement($id)  {
        $qb= $this->createQueryBuilder('participation')
            ->join('participation.evenement','event')
            ->addSelect('event')
            ->where('event.id=:id')
            ->setParameter('id',$id);
        return $qb->getQuery()
            ->getResult();
    }

    public function sms(){
        // Your Account SID and Auth Token from twilio.com/console
        $sid = 'AC91ce07546c98d7ef7de4f4d065364139';
        $auth_token = '94b0342e9b182091a8ce0f768699eb3d';

        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
        // A Twilio number you own with SMS capabilities
        $twilio_number = "+12762849300";

        $client = new Client($sid, $auth_token);
        $client->messages->create(
        // the number you'd like to send the message to
            '+21627839355',
            [
                // A Twilio phone number you purchased at twilio.com/console
                'from' => '+15675220383',

                // the body of the text message you'd like to send
                'body' => 'Un evenement a été ajoutée'
            ]
        );
    }

//    /**
//     * @return Evenement[] Returns an array of Evenement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Evenement
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
