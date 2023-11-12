<?php

declare(strict_types=1);

namespace Shepherdmat\Phpinfo\Model;

use RuntimeException;

class SectionModel
{
    public const TYPE_TEXT = 'text';
    public const TYPE_TABLE = 'table';

    private static array $types = [self::TYPE_TEXT, self::TYPE_TABLE];

    private string $type;
    private int $maxColumns;
    private ?array $textLines;
    private ?string $tableTitle;
    private ?array $tableRows;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTextLines(): ?array
    {
        return $this->textLines;
    }

    public function setTextLines(array $textLines): void
    {
        $this->textLines = $textLines;
    }

    public function getMaxColumns(): int
    {
        if ($this->type !== self::TYPE_TABLE) {
            throw new RuntimeException(sprintf('Column count is only available in %s section type', self::TYPE_TABLE));
        }

        return $this->maxColumns;
    }

    public function setMaxColumns(int $maxColumns): void
    {
        $this->maxColumns = $maxColumns;
    }

    public function getTableTitle(): ?string
    {
        if ($this->type !== self::TYPE_TABLE) {
            throw new RuntimeException(sprintf('Column title is only available in %s section type.', self::TYPE_TABLE));
        }

        return $this->tableTitle;
    }

    public function setTableTitle(?string $tableTitle): void
    {
        if ($this->type !== self::TYPE_TABLE) {
            throw new RuntimeException(sprintf('Column title is only available in %s section type.', self::TYPE_TABLE));
        }

        $this->tableTitle = $tableTitle;
    }

    public function getTableRows(): array
    {
        if ($this->type !== self::TYPE_TABLE) {
            throw new RuntimeException(sprintf('Columns are only available in %s section type.', self::TYPE_TABLE));
        }

        return $this->tableRows;
    }

    public function setTableRows(array $tableRows): void
    {
        if ($this->type !== self::TYPE_TABLE) {
            throw new RuntimeException(sprintf('Columns are only available in %s section type.', self::TYPE_TABLE));
        }

        $this->tableRows = $tableRows;
    }
}