<?php

declare(strict_types=1);

namespace Kenny1911\DoctrineInheritAnnotations\Tests\Fixtures;

use Kenny1911\DoctrineInheritAnnotations\Annotation\Inherit;

/**
 * @FooAnnotation("ChildClass-foo1")
 *
 * @Inherit()
 */
class ChildClass extends ParentClass
{
    /**
     * @FooAnnotation("foo-foo3")
     *
     * @Inherit()
     */
    public $foo;

    /**
     * @FooAnnotation("baz-foo1")
     *
     * @Inherit()
     */
    public $baz;

    /**
     * @FooAnnotation("foo-foo3")
     *
     * @Inherit()
     */
    public function foo()
    {
        parent::foo();
    }

    /**
     * @FooAnnotation("baz-foo1")
     *
     * @Inherit()
     */
    public function baz()
    {
        return $this->baz;
    }
}
