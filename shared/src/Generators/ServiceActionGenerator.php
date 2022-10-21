<?php

declare(strict_types=1);

namespace Spiral\Shared\Generators;

use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;
use Spiral\Files\FilesInterface;
use Spiral\Shared\GRPC\RequestContext;

/**
 * @internal
 */
final class ServiceActionGenerator
{
    public function __construct(
        private readonly FilesInterface $files,
        private readonly string $basePath
    ) {
    }

    public function generate(ParsedClass $interface, ParsedClass $client): void
    {
        foreach ($interface->getMethods() as $method) {
            $this->generateAction($interface, $client, $method);
        }
    }

    private function generateAction(ParsedClass $interface, ParsedClass $client, Method $method)
    {
        $file = new PhpFile;
        $file->setStrictTypes();

        $namespace = new PhpNamespace($interface->getNamespace() . '\\Actions');
        $file->addNamespace($namespace);

        $class = $namespace
            ->addClass(ucfirst($method->getName()) . 'Action')
            ->setFinal();

        $constructor = $class->addMethod('__construct');
        $constructor->addPromotedParameter('client')
            ->setPrivate()
            ->setReadOnly()
            ->setType($interface->getClassNameWithNamespace());

        $requestClass = new \ReflectionClass($method->getParameters()['in']->getType());

        $methods = \array_filter(
            $requestClass->getMethods(),
            fn(\ReflectionMethod $method) => \str_starts_with($method->getName(), 'set')
        );

        $methodsString = [];
        if (\count($methods) > 0) {
            $methodsString[] = '// Fill in request';
            foreach ($methods as $requestClassMethod) {
                $methodsString[] = \sprintf('// $request->%s(...);', $requestClassMethod->getName());
            }
        }

        $responseMethods = \array_filter(
            (new \ReflectionClass($method->getReturnType()))->getMethods(),
            fn(\ReflectionMethod $method) => \str_starts_with($method->getName(), 'get')
        );

        $responseMethodsString = [];
        if (\count($methods) > 0) {
            $responseMethodsString[] = '// Handle response';
            foreach ($responseMethods as $responseClassMethod) {
                $responseMethodsString[] = \sprintf(
                    '// $%s = $response->%s();',
                    \lcfirst(\substr($responseClassMethod->getName(), 3)),
                    $responseClassMethod->getName()
                );
            }
        }

        $handlerMethod = $class->addMethod('handle');
        $handlerMethod->setReturnType('void');
        $handlerMethod->setBody(
            \sprintf(
                <<<'BODY'
$context = new RequestContext();
$request = new %s();
%s

$response = $this->client->%s($context, $request);
%s
BODY,
                $requestClass->getShortName(),
                \implode(PHP_EOL, $methodsString),
                $method->getName(),
                \implode(PHP_EOL, $responseMethodsString),
            )
        );

        $namespace->addUse(RequestContext::class);
        $namespace->addUse($requestClass->getName());
        $namespace->addUse($interface->getClassNameWithNamespace());


        $this->files->ensureDirectory($path = $this->basePath . '/Actions/');
        $this->files->write(
            $path . $class->getName() . '.php',
            (new Printer)->printFile($file)
        );
    }
}
