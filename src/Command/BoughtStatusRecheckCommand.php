<?php


namespace App\Command;


use App\Dto\Util\Interval;
use App\Producer\BoughtStatusCheckProducer;
use App\Service\ReviewService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class BoughtStatusRecheckCommand
 * @package App\Command
 */
class BoughtStatusRecheckCommand extends Command
{
    const FROM = 'from';
    const TO = 'to';

    /** @var BoughtStatusCheckProducer */
    private $boughtStatusCheckProducer;
    /** @var ReviewService */
    private $reviewService;

    protected function configure()
    {
        parent::configure();

        $this->setName('review:bought_status_check');
        $this->addOption(
            static::FROM, null, InputOption::VALUE_REQUIRED,
            'Date from witch the reviews will be reanalyzed'
        )
        ->addOption(
            static::TO, null, InputOption::VALUE_REQUIRED,
            'Date to witch the reviews will be reanalyzed'
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dateFrom = $input->getOption(static::FROM);
        $dateTo = $input->getOption(static::TO);


        $dateFrom =  null === $dateFrom ? $dateFrom : new \DateTime($dateFrom);
        $dateTo =  null === $dateTo ? $dateFrom : new \DateTime($dateTo);

        $interval = new Interval($dateFrom, $dateTo);

        $result = $this->reviewService->getReviewsInInterval($interval);

        foreach ($result as $item) {
            $output->writeln('Publish review for recheck' . json_encode($item, 128));

            $this->boughtStatusCheckProducer
                ->publish(json_encode($item));
        }
    }

    /**
     * @required
     *
     * @param BoughtStatusCheckProducer $boughtStatusCheckProducer
     * @return BoughtStatusRecheckCommand
     */
    public function setBoughtStatusCheckProducer(BoughtStatusCheckProducer $boughtStatusCheckProducer): BoughtStatusRecheckCommand
    {
        $this->boughtStatusCheckProducer = $boughtStatusCheckProducer;
        return $this;
    }

    /**
     * @required
     *
     * @param ReviewService $reviewService
     * @return BoughtStatusRecheckCommand
     */
    public function setReviewService(ReviewService $reviewService): BoughtStatusRecheckCommand
    {
        $this->reviewService = $reviewService;
        return $this;
    }
}