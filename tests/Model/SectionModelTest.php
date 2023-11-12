<?php

use PHPUnit\Framework\TestCase;
use Shepherdmat\Phpinfo\Model\SectionModel;

class SectionModelTest extends TestCase
{
    public function testCanBeInstantiatedWithValidType(): SectionModel
    {
        $section = new SectionModel(SectionModel::TYPE_TEXT);
        $this->assertInstanceOf(SectionModel::class, $section);

        return $section;
    }

    /**
     * @depends testCanBeInstantiatedWithValidType
     */
    public function testCanSetAndGetTextLines(SectionModel $section): void
    {
        $textLines = ['line1', 'line2'];
        $section->setTextLines($textLines);
        $this->assertSame($textLines, $section->getTextLines());
    }

    public function testCanSetAndGetMaxColumnsForTableType(): SectionModel
    {
        $section = new SectionModel(SectionModel::TYPE_TABLE);
        $maxColumns = 3;
        $section->setMaxColumns($maxColumns);
        $this->assertSame($maxColumns, $section->getMaxColumns());

        return $section;
    }

    /**
     * @depends testCanSetAndGetMaxColumnsForTableType
     */
    public function testCanSetAndGetTableTitle(SectionModel $section): void
    {
        $title = 'Table Title';
        $section->setTableTitle($title);
        $this->assertSame($title, $section->getTableTitle());
    }

    /**
     * @depends testCanSetAndGetMaxColumnsForTableType
     */
    public function testCanSetAndGetTableRows(SectionModel $section)
    {
        $rows = [['row1col1', 'row1col2'], ['row2col1', 'row2col2']];
        $section->setTableRows($rows);
        $this->assertSame($rows, $section->getTableRows());
    }

    public function testGetMaxColumnsThrowsExceptionForTextType(): void
    {
        $this->expectException(RuntimeException::class);

        $section = new SectionModel(SectionModel::TYPE_TEXT);
        $section->getMaxColumns();
    }

    public function testGetTableTitleThrowsExceptionForTextType(): void
    {
        $this->expectException(RuntimeException::class);

        $section = new SectionModel(SectionModel::TYPE_TEXT);
        $section->getTableTitle();
    }

    public function testGetTableRowsThrowsExceptionForTextType(): void
    {
        $this->expectException(RuntimeException::class);

        $section = new SectionModel(SectionModel::TYPE_TEXT);
        $section->getTableRows();
    }
}
