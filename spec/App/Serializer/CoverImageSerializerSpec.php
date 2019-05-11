<?php

namespace spec\App\Serializer;

use App\Model\CourseInterface;
use App\Serializer\CoverImageSerializer;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class CoverImageSerializerSpec extends ObjectBehavior
{
    public function let(UploaderHelper $uploaderHelper,
        ObjectNormalizer $normalizer,
        RequestStack $requestStack,
        Request $request,
        CourseInterface $course
    ) {
        $normalizer->normalize($course, null, [])->willReturn(['cover_image_name' => '6jswX0zcfM.png']);
        $uploaderHelper->asset($course, 'coverImageFile')->willReturn('media/6jswX0zcfM.png', 'https://s3.eu-central-1.amazonaws.com/com.owncloud/courses/6jswX0zcfM.png');
        $request->getSchemeAndHttpHost()->willReturn('http://localhost/');
        $requestStack->getCurrentRequest()->willReturn($request);

        $this->beConstructedWith($uploaderHelper, $normalizer, $requestStack);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CoverImageSerializer::class);
    }

    public function it_supports_normalization(CourseInterface $course)
    {
        $this->supportsNormalization(null)->shouldReturn(false);
        $this->supportsNormalization(new \StdClass())->shouldReturn(false);
        $this->supportsNormalization($course)->shouldReturn(true);
    }

    public function it_add_href_when_imagePath_is_set(CourseInterface $course)
    {
        $this->normalize($course)->shouldHaveKeyWithValue('href', ['coverImageUrl' => 'http://localhost/media/6jswX0zcfM.png']);
        $this->normalize($course)->shouldHaveKeyWithValue('href', ['coverImageUrl' => 'https://s3.eu-central-1.amazonaws.com/com.owncloud/courses/6jswX0zcfM.png']);
    }
}
