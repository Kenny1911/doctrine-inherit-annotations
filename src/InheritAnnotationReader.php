<?php

declare(strict_types=1);

namespace Kenny1911\DoctrineInheritAnnotations;

use Doctrine\Common\Annotations\Reader;
use Kenny1911\DoctrineInheritAnnotations\Annotation\Inherit;
use Kenny1911\DoctrineInheritAnnotations\Exception\UnexpectedTypeException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

class InheritAnnotationReader implements Reader
{
    /**
     * @var Reader
     */
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    public function getClassAnnotations(ReflectionClass $class)
    {
        return $this->getAnnotations($class);
    }

    public function getClassAnnotation(ReflectionClass $class, $annotationName)
    {
        return $this->getAnnotation($class, $annotationName);
    }

    public function getMethodAnnotations(ReflectionMethod $method)
    {
        return $this->getAnnotations($method);
    }

    public function getMethodAnnotation(ReflectionMethod $method, $annotationName)
    {
        return $this->getAnnotation($method, $annotationName);
    }

    public function getPropertyAnnotations(ReflectionProperty $property)
    {
        return $this->getAnnotations($property);
    }

    public function getPropertyAnnotation(ReflectionProperty $property, $annotationName)
    {
        return $this->getAnnotation($property, $annotationName);
    }

    /**
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $ref
     *
     * @return object[]
     */
    private function getAnnotations($ref): array
    {
        if ($ref instanceof ReflectionClass) {
            $annotations = $this->reader->getClassAnnotations($ref);
        } elseif ($ref instanceof ReflectionMethod) {
            $annotations = $this->reader->getMethodAnnotations($ref);
        } elseif ($ref instanceof ReflectionProperty) {
            $annotations = $this->reader->getPropertyAnnotations($ref);
        } else {
            throw new UnexpectedTypeException($ref, 'ReflectionClass|ReflectionMethod|ReflectionProperty');
        }

        if ($this->isInherit($ref) && ($parent = $this->getParent($ref))) {
            $annotations = array_merge($annotations, $this->getAnnotations($parent));
        }

        return $annotations;
    }

    /**
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $ref
     * @param class-string $annotationName
     *
     * @return object|null
     */
    private function getAnnotation($ref, string $annotationName)
    {
        foreach ($this->getAnnotations($ref) as $annotation) {
            if ($annotation instanceof $annotationName) {
                return $annotation;
            }
        }

        return null;
    }

    /**
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $ref
     */
    private function isInherit($ref): bool
    {
        if ($ref instanceof ReflectionClass) {
            return (bool) $this->reader->getClassAnnotation($ref, Inherit::class);
        } elseif ($ref instanceof ReflectionMethod) {
            return (bool) $this->reader->getMethodAnnotation($ref, Inherit::class);
        } elseif ($ref instanceof ReflectionProperty) {
            return (bool) $this->reader->getPropertyAnnotation($ref, Inherit::class);
        } else {
            throw new UnexpectedTypeException($ref, 'ReflectionClass|ReflectionMethod|ReflectionProperty');
        }
    }

    /**
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $ref
     *
     * @return ReflectionClass|ReflectionMethod|ReflectionProperty|null
     */
    private function getParent($ref)
    {
        if ($ref instanceof ReflectionClass) {
            return $ref->getParentClass() ?: null;
        } elseif ($ref instanceof ReflectionMethod) {
            $parent = $ref->getDeclaringClass()->getParentClass();

            try {
                return $parent ? $parent->getMethod($ref->getName()) : null;
            } catch (ReflectionException $e) {
                return null;
            }
        } elseif ($ref instanceof ReflectionProperty) {
            $parent = $ref->getDeclaringClass()->getParentClass();

            try {
                return $parent ? $parent->getProperty($ref->getName()) : null;
            } catch (ReflectionException $e) {
                return null;
            }
        } else {
            throw new UnexpectedTypeException($ref, 'ReflectionClass|ReflectionMethod|ReflectionProperty');
        }
    }
}
