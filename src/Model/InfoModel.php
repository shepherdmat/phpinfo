<?php

declare(strict_types=1);

namespace Shepherdmat\Phpinfo\Model;

class InfoModel
{
    private string $name;
    private int $maxColumns;
    private array $sections = [];

    public function __construct(string $name, int $maxColumns)
    {
        $this->name = $name;
        $this->maxColumns = $maxColumns;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMaxColumns(): int
    {
        return $this->maxColumns;
    }

    public function getSections(): array
    {
        return $this->sections;
    }

    public function addSection(SectionModel $sectionModel): void
    {
        $this->sections[] = $sectionModel;
    }
}