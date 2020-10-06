<?php

namespace Oniric85\UsersService\Http\Request;

use Oniric85\UsersService\Http\Request\Exception\InvalidParameterException;
use Oniric85\UsersService\Http\Request\Exception\InvalidPayloadException;
use ReflectionClass;
use ReflectionProperty;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidationListener
{
    private const VALIDATION_GROUP_STRICT = 'Strict';
    private const VALIDATION_GROUP_DEFAULT = 'Default';

    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @throws InvalidParameterException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $requestClass = $request->attributes->get('_model');

        if (!$requestClass) {
            return;
        }

        $method = $request->getMethod();

        $params = [];

        if (in_array($method, [Request::METHOD_POST, Request::METHOD_PUT], true)) {
            $payload = json_decode($request->getContent(), true);

            if (null === $payload) {
                throw new InvalidPayloadException();
            }

            $params = $payload;
        } elseif (Request::METHOD_GET === $method) {
            $params = $request->query->all();
        }

        $model = $this->buildModel($requestClass, $params);
        $this->validateModel($model);

        $request->attributes->set('model', $model);
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $modelClassName
     *
     * @return T
     */
    private function buildModel(string $modelClassName, array $params = []): object
    {
        if (!class_exists($modelClassName)) {
            throw new RuntimeException(sprintf('Model class \'%s\' does not exist.', $modelClassName));
        }

        $reflectionClass = new ReflectionClass($modelClassName);

        // Create new model instance
        $model = $reflectionClass->newInstanceWithoutConstructor();

        // Get all properties of class
        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PRIVATE);

        // Fill the model with params
        foreach ($properties as $property) {
            $propertyName = $property->getName();

            if (array_key_exists($propertyName, $params)) {
                $reflectionProperty = $reflectionClass->getProperty($propertyName);
                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($model, $params[$propertyName]);
            }
        }

        return $model;
    }

    /**
     * @throws InvalidParameterException
     */
    private function validateModel(object $model): void
    {
        $this->validateByGroup($model, self::VALIDATION_GROUP_STRICT);
        $this->validateByGroup($model, self::VALIDATION_GROUP_DEFAULT);
    }

    protected function validateByGroup(object $model, string $validationGroup): void
    {
        $errors = $this->validator->validate($model, null, [$validationGroup]);

        if ($errors->count() > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $field = $error->getPropertyPath();
                $errorMessage = $error->getMessage();

                if (!isset($errorMessages[$field])) {
                    $errorMessages[$field] = [];
                }

                $errorMessages[$field][] = $errorMessage;
            }

            throw new InvalidParameterException($errorMessages);
        }
    }
}
