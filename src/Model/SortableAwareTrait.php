<?php

declare(strict_types=1);

namespace App\Model;

trait SortableAwareTrait
{
    private $position = -1;

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }
}
