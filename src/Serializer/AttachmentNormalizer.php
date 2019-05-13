<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Attachment;
use App\Serializer\Processor\AttachmentHrefProcessor;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class AttachmentNormalizer implements NormalizerInterface
{
    private $normalizer;
    private $attachmentHrefProcessor;

    public function __construct(
        ObjectNormalizer $normalizer,
        AttachmentHrefProcessor $attachmentHrefProcessor
    ) {
        $this->normalizer = $normalizer;
        $this->attachmentHrefProcessor = $attachmentHrefProcessor;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $data = $this->attachmentHrefProcessor->process($object, $data);

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Attachment;
    }
}
