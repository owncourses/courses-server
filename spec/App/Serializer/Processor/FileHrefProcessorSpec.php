<?php

namespace spec\App\Serializer\Processor;

use App\Model\AttachmentInterface;
use App\Model\CourseInterface;
use App\Serializer\Processor\FileHrefProcessor;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class FileHrefProcessorSpec extends ObjectBehavior
{
    public function let(
        UploaderHelper $uploaderHelper,
        RequestStack $requestStack,
        Request $request,
        CourseInterface $course,
        AttachmentInterface $attachment
    ): void {
        $uploaderHelper->asset($attachment, 'file')->willReturn('media/6jswX0zcfM.png', 'https://s3.eu-central-1.amazonaws.com/com.owncloud/courses/6jswX0zcfM.png');
        $uploaderHelper->asset($course, 'coverImageFile')->willReturn('media/6jswX0zcfM.png', 'https://s3.eu-central-1.amazonaws.com/com.owncloud/courses/6jswX0zcfM.png');
        $request->getSchemeAndHttpHost()->willReturn('http://localhost/');
        $requestStack->getCurrentRequest()->willReturn($request);

        $this->beConstructedWith($uploaderHelper, $requestStack);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FileHrefProcessor::class);
    }

    public function it_add_href_when_imagePath_is_set(CourseInterface $course)
    {
        $data = ['cover_image_name' => '6jswX0zcfM.png'];
        $this->process($course, $data)->shouldHaveKeyWithValue('href', ['cover_image_url' => 'http://localhost/media/6jswX0zcfM.png']);
        $this->process($course, $data)->shouldHaveKeyWithValue('href', ['cover_image_url' => 'https://s3.eu-central-1.amazonaws.com/com.owncloud/courses/6jswX0zcfM.png']);
    }

    public function it_add_href_when_fil_name_is_set(AttachmentInterface $attachment)
    {
        $data = ['file_name' => '6jswX0zcfM.png'];
        $this->process($attachment, $data)->shouldHaveKeyWithValue('href', ['download' => 'http://localhost/media/6jswX0zcfM.png']);
        $this->process($attachment, $data)->shouldHaveKeyWithValue('href', ['download' => 'https://s3.eu-central-1.amazonaws.com/com.owncloud/courses/6jswX0zcfM.png']);
    }
}
