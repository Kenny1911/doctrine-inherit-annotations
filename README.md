# InheritAnnotationReader

`InheritAnnotationReader` - is implementation of `Reader` interface from `doctrine/annotations` package, that support
inherit annotations from parent class. To do this, you must specify
`@Kenny1911\DoctrineInheritAnnotations\Annotation\Inherit` annotation, like as `@inheritDoc` from `PHPDoc`.

## New InheritAnnotationReader instance

`InheritAnnotationReader` instance is decorate original `Reader`:

```php
use Doctrine\Common\Annotations\AnnotationReader;
use Kenny1911\DoctrineInheritAnnotations\InheritAnnotationReader;

$reader = new AnnotationReader(); // Original annotation reader

$inheritReader = new InheritAnnotationReader($reader);
```

## Usage

```php
use Doctrine\Common\Annotations\AnnotationReader;
use Kenny1911\DoctrineInheritAnnotations\Annotation\Inherit;
use Kenny1911\DoctrineInheritAnnotations\InheritAnnotationReader;

/**
 * @FooAnnotation()
 */
class ParentClass {}

/**
 * @BarAnnotation()
 * 
 * @Inherit()
 */
class ChildClass extends ParentClass {}

$reader = new AnnotationReader();
$reader->getClassAnnotations(new ReflectionClass(ChildClass::class)); // return [@BarAnnotation(), @Inherit()]

$inheritReader = new InheritAnnotationReader($reader);
$inheritReader->getClassAnnotations(new ReflectionClass(ChildClass::class)); // return [@BarAnnotation(), @Inherit(), @FooAnnotation()]
```
