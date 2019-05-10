<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Model\CourseInterface;
use function array_key_exists;
use function is_array;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class CourseSerializer implements NormalizerInterface
{
    private $uploaderHelper;
    private $normalizer;
    private $requestStack;

    public function __construct(
        UploaderHelper $uploaderHelper,
        ObjectNormalizer $normalizer,
        RequestStack $requestStack
    ) {
        $this->uploaderHelper = $uploaderHelper;
        $this->normalizer = $normalizer;
        $this->requestStack = $requestStack;
    }

    public function normalize($course, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($course, $format, $context);

        if (is_array($data) && array_key_exists('cover_image_name', $data)) {
            $imagePath = $this->uploaderHelper->asset($course, 'coverImageFile');
            $currentRequest = $this->requestStack->getCurrentRequest();
            if (null !== $imagePath && null !== $currentRequest && false === strpos($imagePath, '://')) {
                $imagePath = $currentRequest->getSchemeAndHttpHost().$imagePath;
            }
            $data['href']['coverImageUrl'] = $imagePath;
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof CourseInterface;
    }
}
