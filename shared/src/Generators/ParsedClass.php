<?php

declare(strict_types=1);

namespace Spiral\Shared\Generators;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\InterfaceType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;

/**
 * @internal
 */
final class ParsedClass
{
    private readonly PhpFile $file;
    private readonly PhpNamespace $namespace;
    private readonly InterfaceType|ClassType $class;

    public function __construct(string $content)
    {
        $this->file = PhpFile::fromCode($content);

        $this->namespace = $this->file->getNamespaces()[\array_key_first($this->file->getNamespaces())];
        $this->class = $this->namespace->getClasses()[\array_key_first($this->namespace->getClasses())];
    }


    public function addUse(string $class): void
    {
        $this->namespace->addUse($class);
    }

    public function getMethod(string $name): Method
    {
        return $this->class->getMethod($name);
    }

    public function getMethods(): array
    {
        return $this->class->getMethods();
    }

    public function getNamespace(): ?string
    {
        return $this->namespace->getName();
    }

    public function getClassName(): ?string
    {
        return $this->class->getName();
    }

    public function getClassNameWithNamespace(): string
    {
        return $this->getNamespace() . '\\' . $this->getClassName();
    }

    public function getContent(): string
    {
        return (new Printer)->printFile($this->file);
    }
}
