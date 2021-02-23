<?php

declare(strict_types=1);

namespace Kenny1911\DoctrineInheritAnnotations\Tests\Fixtures;

/**
 * @FooAnnotation("ChildClass-foo1")
 */
class ChildNotInheritClass extends ParentClass
{
    /**
     * @FooAnnotation("foo-foo3")
     */
    public $foo;

    /**
     * @FooAnnotation("baz-foo1")
     */
    public $baz;

    /**
     * @FooAnnotation("foo-foo3")
     */
    public function foo()
    {
        parent::foo();
    }

    /**
     * @FooAnnotation("baz-foo1")
     */
    public function baz()
    {
        return $this->baz;
    }
}
