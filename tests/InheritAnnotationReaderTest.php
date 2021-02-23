<?php

declare(strict_types=1);

namespace Kenny1911\DoctrineInheritAnnotations\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Kenny1911\DoctrineInheritAnnotations\Annotation\Inherit;
use Kenny1911\DoctrineInheritAnnotations\Exception\UnexpectedTypeException;
use Kenny1911\DoctrineInheritAnnotations\InheritAnnotationReader;
use Kenny1911\DoctrineInheritAnnotations\Tests\Fixtures\BarAnnotation;
use Kenny1911\DoctrineInheritAnnotations\Tests\Fixtures\ChildClass;
use Kenny1911\DoctrineInheritAnnotations\Tests\Fixtures\ChildNotInheritClass;
use Kenny1911\DoctrineInheritAnnotations\Tests\Fixtures\FooAnnotation;
use Kenny1911\DoctrineInheritAnnotations\Tests\Fixtures\ParentClass;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

class InheritAnnotationReaderTest extends TestCase
{
    private $reader;

    protected function setUp(): void
    {
        $this->reader = new InheritAnnotationReader(new AnnotationReader());
    }

    /**
     * @param object $object
     *
     * @dataProvider dataGetClassAnnotations
     */
    public function testGetClassAnnotations($object, array $expected)
    {
        $this->assertSame(
            $expected,
            $this->prepareAnnotations(
                $this->reader->getClassAnnotations(new ReflectionClass($object))
            )
        );
    }

    public function dataGetClassAnnotations(): array
    {
        return [
            'inherit' => [new ChildClass(), ['ChildClass-foo1', 'inherit', 'ParentClass-foo1', 'ParentClass-foo2', 'ParentClass-bar1', 'ParentClass-bar2', 'inherit']],
            'not inherit' => [new ChildNotInheritClass(), ['ChildClass-foo1']],
            'no parent' => [new ParentClass(), ['ParentClass-foo1', 'ParentClass-foo2', 'ParentClass-bar1', 'ParentClass-bar2', 'inherit']]
        ];
    }

    /**
     * @param object $object
     *
     * @dataProvider dataGetClassAnnotation
     */
    public function testGetClassAnnotation($object, string $annotationName, ?string $expected)
    {
        $annotation =  $this->reader->getClassAnnotation(new ReflectionClass($object), $annotationName);

        $this->assertSame($expected, $annotation ? strval($annotation) : null);
    }

    public function dataGetClassAnnotation(): array
    {
        return [
            'inherit' => [new ChildClass(), FooAnnotation::class, 'ChildClass-foo1'],
            'inherit contain in parent' => [new ChildClass(), BarAnnotation::class, 'ParentClass-bar1'],
            'not inherit' => [new ChildNotInheritClass(), BarAnnotation::class, null],
        ];
    }

    /**
     * @param object $object
     * @throws ReflectionException
     *
     * @dataProvider dataGetMethodAnnotations
     */
    public function testGetMethodAnnotations($object, string $method, array $expected)
    {
        $this->assertSame(
            $expected,
            $this->prepareAnnotations(
                $this->reader->getMethodAnnotations(new ReflectionMethod($object, $method))
            )
        );
    }

    public function dataGetMethodAnnotations(): array
    {
        return [
            'inherit' => [new ChildClass(), 'foo', ['foo-foo3', 'inherit', 'foo-foo1', 'foo-foo2', 'foo-bar1', 'foo-bar2', 'inherit']],
            'not inherit' => [new ChildNotInheritClass(), 'foo', ['foo-foo3']],
            'no parent' => [new ChildClass(), 'baz', ['baz-foo1', 'inherit']]
        ];
    }

    /**
     * @param object $object
     * @throws ReflectionException
     *
     * @dataProvider dataGetMethodAnnotation
     */
    public function testGetMethodAnnotation($object, string $method, string $annotationName, ?string $expected)
    {
        $annotation = $this->reader->getMethodAnnotation(new ReflectionMethod($object, $method), $annotationName);

        $this->assertSame($expected, $annotation ? strval($annotation) : null);
    }

    public function dataGetMethodAnnotation(): array
    {
        return [
            'inherit' => [new ChildClass(), 'foo', FooAnnotation::class, 'foo-foo3'],
            'inherit contain in parent' => [new ChildClass(), 'foo', BarAnnotation::class, 'foo-bar1'],
            'not inherit' => [new ChildNotInheritClass(), 'foo', BarAnnotation::class, null],
        ];
    }

    /**
     * @param object $object
     * @throws ReflectionException
     *
     * @dataProvider dataGetPropertyAnnotations
     */
    public function testGetPropertyAnnotations($object, string $property, array $expected)
    {
        $this->assertSame(
            $expected,
            $this->prepareAnnotations(
                $this->reader->getPropertyAnnotations(new ReflectionProperty($object, $property))
            )
        );
    }

    public function dataGetPropertyAnnotations(): array
    {
        return [
            'inherit' => [new ChildClass(), 'foo', ['foo-foo3', 'inherit', 'foo-foo1', 'foo-foo2', 'foo-bar1', 'foo-bar2', 'inherit']],
            'not inherit' => [new ChildNotInheritClass(), 'foo', ['foo-foo3']],
            'no parent' => [new ChildClass(), 'baz', ['baz-foo1', 'inherit']]
        ];
    }

    /**
     * @param object $object
     * @throws ReflectionException
     *
     * @dataProvider dataGetPropertyAnnotation
     */
    public function testGetPropertyAnnotation($object, string $property, string $annotationName, ?string $expected)
    {
        $annotation = $this->reader->getPropertyAnnotation(new ReflectionProperty($object, $property), $annotationName);

        $this->assertSame($expected, $annotation ? strval($annotation) : null);
    }

    public function dataGetPropertyAnnotation(): array
    {
        return [
            'inherit' => [new ChildClass(), 'foo', FooAnnotation::class, 'foo-foo3'],
            'inherit contain in parent' => [new ChildClass(), 'foo', BarAnnotation::class, 'foo-bar1'],
            'not inherit' => [new ChildNotInheritClass(), 'foo', BarAnnotation::class, null],
        ];
    }

    /**
     * @throws ReflectionException
     *
     * @dataProvider dataUnexpectedType
     */
    public function testUnexpectedType(string $method)
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "ReflectionClass|ReflectionMethod|ReflectionProperty", "string" given');

        $method = new ReflectionMethod($this->reader, $method);
        $method->setAccessible(true);

        $method->invoke($this->reader, 'invalidValue');
    }

    public function dataUnexpectedType(): array
    {
        return [
            ['getAnnotations'],
            ['isInherit'],
            ['getParent'],
        ];
    }

    private function prepareAnnotations(array $annotations): array
    {
        return array_map(
            function($annotation) {
                if ($annotation instanceof Inherit) {
                    return 'inherit';
                }

                return strval($annotation);
            },
            $annotations
        );
    }
}
