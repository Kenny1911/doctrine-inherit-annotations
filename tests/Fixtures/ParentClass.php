<?php

declare(strict_types=1);

namespace Kenny1911\DoctrineInheritAnnotations\Tests\Fixtures;

use Kenny1911\DoctrineInheritAnnotations\Annotation\Inherit;

/**
 * @FooAnnotation("ParentClass-foo1")
 * @FooAnnotation("ParentClass-foo2")
 * @BarAnnotation("ParentClass-bar1")
 * @BarAnnotation("ParentClass-bar2")
 *
 * @Inherit()
 */
class ParentClass
{
    /**
     * @FooAnnotation("foo-foo1")
     * @FooAnnotation("foo-foo2")
     * @BarAnnotation("foo-bar1")
     * @BarAnnotation("foo-bar2")
     *
     * @Inherit()
     */
    public $foo;

    /**
     * @FooAnnotation("bar-foo1")
     * @FooAnnotation("bar-foo2")
     * @BarAnnotation("bar-bar1")
     * @BarAnnotation("bar-bar2")
     *
     * @Inherit()
     */
    public $bar;

    /**
     * @FooAnnotation("foo-foo1")
     * @FooAnnotation("foo-foo2")
     * @BarAnnotation("foo-bar1")
     * @BarAnnotation("foo-bar2")
     *
     * @Inherit()
     */
    public function foo()
    {
        return $this->foo;
    }

    /**
     * @FooAnnotation("bar-foo1")
     * @FooAnnotation("bar-foo2")
     * @BarAnnotation("bar-bar1")
     * @BarAnnotation("bar-bar2")
     *
     * @Inherit()
     */
    public function bar()
    {
        return $this->bar;
    }
}
