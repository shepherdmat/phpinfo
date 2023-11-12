<?php

declare(strict_types=1);

namespace Shepherdmat\Phpinfo;

use InvalidArgumentException;
use Shepherdmat\Phpinfo\Model\InfoModel;
use Shepherdmat\Phpinfo\Model\SectionModel;

class Phpinfo
{
    private const COLUMN_SEPARATOR = ' => ';

    private static array $sections = [
        INFO_GENERAL => 'General',
        INFO_CREDITS => 'Credits',
        INFO_CONFIGURATION => 'Configuration',
        INFO_MODULES => 'Modules',
        INFO_ENVIRONMENT => 'Environment', //problem
        INFO_VARIABLES => 'Variables',
        INFO_LICENSE => 'License',
    ];

    private static array $skipContent = [
        'phpinfo()',
        'PHP Credits',
        'Contribution => Authors',
        'Module => Authors',
        '_______________________________________________________________________',
    ];

    public static function build(): array
    {
        $info = [];

        foreach (self::$sections as $id => $name) {
            $info[] = self::buildSection($id);
        }

        return $info;
    }

    public static function buildSection(int $sectionType): InfoModel
    {
        $sectionsTypes = self::$sections;

        if (!array_key_exists($sectionType, $sectionsTypes)) {
            $availableSectionTypes = implode(', ', self::getSectionsDescription());

            throw new InvalidArgumentException(
                sprintf('Unknown section type "%s". Available sections are: %s.', $sectionType, $availableSectionTypes)
            );
        }

        $sectionRawContent = self::getContent($sectionType);
        $maxRows = self::getMaxColumnsFromRawContent($sectionRawContent);
        $title = $sectionsTypes[$sectionType];

        $infoModel = new InfoModel($sectionsTypes[$sectionType], $maxRows);

        foreach (self::getRawSectionBlocks($sectionRawContent) as $content) {
            $section = self::buildSectionFromRawContentArray($content, $title);

            if (SectionModel::TYPE_TEXT === $section->getType() && 0 === count($section->getTextLines())) {
                continue;
            }

            $infoModel->addSection(self::buildSectionFromRawContentArray($content, $title));
        }

        return $infoModel;
    }

    private static function getContent(int $infoType = INFO_GENERAL): string
    {
        ob_start();
        phpinfo($infoType);

        return ob_get_clean();
    }

    private static function getMaxColumnsFromRawContent(string $rowContent): int
    {
        return self::getMaxColumnsFromArray(preg_split("/((\r?\n)|(\r\n?))/", $rowContent));
    }

    private static function getMaxColumnsFromArray(array $contentArray): int
    {
        $maxRows = 0;

        foreach ($contentArray as $line) {
            $currentRowsNumber = count(explode(self::COLUMN_SEPARATOR, $line));

            if ($currentRowsNumber > $maxRows) {
                $maxRows = $currentRowsNumber;
            }
        }

        return $maxRows;
    }

    private static function getSectionsDescription(): array
    {
        $description = [];

        foreach (self::$sections as $id => $name) {
            $description[] = sprintf('%s (%s)', $id, $name);
        }

        return $description;
    }

    private static function getRawSectionBlocks(string $rawContent): array
    {
        $innerSectionCurrentRow = 0;
        $innerSections = [];

        foreach (preg_split("/((\r?\n)|(\r\n?))/", $rawContent) as $line) {
            $line = trim($line);

            if (!$line) {
                $innerSectionCurrentRow++;

                continue;
            }

            if (in_array($line, self::$skipContent, true)) {
                continue;
            }

            $innerSections[$innerSectionCurrentRow][] = $line;
        }

        return $innerSections;
    }

    private static function buildSectionFromRawContentArray(array $rawContentArray, ?string $title = null): SectionModel
    {
        $type = self::isContentTableType($rawContentArray)
            ? SectionModel::TYPE_TABLE
            : SectionModel::TYPE_TEXT;

        $section = new SectionModel($type);

        if (SectionModel::TYPE_TABLE === $type) {
            $section->setMaxColumns(self::getMaxColumnsFromArray($rawContentArray));
            $section->setTableTitle(self::getContentTableHeader($rawContentArray));
            $section->setTableRows(self::getContentTableRows($rawContentArray, $title));
        } else {
            foreach ($rawContentArray as $key => $line) {
                if ($title === $line || in_array($line, self::$skipContent, true)) {
                    unset($rawContentArray[$key]);
                }
            }

            $section->setTextLines($rawContentArray);
        }

        return $section;
    }

    private static function isContentTableType(array $rawContentArray): bool
    {
        foreach ($rawContentArray as $contentLine) {
            if (str_contains($contentLine, self::COLUMN_SEPARATOR)) {
                return true;
            }
        }

        return false;
    }

    private static function getContentTableHeader(array $rawContentArray): ?string
    {
        if (count(explode(self::COLUMN_SEPARATOR, $rawContentArray[0])) === 1) {
            return $rawContentArray[0];
        }

        return null;
    }

    private static function getContentTableRows(array $rawContentArray, ?string $title = null): array
    {
        $rows = [];

        foreach ($rawContentArray as $contentLine) {
            $data = explode(self::COLUMN_SEPARATOR, $contentLine);

            if (count($data) === 1) {
                if (($data[0] === null || $data[0] !== $title)) {
                    continue;
                }

                if (!count($rows)) {
                    $rows[] = $data[0];
                    continue;
                }

                $rowId = (int)array_key_last($rows);
                $colId = (int)array_key_last($rows[$rowId]);

                $colData = $rows[$rowId][$colId];
                $rows[$rowId][$colId] = $colData . ' ' . $data[0];

                continue;
            }

            $rows[] = $data;
        }

        return $rows;
    }
}