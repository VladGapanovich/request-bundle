<a href="https://justreserve.me" target="_blank">JustReserve</a> RequestBundle
=============================

This is an implementation of hydrating data from symfony requests in narrow request for specific controller/action.

# Installation

1. Require this bundle in your application:

```shell
composer require jrm/request-bundle
```

2. Enable the bundle in your application:

```php
return [
    # ...
    Jrm\RequestBundle\JrmRequestBundle::class => ['all' => true],
    # ...
];
```

# Usage

Create request using some sources of data:

* `Body` (take JSON data from request body)
* `Collection` (Needed for hydrate of collection some sub objects)
* `Cookie` (take data from cookies)
* `EmbeddableRequest` (Needed for hydrate of some sub object)
* `File` (take data from files)
* `Form` (take data from form data)
* `Header` (take data from headers)
* `PathAttribute` (take data from path attributes)
* `Query` (take data from query string)

### Request

```php
use Jrm\RequestBundle\Model\Source;
use Jrm\RequestBundle\Parameter\Collection;
use Jrm\RequestBundle\Parameter\Header;
use Jrm\RequestBundle\Parameter\PathAttribute;

final class MyRequest
{
    public function __construct(
        #[PathAttribute('id')]
        public readonly int $id,
        #[Body('pos_id')]
        private readonly string $posId,
        #[Header('Content-Type')]
        public readonly string $contentType,
        #[Assert\Valid]
        #[Collection(
            type: ProductItem::class,
            source: Source::BODY,
            path: 'products',
        )]
        public readonly array $products,
    ) {
    }
}
```

### Controller
This example with invokable controller, but you can use it with regular controller.
```php
use Jrm\RequestBundle\MapRequest;

#[Route(
    '/do-something/{id}',
    name: 'app.do.something',
    methods: [Request::METHOD_POST],
)]
final class MyAction
{
    public function __invoke(#[MapRequest] MyRequest $request): JsonResponse
    {
        //do something

        return new JsonResponse(null);
    }
}
```

## Nested fields

Your data, for example request body, may have some nesting.


```json
{
  "request": {
    "some_field": "some_value",
    "next_field": "next_value"
  }
}
```

You can pass path to this filed.

```php
use Jrm\RequestBundle\Parameter\Body;

final class MyRequest
{
    public function __construct(
        #[Body('request.some_field')]
        public readonly string $someField,
        #[Body('request.next_field')]
        public readonly string $nextField,
    ) {
    }
}
```

## Validation

You can validate your request by symfony constraints, if validation will be failed, Jrm\RequestBundle\Listener\RequestValidationFailedExceptionListener will send response with all failed fields and error messages for them

```php
use Jrm\RequestBundle\Parameter\Query;
use Symfony\Component\Validator\Constraints as Assert;

final class MyRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Query('some_field')]
        public readonly string $clientIp,
    ) {
    }
}
```

```json
{
  "message": "Validation failed.",
  "errors": [
    {
      "code": "48b70abd-a021-4ce7-9662-616cd58eeaee",
      "message": "This value should not be blank.",
      "parameters": [],
      "property_path": "some_field"
    }
  ]
}
```

## Collection

In some cases you may need to hydrate collection of data, you can use Collection attribute and "describe" this collection items as a separate object or scalar.

```php
use Jrm\RequestBundle\Parameter\Item;
use Symfony\Component\Validator\Constraints as Assert;

final class MyCollectionItem
{
    public function __construct(
        #[Assert\Uuid]
        #[Item('id')]
        public readonly string $id,
    ) {
    }
}
```

```php
use Jrm\RequestBundle\Model\Source;
use Jrm\RequestBundle\Parameter\Collection;
use Symfony\Component\Validator\Constraints as Assert;

final class MyRequest
{
    public function __construct(
        #[Assert\Valid]
        #[Collection(
            type: MyCollectionItem::class,
            source: Source::BODY,
            path: 'sub_ids',
        )]
        public readonly array $items,
    ) {
    }
}
```

> **_NOTE:_** You should use #[Assert\Valid] for your collection as in the example above for validating your collection, because without this constraint, symfony validator ignore it

## Custom Resolver

You can create your custom resolver to define new way to get of data for request<br>
For this you need to create:

### Parameter
```php
#[Attribute(Attribute::TARGET_PARAMETER, Attribute::TARGET_PROPERTY)]
final class UserId implements ParameterInterface
{
    /**
     * @return class-string<UserIdResolver>
     */
    public function resolvedBy(): string
    {
        return UserIdResolver::class;
    }
}
```

### ParameterResolver

```php
use App\Domain\User\Exception\UserNotAuthorizedException;
use App\Domain\User\Model\User;
use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Parameter\RequestAttribute;
use Jrm\RequestBundle\Parameter\ParameterResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class UserIdResolver implements ParameterResolver
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    public function resolve(
        Request $request,
        ReflectionParameter $parameter,
        RequestAttribute $attribute,
    ): int {
        if (!$attribute instanceof UserId) {
            throw new UnexpectedAttributeException(UserId::class, $attribute::class);
        }

        try {
            $user = $this->tokenStorage->getToken()?->getUser();

            if (null === $user) {
                throw UserNotAuthorizedException::create();
            }
    
            return $user->id();
        } catch (Throwable $throwable) {
            if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            throw $throwable;
        }
    }
}
```

## Custom Caster

You can create your custom caster for casting value to anything<br>
For this you need to create:

### Caster

```php
use App\Domain\User\Model\UserId;
use Jrm\RequestBundle\Caster\Caster;
use Jrm\RequestBundle\Exception\InvalidTypeException;
use Jrm\RequestBundle\Parameter\RequestAttribute;

final class UserIdCaster implements Caster
{
    /**
     * @return class-string<UserId>
     */
    public static function getType(): string
    {
        return UserId::class;
    }

    public function cast(mixed $value, RequestAttribute $attribute, bool $allowsNull): ?UserId
    {
        if (null === $value && $allowsNull) {
            return null;
        }

        $type = get_debug_type($value);
        $value = filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

        if (is_int($value)) {
            return new UserId($value);
        }

        throw new InvalidTypeException(UserId::class, $type);
    }
}
```

## Plans:

* Make the validator optional
* Add context and insert it instead of `RequestAttribute` and `type`
* Make Attribute's `name` or `path` nullable and use parameter name if it is null.
* Make hydration by class properties
* Make data validation before passing it as `construct` parameters
* Make automation conversion to Open Api Doc
* Make the `Item` attribute optional
* Add validation tests that all requests are valid classes with supported attributes and types
* Add support of intersection and union types
