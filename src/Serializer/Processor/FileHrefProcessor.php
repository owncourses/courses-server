<?php

declare(strict_types=1);

namespace App\Serializer\Processor;

use function array_key_exists;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

final class FileHrefProcessor
{
    private $uploaderHelper;
    private $requestStack;

    private const SUPPORTED_KEYS = [
        'file_name' => [
            'file' => 'file',
            'property' => 'download',
        ],
        'cover_image_name' => [
            'file' => 'coverImageFile',
            'property' => 'cover_image_url',
        ],
        'picture' => [
            'file' => 'pictureFile',
            'property' => 'picture',
        ],
    ];

    public function __construct(
        UploaderHelper $uploaderHelper,
        RequestStack $requestStack
    ) {
        $this->uploaderHelper = $uploaderHelper;
        $this->requestStack = $requestStack;
    }

    public function process(object $object, array $data): array
    {
        foreach ($data as $key => $value) {
            if (array_key_exists($key, self::SUPPORTED_KEYS)) {
                $filePath = $this->uploaderHelper->asset($object, self::SUPPORTED_KEYS[$key]['file']);
                $currentRequest = $this->requestStack->getCurrentRequest();
                if (null !== $filePath && null !== $currentRequest && false === strpos($filePath, '://')) {
                    $filePath = $currentRequest->getSchemeAndHttpHost().$filePath;
                }
                $data['href'][self::SUPPORTED_KEYS[$key]['property']] = $filePath;
            }
        }

        return $data;
    }
}
