<?php

namespace spec\App\Serializer\Processor;

use App\Model\CourseInterface;
use App\Serializer\Processor\CoverImageProcessor;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class CoverImageProcessorSpec extends ObjectBehavior
{
    public function let(
        UploaderHelper $uploaderHelper,
        RequestStack $requestStack,
        Request $request,
        CourseInterface $course
    ): void {
        $uploaderHelper->asset($course, 'coverImageFile')->willReturn('media/6jswX0zcfM.png', 'https://s3.eu-central-1.amazonaws.com/com.owncloud/courses/6jswX0zcfM.png');
        $request->getSchemeAndHttpHost()->willReturn('http://localhost/');
        $requestStack->getCurrentRequest()->willReturn($request);

        $this->beConstructedWith($uploaderHelper, $requestStack);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CoverImageProcessor::class);
    }

    public function it_add_href_when_imagePath_is_set(CourseInterface $course)
    {
        $data = ['cover_image_name' => '6jswX0zcfM.png'];
        $this->process($course, $data)->shouldHaveKeyWithValue('href', ['coverImageUrl' => 'http://localhost/media/6jswX0zcfM.png']);
        $this->process($course, $data)->shouldHaveKeyWithValue('href', ['coverImageUrl' => 'https://s3.eu-central-1.amazonaws.com/com.owncloud/courses/6jswX0zcfM.png']);
    }
}
