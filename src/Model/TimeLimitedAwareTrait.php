<?php

declare(strict_types=1);

namespace App\Model;

use DateTime;
use DateTimeInterface;

trait TimeLimitedAwareTrait
{
    protected $startDate;

    protected $endDate;

    protected $active;

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function isActive(): bool
    {
        $today = new DateTime();
        $afterStart = true;
        $beforeEnd = true;
        if ($this->startDate instanceof DateTimeInterface) {
            $afterStart = $today->getTimestamp() > $this->startDate->getTimestamp();
        }

        if ($this->endDate instanceof DateTimeInterface) {
            $beforeEnd = $today->getTimestamp() < $this->endDate->getTimestamp();
        }

        return $afterStart && $beforeEnd;
    }
}
