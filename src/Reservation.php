<?php
namespace zrslib;


class Reservation
{
    /**
     * @var \DateTimeInterface
     */
    private $start;
    /**
     * @var \DateTimeInterface
     */
    private $end;
    /**
     * @var string
     */
    private $location;
    /**
     * @var string
     */
    private $activity;



    public function getActivity()
    {
        return $this->activity;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function __construct(\DateTimeInterface $start, \DateTimeInterface $end, $location, $activity)
    {
        $this->activity = $activity;
        $this->end = $end;
        $this->location = $this->location;
        $this->start = $start;
    }
}