<?php
declare(strict_types=1);

namespace Ruchlewicz\Ceneo\Test\Util;

use PHPUnit\Framework\TestCase;
use Ruchlewicz\Ceneo\Util\CsvCreator;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;

class CsvCreatorTest extends TestCase
{
    public function testGenerateCsvFile()
    {
        $productRepository = $this->getMockBuilder(EntityRepositoryInterface::class)->getMock();
        $csvGenerator = new CsvCreator($productRepository);
        $csvGenerator->generateCsvFile();
        $this->assertFileExists('public/ceneo.csv');
    }
}
