<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Model\CourseInterface;
use App\Serializer\Processor\CoverImageProcessor;
use App\Serializer\Processor\ProgressProcessor;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CourseNormalizer implements NormalizerInterface
{
    private $normalizer;
    private $coverImageProcessor;
    private $progressProcessor;

    public function __construct(
        ObjectNormalizer $normalizer,
        CoverImageProcessor $coverImageProcessor,
        ProgressProcessor $progressProcessor
    ) {
        $this->normalizer = $normalizer;
        $this->coverImageProcessor = $coverImageProcessor;
        $this->progressProcessor = $progressProcessor;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $data = $this->coverImageProcessor->process($object, $data);
        $data = $this->progressProcessor->process($object, $data);

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof CourseInterface;
    }
}
