<?php

declare(strict_types=1);

namespace App\Model;

interface VisibilityInterface
{
    public function isVisible(): bool;

    public function getVisible(): ?bool;

    public function setVisible(bool $visible): void;
}
