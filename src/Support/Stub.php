<?php

namespace AlphaDevTeam\AlphaCruds\Support;

class Stub
{
    protected string $path;

    protected static ?string $basePath = null;

    protected array $replaces = [];

    public function __construct($path, array $replaces = [])
    {
        $this->path = $path;
        $this->replaces = $replaces;
    }

    public static function create($path, array $replaces = []): static
    {
        return new static($path, $replaces);
    }

    public function setPath($path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getPath(): string
    {
        $path = static::getBasePath() . $this->path;

        return file_exists($path) ? $path : __DIR__ . '/../Commands/stubs' . $this->path;
    }

    public static function setBasePath($path): void
    {
        static::$basePath = $path;
    }

    public static function getBasePath(): ?string
    {
        return static::$basePath;
    }

    public function getContents(): array|bool|string
    {
        $contents = file_get_contents($this->getPath());

        foreach ($this->replaces as $search => $replace) {
            $contents = str_replace('$' . strtoupper($search) . '$', $replace, $contents);
        }

        return $contents;
    }

    public function render(): bool|array|string
    {
        return $this->getContents();
    }

    public function saveTo($path, $filename): bool
    {
        return file_put_contents($path . '/' . $filename, $this->getContents());
    }

    public function replace(array $replaces = []): static
    {
        $this->replaces = $replaces;

        return $this;
    }

    public function getReplaces(): array
    {
        return $this->replaces;
    }
}
