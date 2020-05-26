<?php

declare(strict_types=1);

namespace App\Model;

interface NotificationInterface extends PersistableInterface, TimestampableInterface
{
    public const LABEL_NEW = 'new';

    public const LABEL_IMPORTANT = 'important';

    public function getTitle(): ?string;

    public function setTitle(?string $title): void;

    public function getText(): ?string;

    public function setText(?string $text): void;

    public function getUrl(): ?string;

    public function setUrl(?string $url): void;

    public function getLabel(): ?string;

    public function setLabel(?string $label): void;

    public function getUrlTitle(): ?string;

    public function setUrlTitle(?string $urlTitle): void;

    public function isRead(): bool;

    public function setRead(bool $read): void;
}
