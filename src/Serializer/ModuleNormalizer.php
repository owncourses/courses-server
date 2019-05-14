<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Model\ModuleInterface;
use App\Serializer\Processor\ModuleProgressProcessor;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ModuleNormalizer implements NormalizerInterface
{
    private $normalizer;
    private $moduleProgressProcessor;

    public function __construct(
        ObjectNormalizer $normalizer,
        ModuleProgressProcessor $moduleProgressProcessor
    ) {
        $this->normalizer = $normalizer;
        $this->moduleProgressProcessor = $moduleProgressProcessor;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $data = $this->moduleProgressProcessor->process($object, $data);

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof ModuleInterface;
    }
}
