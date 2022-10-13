<?php

declare(strict_types=1);

namespace Spiral\Shared\Commands;

use Codedungeon\PHPCliColors\Color;
use Spiral\Files\FilesInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Spiral\Shared\Generators\BootloaderGenerator;
use Spiral\Shared\Generators\ProtoCompiler;

/**
 * @internal
 *
 * proto files compilation command.
 */
final class CompileCommand extends Command
{
    public function __construct(
        private readonly FilesInterface $files,
        private readonly array $services,
        private readonly string $binaryPath,
        private string $root
    ) {
        parent::__construct('compile-proto-files');
    }

    public function getDescription(): string
    {
        return 'Generate GPRC service code using protobuf specification';
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $binaryPath = $this->binaryPath;

        if ($binaryPath !== null && ! $this->files->exists($binaryPath)) {
            $io->error(\sprintf('PHP Server plugin binary `%s` not found. ', $binaryPath));

            return self::FAILURE;
        }

        $compiler = new ProtoCompiler(
            $this->getRootPath(),
            $this->getNamespace(),
            $this->files,
            $binaryPath
        );

        $services = [];

        foreach ($this->services as $protoFile) {
            if (! $this->files->exists($protoFile)) {
                $io->error(\sprintf('Proto file `%s` not found.', $protoFile));
                continue;
            }

            $io->writeln(\sprintf('Compiling <fg=cyan>`%s`</fg=cyan>:', $protoFile));

            try {
                $result = $compiler->compile($protoFile);
            } catch (\Throwable $e) {
                $io->writeln(\sprintf('<error>Error:</error> <fg=red>%s</fg=red>', $e->getMessage()));
                continue;
            }

            if ($result->getFiles() === []) {
                $io->info(\sprintf('No files were generated for `%s`.', $protoFile));
                continue;
            }

            foreach ($result->getFiles() as $file) {
                $io->writeln(\sprintf(
                    "<fg=green>•</fg=green> %s%s%s",
                    Color::LIGHT_WHITE,
                    $this->files->relativePath($file, $this->root),
                    Color::RESET
                ));
            }

            $io->writeln('');

            foreach ($result->getServices() as $service) {
                $services[] = $service;
            }
        }

        if ($services !== []) {
            (new BootloaderGenerator($this->files, $this->getBootloaderPath()))->generate($services);
        }

        return self::SUCCESS;
    }

    /**
     * Get base source code path.
     */
    private function getRootPath(): string
    {
        return __DIR__. '/../';
    }

    /**
     * Get base namespace of package.
     */
    private function getNamespace(): string
    {
        return 'Spiral\\Shared';
    }

    /**
     * Get bootloader path
     */
    private function getBootloaderPath(): string
    {
        return $this->getRootPath(). 'Bootloader/SharedBootloader.php';
    }
}
