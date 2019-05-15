<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Model\ModuleInterface;
use App\Serializer\Processor\ProgressProcessor;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ModuleNormalizer implements NormalizerInterface
{
    private $normalizer;
    private $progressProcessor;

    public function __construct(
        ObjectNormalizer $normalizer,
        ProgressProcessor $progressProcessor
    ) {
        $this->normalizer = $normalizer;
        $this->progressProcessor = $progressProcessor;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $data = $this->progressProcessor->process($object, $data);

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof ModuleInterface;
    }
}
