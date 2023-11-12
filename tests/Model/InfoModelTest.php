<?php

use PHPUnit\Framework\TestCase;
use Shepherdmat\Phpinfo\Model\InfoModel;
use Shepherdmat\Phpinfo\Model\SectionModel;

class InfoModelTest extends TestCase
{
    public function testCanCreateInfoModelInstance(): InfoModel
    {
        $name = 'TestName';
        $maxColumns = 5;

        $infoModel = new InfoModel($name, $maxColumns);

        $this->assertInstanceOf(InfoModel::class, $infoModel);
        return $infoModel;
    }

    /**
     * @depends testCanCreateInfoModelInstance
     */
    public function testCanGetName(InfoModel $infoModel): void
    {
        $this->assertSame('TestName', $infoModel->getName());
    }

    /**
     * @depends testCanCreateInfoModelInstance
     */
    public function testCanGetMaxColumns(InfoModel $infoModel): void
    {
        $this->assertSame(5, $infoModel->getMaxColumns());
    }

    /**
     * @depends testCanCreateInfoModelInstance
     */
    public function testCanAddAndGetSections(InfoModel $infoModel): void
    {
        $sectionModel = $this->createMock(SectionModel::class);

        $infoModel->addSection($sectionModel);
        $sections = $infoModel->getSections();

        $this->assertCount(1, $sections);
        $this->assertSame($sectionModel, $sections[0]);
    }
}
