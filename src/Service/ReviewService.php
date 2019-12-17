<?php


namespace App\Service;


use App\Dto\Util\Interval;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ReviewService
 * @package App\Service
 */
class ReviewService
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param Interval $interval
     * @return array
     * @throws DBALException
     */
    public function getReviewsInInterval(Interval $interval): array
    {
        $stm =  $this->entityManager
            ->getConnection()
            ->prepare(
                "   
                    SELECT 
                    efn.id as review_id,
                    efn.customer_id as customer_id,
                    efn.product_id as product_doc_id,
                    UNIX_TIMESTAMP(efn.created) as check_up_to
                    from emag_feedback.eos_feedback_new efn where efn.`type` = 'REVIEW' and UNIX_TIMESTAMP(efn.created) > :start
                    and UNIX_TIMESTAMP(efn.created) < :end
                "
            );

        $stm->bindParam('start', $interval->getStart()->getTimestamp());
        $stm->bindParam('end', $interval->getEnd()->getTimestamp());

        $stm->execute();

        return $stm->fetch(FetchMode::ASSOCIATIVE);
    }

    /**
     * @required
     *
     * @param EntityManagerInterface $entityManager
     * @return ReviewService
     */
    public function setEntityManager(EntityManagerInterface $entityManager): ReviewService
    {
        $this->entityManager = $entityManager;
        return $this;
    }
}