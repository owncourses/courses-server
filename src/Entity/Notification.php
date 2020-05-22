<?php

namespace App\Entity;

use App\Model\NotificationInterface;
use App\Model\PersistableAwareTrait;
use App\Model\TimestampableAwareTrait;

class Notification implements NotificationInterface
{
    use TimestampableAwareTrait;
    use PersistableAwareTrait;

    private ?string $title = null;

    private ?string $text = null;

    private ?string $url = null;

    private ?string $label = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

}
