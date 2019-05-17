<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Attachment;
use App\Serializer\Processor\FileHrefProcessor;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class AttachmentNormalizer implements NormalizerInterface
{
    private $normalizer;
    private $fileHrefProcessor;

    public function __construct(
        ObjectNormalizer $normalizer,
        FileHrefProcessor $fileHrefProcessor
    ) {
        $this->normalizer = $normalizer;
        $this->fileHrefProcessor = $fileHrefProcessor;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $data = $this->fileHrefProcessor->process($object, $data);

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Attachment;
    }
}
