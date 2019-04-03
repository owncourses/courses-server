<?php

declare(strict_types=1);

namespace App\Model;

trait TimestampableAwareTrait
{
    private $created;

    private $updated;

    public function getCreated(): \DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): void
    {
        $this->created = $created;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): void
    {
        $this->updated = $updated;
    }
}
