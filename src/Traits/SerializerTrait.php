<?php
declare(strict_types=1);

namespace App\Traits;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

trait SerializerTrait
{
    /**
     * @return mixed
     */
    protected function deserialize(
        mixed $contents,
        string $classname,
        array $context = [AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true]
    ) {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $serializer = new Serializer(
            [
                new ObjectNormalizer($classMetadataFactory, new CamelCaseToSnakeCaseNameConverter(), null, new ReflectionExtractor()),
                new ArrayDenormalizer(),
            ],
            [new JsonEncoder()]
        );

        return $serializer->deserialize($contents, $classname, 'json', $context);
    }

    /**
     * @param mixed $data
     */
    protected function serialize($data): string
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->serialize($data, 'json');
    }
}
