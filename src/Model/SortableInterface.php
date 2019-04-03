<?php

declare(strict_types=1);

namespace App\Model;

interface SortableInterface
{
    public function setPosition(int $position): void;

    public function getPosition(): ?int;
}
