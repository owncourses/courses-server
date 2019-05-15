<?php

declare(strict_types=1);

namespace App\Serializer\Processor;

use function array_key_exists;
use function is_array;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

final class AttachmentHrefProcessor
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

    public function process(object $object, array $data): array
    {
        if (is_array($data) && array_key_exists('file_name', $data)) {
            $filePath = $this->uploaderHelper->asset($object, 'file');
            $currentRequest = $this->requestStack->getCurrentRequest();
            if (null !== $filePath && null !== $currentRequest && false === strpos($filePath, '://')) {
                $filePath = $currentRequest->getSchemeAndHttpHost().$filePath;
            }
            $data['href']['download'] = $filePath;
        }

        return $data;
    }
}
