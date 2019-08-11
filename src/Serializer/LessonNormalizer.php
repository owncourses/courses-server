<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Model\LessonInterface;
use App\Serializer\Processor\FileHrefProcessor;
use App\Serializer\Processor\LessonCompletedProcessor;
use App\Serializer\Processor\LessonPaginationProcessor;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class LessonNormalizer implements NormalizerInterface
{
    private $normalizer;
    private $fileHrefProcessor;
    private $lessonCompletedProcessor;
    private $lessonPaginationProcessor;

    public function __construct(
        ObjectNormalizer $normalizer,
        FileHrefProcessor $fileHrefProcessor,
        LessonCompletedProcessor $lessonCompletedProcessor,
        LessonPaginationProcessor $lessonPaginationProcessor
    ) {
        $this->normalizer = $normalizer;
        $this->fileHrefProcessor = $fileHrefProcessor;
        $this->lessonCompletedProcessor = $lessonCompletedProcessor;
        $this->lessonPaginationProcessor = $lessonPaginationProcessor;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $data = $this->fileHrefProcessor->process($object, $data);
        $data = $this->lessonCompletedProcessor->process($object, $data);
        $data = $this->lessonPaginationProcessor->process($object, $data);

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof LessonInterface;
    }
}
