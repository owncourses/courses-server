<?php

namespace spec\App\Serializer\Processor;

use App\Model\AttachmentInterface;
use App\Serializer\Processor\AttachmentHrefProcessor;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class AttachmentHrefProcessorSpec extends ObjectBehavior
{
    public function let(
        UploaderHelper $uploaderHelper,
        RequestStack $requestStack,
        Request $request,
        AttachmentInterface $attachment
    ): void {
        $uploaderHelper->asset($attachment, 'file')->willReturn('media/6jswX0zcfM.png', 'https://s3.eu-central-1.amazonaws.com/com.owncloud/courses/6jswX0zcfM.png');
        $request->getSchemeAndHttpHost()->willReturn('http://localhost/');
        $requestStack->getCurrentRequest()->willReturn($request);

        $this->beConstructedWith($uploaderHelper, $requestStack);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AttachmentHrefProcessor::class);
    }

    public function it_add_href_when_fil_name_is_set(AttachmentInterface $attachment)
    {
        $data = ['file_name' => '6jswX0zcfM.png'];
        $this->process($attachment, $data)->shouldHaveKeyWithValue('href', ['download' => 'http://localhost/media/6jswX0zcfM.png']);
        $this->process($attachment, $data)->shouldHaveKeyWithValue('href', ['download' => 'https://s3.eu-central-1.amazonaws.com/com.owncloud/courses/6jswX0zcfM.png']);
    }
}
