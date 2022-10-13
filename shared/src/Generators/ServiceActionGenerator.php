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

        $namespace = new PhpNamespace($interface->getNamespace().'\\Actions');
        $file->addNamespace($namespace);

        $class = $namespace
            ->addClass(ucfirst($method->getName()).'Action')
            ->setFinal();

        $constructor = $class->addMethod('__construct');
        $constructor->addPromotedParameter('client')
            ->setPrivate()
            ->setType($interface->getClassNameWithNamespace());

        $handlerMethod = $class->addMethod('handle');
        $handlerMethod->setReturnType('void');
        $handlerMethod->setBody(\sprintf(<<<'BODY'
$context = new RequestContext();
$request = new %s();

$response = $this->client->%s($context, $request);
BODY,
        $requestClass = $method->getParameters()['in']->getType(),
        $method->getName()
));

        $namespace->addUse(RequestContext::class);
        $namespace->addUse($interface->getClassNameWithNamespace());
        $namespace->addUse($client->getClassNameWithNamespace());
        $namespace->addUse($requestClass);

        $this->files->ensureDirectory($path = $this->basePath.'/Actions/');
        $this->files->write(
            $path.$class->getName().'.php',
            (new Printer)->printFile($file)
        );
    }
}
