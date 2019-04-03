<?php

declare(strict_types=1);

namespace App\Model;

interface PersistableInterface
{
    public function getId(): ?string;

    public function setId(string $id): void;
}
