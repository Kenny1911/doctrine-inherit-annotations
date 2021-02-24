<?php

declare(strict_types=1);

namespace Kenny1911\DoctrineInheritAnnotations\Tests\Fixtures;

/**
 * @Annotation
 */
class BarAnnotation
{
    /**
     * @var string
     */
    public $id;

    public static function create(string $id): self
    {
        $annotation = new static();
        $annotation->id = $id;

        return $annotation;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
