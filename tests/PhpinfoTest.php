<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Shepherdmat\Phpinfo\Phpinfo;

class PhpinfoTest extends TestCase
{
    public function testBuildSectionThrowsExceptionForUnknownSection(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Phpinfo::buildSection(-1);
    }

    public function testBuildSectionReturnsInfoModelForValidSection(): void
    {
        $sectionType = INFO_GENERAL;
        $infoModel = Phpinfo::buildSection($sectionType);

        $this->assertSame('General', $infoModel->getName());
    }
}