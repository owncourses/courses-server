<?php

declare(strict_types=1);

namespace App\Model;

trait VisibilityAwareTrait
{
    private $visible;

    public function isVisible(): bool
    {
        return true === $this->visible;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }
}
