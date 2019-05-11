<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Model\CourseInterface;
use App\Model\LessonInterface;
use App\Repository\UserLessonRepositoryInterface;
use function array_key_exists;
use function is_array;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class CoverImageSerializer implements NormalizerInterface
{
    private $uploaderHelper;
    private $normalizer;
    private $requestStack;
    private $userLessonRepository;
    private $security;

    public function __construct(
        UploaderHelper $uploaderHelper,
        UserLessonRepositoryInterface $userLessonRepository,
        ObjectNormalizer $normalizer,
        Security $security,
        RequestStack $requestStack
    ) {
        $this->uploaderHelper = $uploaderHelper;
        $this->userLessonRepository = $userLessonRepository;
        $this->normalizer = $normalizer;
        $this->security = $security;
        $this->requestStack = $requestStack;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        if (is_array($data) && array_key_exists('cover_image_name', $data)) {
            $imagePath = $this->uploaderHelper->asset($object, 'coverImageFile');
            $currentRequest = $this->requestStack->getCurrentRequest();
            if (null !== $imagePath && null !== $currentRequest && false === strpos($imagePath, '://')) {
                $imagePath = $currentRequest->getSchemeAndHttpHost().$imagePath;
            }
            $data['href']['coverImageUrl'] = $imagePath;
        }

        if ($object instanceof LessonInterface) {
            $userLesson = $this->userLessonRepository->findOneBy(
                ['user' => $this->security->getUser(), 'lesson' => $object]
            );

            if (null === $userLesson) {
                $data['completed'] = null;
            } else {
                $data['completed'] = $userLesson->getCompleted();
            }
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof CourseInterface || $data instanceof LessonInterface;
    }
}
