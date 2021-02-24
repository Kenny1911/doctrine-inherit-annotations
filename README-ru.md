# InheritAnnotationReader

`InheritAnnotationReader` - это реализация интерфейса `Reader` из пакета `doctrine/annotations`,
поддерживающая наследование аннотаций из родительского класса. Для этого среди аннотаций должна присутствовать аннотация
`@Kenny1911\DoctrineInheritAnnotations\Annotation\Inherit`, по аналогии с `@inheritDoc` у `PHPDoc`.

## Создание нового InheritAnnotationReader

`InheritAnnotationReader` декорирует оригинальный `Reader`:

```php
use Doctrine\Common\Annotations\AnnotationReader;
use Kenny1911\DoctrineInheritAnnotations\InheritAnnotationReader;

$reader = new AnnotationReader(); // Оригинальный Annotation Reader

$inheritReader = new InheritAnnotationReader($reader);
```

## Использование

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
