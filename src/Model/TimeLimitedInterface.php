<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeInterface;

interface TimeLimitedInterface
{
    public function getStartDate(): ?DateTimeInterface;

    public function setStartDate(DateTimeInterface $startDate): void;

    public function getEndDate(): ?DateTimeInterface;

    public function setEndDate(DateTimeInterface $endDate): void;

    public function isActive(): bool;
}
