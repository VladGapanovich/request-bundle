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

* `Body` (take data from request body or form)
* `Collection` (Needed for hydrate of collection some sub objects)
* `Cookie` (take data from cookies)
* `EmbeddableRequest` (Needed for hydrate of some sub object)
* `File` (take data from files)
* `Header` (take data from headers)
* `PathAttribute` (take data from path attributes)
* `Query` (take data from query string)

### Request

```php
use Jrm\RequestBundle\Model\Source;
use Jrm\RequestBundle\Attribute\Collection;
use Jrm\RequestBundle\Attribute\Header;
use Jrm\RequestBundle\Attribute\PathAttribute;

final class MyRequest
{
    public function __construct(
        #[PathAttribute()]
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
use Jrm\RequestBundle\Attribute\Body;

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

You can validate your request by symfony constraints, if validation will be failed,
Jrm\RequestBundle\Listener\RequestValidationFailedExceptionListener will send response with all failed fields and error messages for them

```php
use Jrm\RequestBundle\Attribute\Query;
use Symfony\Component\Validator\Constraints as Assert;

final class MyRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Query('some_field')]
        public readonly string $field,
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

In some cases you may need to hydrate collection of data, you can use Collection attribute and "describe" this collection
items as a separate object.

```php
use Jrm\RequestBundle\Attribute\Internal\Item;
use Symfony\Component\Validator\Constraints as Assert;

final class MyCollectionItem
{
    public function __construct(
        #[Assert\Uuid]
        #[Item()]
        public readonly string $id,
    ) {
    }
}
```

```php
use Jrm\RequestBundle\Model\Source;
use Jrm\RequestBundle\Attribute\Collection;
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

> **_NOTE:_** You should use #[Assert\Valid] for your collection as in the example above for validating your collection,
> because without this constraint, symfony validator ignore it

## Custom Resolver

You can create your custom resolver to define new way to get of data for request<br>
For this you need to create:

### Parameter
```php
use Jrm\RequestBundle\Attribute\RequestAttribute;

#[Attribute(Attribute::TARGET_PARAMETER, Attribute::TARGET_PROPERTY)]
final class UserId implements RequestAttribute
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
use Jrm\RequestBundle\Model\Metadata;
use Jrm\RequestBundle\Attribute\RequestAttribute;
use Jrm\RequestBundle\Attribute\ValueResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class UserIdResolver implements ValueResolver
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    public function resolve(
        Request $request,
        Metadata $metadata,
        RequestAttribute $attribute,
    ): int {
        if (!$attribute instanceof UserId) {
            throw new UnexpectedAttributeException(UserId::class, $attribute::class);
        }

        try {
            $user = $this->tokenStorage->getToken()?->getUser();

            if ($user === null) {
                throw new UserNotAuthorizedException();
            }
    
            return $user->id();
        } catch (Throwable $throwable) {
            if ($parameter->isOptional()) {
                return $parameter->defaultValue();
            }

            throw $throwable;
        }
    }
}
```

## Plans:

* Make automation conversion to Open Api Doc
* Make the `Item` attribute optional
* Add validation tests that all requests are valid classes with supported attributes and types
* Fix issue with validation, when your request haven't any required params
* Add bundle to symfony flex
* Add more unit and integration tests
