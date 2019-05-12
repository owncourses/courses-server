<?php

declare(strict_types=1);

namespace App\Serializer\Processor;

use function array_key_exists;
use function is_array;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class CoverImageProcessor
{
    private $uploaderHelper;
    private $requestStack;

    public function __construct(
        UploaderHelper $uploaderHelper,
        RequestStack $requestStack
    ) {
        $this->uploaderHelper = $uploaderHelper;
        $this->requestStack = $requestStack;
    }

    public function process($object, array $data): array
    {
        if (is_array($data) && array_key_exists('cover_image_name', $data)) {
            $imagePath = $this->uploaderHelper->asset($object, 'coverImageFile');
            $currentRequest = $this->requestStack->getCurrentRequest();
            if (null !== $imagePath && null !== $currentRequest && false === strpos($imagePath, '://')) {
                $imagePath = $currentRequest->getSchemeAndHttpHost().$imagePath;
            }
            $data['href']['coverImageUrl'] = $imagePath;
        }

        return $data;
    }
}
