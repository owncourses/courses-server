<?php

declare(strict_types=1);

namespace App\Model;

interface TimestampableInterface
{
    public function getCreated(): \DateTimeInterface;

    public function setCreated(\DateTimeInterface $created): void;

    public function getUpdated(): ?\DateTimeInterface;

    public function setUpdated(\DateTimeInterface $updated): void;
}
