# Hostnet coding standards
Adds additional sniffs and fixes to squizlabs/php_codesniffer

## Rules Overview

### Abstract class must be prefixed with Abstract

- class: [`Hostnet\Sniffs\Classes\AbstractClassMustBePrefixedWithAbstractSniff`](/src/Hostnet/Sniffs/Classes/AbstractClassMustBePrefixedWithAbstractSniff.php)
- sniff: `Hostnet.Classes.AbstractClassMustBePrefixedWithAbstract`

:x:

```php
abstract class MyClass
{
    public abstract function getEntityManager();
}
```

:+1:

```php
abstract class AbstractMyClass
{
    public abstract function getEntityManager();
}
```

### Class and Namespace must be in PascalCase

- class: [`Hostnet\Sniffs\Classes\ClassAndNamespaceMustBeInPascalCaseSniff`](/src/Hostnet/Sniffs/Classes/ClassAndNamespaceMustBeInPascalCaseSniff.php)
- sniff: `Hostnet.Classes.ClassAndNamespaceMustBeInPascalCase`

:x:

```php
namespace This\IS\a\BAD\namespaceE;

class MyClassIs09LookingFaULty
{
}
```

:+1:

```php
namespace This\Is\A\Good\NameSpaceE;

class MyClassIsLookingGood
{
}
```

### Interface must be postfixed with Interface

- class: [`Hostnet\Sniffs\Classes\InterfaceMustBePostfixedWithInterfaceSniff`](/src/Hostnet/Sniffs/Classes/InterfaceMustBePostfixedWithInterfaceSniff.php)
- sniff: `Hostnet.Classes.InterfaceMustBePostfixedWithInterface`

:x:

```php
interface MyClass
{
}
```

:+1:

```php
interface MyClassInterface
{
}
```

### No vertical whitespace between use statements

- class: [`Hostnet\Sniffs\Classes\NoVerticalWhitespaceBetweenUseStatementsSniff`](/src/Hostnet/Sniffs/Classes/NoVerticalWhitespaceBetweenUseStatementsSniff.php)
- sniff: `Hostnet.Classes.NoVerticalWhitespaceBetweenUseStatements`

:x:

```php
use SomeSpace; // comment
use MoreSpace;

use  LostIn\Space;
```

:+1:

```php
use SomeSpace; // comment
use MoreSpace;
use  LostIn\Space;
```

### Only one use statement per line

- class: [`Hostnet\Sniffs\Classes\OnlyOneUseStatementPerLineSniff`](/src/Hostnet/Sniffs/Classes/OnlyOneUseStatementPerLineSniff.php)
- sniff: `Hostnet.Classes.OnlyOneUseStatementPerLine`

:x:

```php
use SomeOtherSpace as Hello, ThisShouldNotBeHere;
```

:+1:

```php
use SomeOtherSpace as Hello;
```

### Protected properties are not allowed

- class: [`Hostnet\Sniffs\Classes\ProtectedPropertiesAreNotAllowedSniff`](/src/Hostnet/Sniffs/Classes/ProtectedPropertiesAreNotAllowedSniff.php)
- sniff: `Hostnet.Classes.ProtectedPropertiesAreNotAllowed`

:x:

```php
    private $bar;

    protected $foo;

    public $public_variable;
```

:+1:

```php
    private $bar;

    public $public_variable;
```

### Trait must be postfixed with Trait

- class: [`Hostnet\Sniffs\Classes\TraitMustBePostfixedWithTraitSniff`](/src/Hostnet/Sniffs/Classes/TraitMustBePostfixedWithTraitSniff.php)
- sniff: `Hostnet.Classes.TraitMustBePostfixedWithTrait`

:x:

```php
trait MyClass
{
}
```

:+1:

```php
trait MyClassTrait
{
}
```

### Use statements alphabetically ordered

- class: [`Hostnet\Sniffs\Classes\UseStatementsAlphabeticallyOrderedSniff`](/src/Hostnet/Sniffs/Classes/UseStatementsAlphabeticallyOrderedSniff.php)
- sniff: `Hostnet.Classes.UseStatementsAlphabeticallyOrdered`

:x:

```php
use A;
use C;
use B;
```

:+1:

```php
use A;
use B;
use C;
```

### Variable and property must be in snake case

- class: [`Hostnet\Sniffs\Classes\VariableAndPropertyMustBeInSnakeCaseSniff`](/src/Hostnet/Sniffs/Classes/VariableAndPropertyMustBeInSnakeCaseSniff.php)
- sniff: `Hostnet.Classes.VariableAndPropertyMustBeInSnakeCase`

:x:

```php
private $badProperty;
```

:+1:

```php
private $good_property;
```

### @covers fully qualified name

- class: [`Hostnet\Sniffs\Commenting\AtCoversFullyQualifiedNameSniff`](/src/Hostnet/Sniffs/Commenting/AtCoversFullyQualifiedNameSniff.php)
- sniff: `Hostnet.Commenting.AtCoversFullyQualifiedName`

:x:

```php
/**
 * @covers Hostnet\Test\TestUnit\Bad
 */
```

:+1:

```php
/**
 * @covers \Hostnet\Test\TestUnit\Good
 */
```

### Author is formatted correctly
- class: [`Hostnet\Sniffs\Commenting\AuthorIsFormattedCorrectlySniff`](/src/Hostnet/Sniffs/Commenting/AuthorIsFormattedCorrectlySniff.php)
- sniff: `Hostnet.Commenting.AuthorIsFormattedCorrectly`

:x:

```php
/**
 * @author Henk
 */
```

:+1:

```php
/**
 * @author Henk de Vries <h.de.vries@hostnet.nl>
 */
```

### File comment copyright
- class: [`Hostnet\Sniffs\Commenting\FileCommentCopyrightSniff`](/src/Hostnet/Sniffs/Commenting/FileCommentCopyrightSniff.php)
- sniff: `Hostnet.Commenting.FileCommentCopyright`
- parameters:
    - years: Which years should be noted for the copyright, if not configured the _years is 'calculated'
    - copyright_holder: The legal holder of the copyright.
    - copyright_tag: The 'name' of the tag to search for. phpDocumenter uses @copyright.

:x:

```php
<?php
/**
 * Test1234
 */
class abc {

}
```

:+1:

```php
/**
 * @copyright 2017 Hostnet B.V.
 */

/**
 * Test1234
 */
class abc {

}
```

### Declares strict
- class: [`Hostnet\Sniffs\Declares\StrictSniff`](/src/Hostnet/Sniffs/Declares/StrictSniff.php)
- sniff: `Hostnet.Declares.Strict`

:x:

```php
<?php
    echo 'hi there';
?>
```

:+1:

```php
<?php
declare(strict_types=1);
```

### Return type declaration
- class: [`Hostnet\Sniffs\Functions\ReturnTypeDeclarationSniff`](/src/Hostnet/Sniffs/Functions/ReturnTypeDeclarationSniff.php)
- sniff: `Hostnet.Functions.ReturnTypeDeclaration`
- parameters:
    - closing_parenthesis_colon_spacing: spacing between the function's closing parenthesis and colon.
    - colon_return_type_spacing: spacing between the colon and the return type.

:x:

```php
    public function this() : string
    {
        return;
    }
```

:+1:

```php
    public function this(): string
    {
        return;
    }
```

### Method name starts with 'getis'
- class: [`Hostnet\Sniffs\NamingConventions\MethodNameStartsWithGetIsSniff`](/src/Hostnet/Sniffs/NamingConventions/MethodNameStartsWithGetIsSniff.php)
- sniff: `Hostnet.NamingConventions.MethodNameStartsWithGetIs`

:x:

```php
  protected function getIsStuff()
  {
  }
```

:+1:

```php
  protected function isStuff()
  {
  }
```

### Namespace
- class: [`Hostnet\Sniffs\PhpUnit\NamespaceSniff`](/src/Hostnet/Sniffs/PhpUnit/NamespaceSniff.php)
- sniff: `Hostnet.PhpUnit.Namespace`

:x:

```php
class NamespaceTest extends PHPUnit_Framework_TestCase
```

:+1:

```php
class NamespaceTest extends TestCase
```
