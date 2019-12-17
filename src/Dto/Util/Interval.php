<?php


namespace App\Dto\Util;

/**
 * Class Interval
 * @package App\Dto\Util
 */
class Interval
{
    /** @var \DateTime */
    private $start;
    /** @var \DateTime */
    private $end;

    /**
     * Interval constructor.
     * @param \DateTime $start
     * @param \DateTime $end
     */
    public function __construct(\DateTime $start, \DateTime $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return \DateTime
     */
    public function getStart(): \DateTime
    {
        return $this->start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd(): \DateTime
    {
        return $this->end;
    }
}