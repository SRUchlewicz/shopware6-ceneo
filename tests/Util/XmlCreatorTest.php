<?php
declare(strict_types=1);

namespace Ruchlewicz\Ceneo\Test\Util;

use PHPUnit\Framework\TestCase;
use Ruchlewicz\Ceneo\Util\XmlCreator;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Context;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\DataAbstractionLayer\Pricing\PriceCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Pricing\Price;

class XmlCreatorTest extends TestCase
{
    use IntegrationTestBehaviour;

    public function testGenerateXmlFile()
    {
        $productRepository = $this->getMockBuilder(EntityRepositoryInterface::class)->getMock();

        $productRepository->method('search')->willReturn(
            new EntitySearchResult(
                1,
                $this->getProductEntityCollection(),
                null,
                new Criteria(),
                Context::createDefaultContext()
            )
        );

        $kernel = $this->getContainer()->get('kernel');
        $testXmlFileName = 'ceneo_test.xml';
        $csvGenerator = new XmlCreator($productRepository, $kernel);
        $csvGenerator->generateXmlFile($testXmlFileName);
        $this->assertFileExists(TEST_PROJECT_DIR . '/' . XmlCreator::XML_DIR . '/' . $testXmlFileName);
        //$this->removeTestXmlFile($testXmlFileName);
    }

    private function getProductEntityCollection(): EntityCollection
    {
        $price = new Price('1',125, 125, false);
        $product = new ProductEntity();
        $product->setUniqueIdentifier(Uuid::randomHex());
        $product->setName('Test product');
        $product->setAutoIncrement(1);
        $product->setPrice(new PriceCollection([$price]));

        return new EntityCollection([$product]);
    }

    private function removeTestXmlFile($fileName)
    {
        unlink(TEST_PROJECT_DIR . '/' . XmlCreator::XML_DIR . '/' . $fileName);
    }
}
