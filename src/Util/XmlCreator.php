<?php
declare(strict_types=1);

namespace Ruchlewicz\Ceneo\Util;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Kernel;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Context;
use XMLWriter;
use Generator;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Content\Product\ProductEntity;

class XmlCreator
{
    public const XML_DEFAULT_FILENAME = 'ceneo.xml';
    public const XML_DIR = 'public';

    /**
     * @var EntityRepositoryInterface
     */
    private $productRepository;

    /**
     * @var Kernel
     */
    private $kernel;

    public function __construct(
        EntityRepositoryInterface $productRepository,
        Kernel $kernel
    ) {
        $this->productRepository = $productRepository;
        $this->kernel = $kernel;
    }

    public function generateXmlFile(string $fileName = null): void
    {
        $products = $this->getActiveProducts();

        $this->clearXmlFile($fileName);

        $xmlWriter = new XMLWriter();

        $xmlWriter->openMemory();
        $xmlWriter->flush();
        $xmlWriter->startDocument('1.0', 'UTF-8');
        $xmlWriter->startElement('offers');
        $xmlWriter->writeAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
        $xmlWriter->writeAttribute('version','1');
        $i = 0;
        /** @var ProductEntity $product */
        foreach ($products as $product) {
            $xmlWriter->startElement('o');
            $xmlWriter->writeAttribute('id', (string)$product->getAutoIncrement());
            $xmlWriter->writeAttribute('url', 'test'); //TODO implement getting product url
            $xmlWriter->writeAttribute('price', (string) $product->getPrice()->first()->getGross()); // TODO check the possible null
            $xmlWriter->writeAttribute('avail', '1'); // TODO implement
            $xmlWriter->writeAttribute('weight','1'); // TODO implement
            $xmlWriter->writeAttribute('stock', '1'); // TODO implement
            $xmlWriter->writeAttribute('basket', '1'); // TODO implement
            $xmlWriter->writeElement('name', $this->overflowContentWithCdata($product->getName()));
            $xmlWriter->writeElement('desc', $this->overflowContentWithCdata($product->getDescription()));
            $xmlWriter->endElement();
            $i++;
            // Flush XML in memory to file every 1000 iterations
            if (0 === $i%1000) {
                file_put_contents($this->getXmlFilePath($fileName), $xmlWriter->flush(true), FILE_APPEND);
            }
        }
        $xmlWriter->endElement();

        file_put_contents($this->getXmlFilePath($fileName), $xmlWriter->flush(true), FILE_APPEND);
    }

    private function clearXmlFile(string $fileName = null): void
    {
        fopen($this->getXmlFilePath($fileName), 'w');
    }

    private function getXmlFilePath(string $fileName = null): string
    {
        return $this->kernel->getProjectDir() . '/' . self::XML_DIR . '/' . ($fileName ?: self::XML_DEFAULT_FILENAME);
    }

    private function getActiveProducts(): Generator
    {
        $products = $this->productRepository->search(
            $this->getSearchCriteriaForActiveProducts(),
            Context::createDefaultContext()
        )->getEntities();

        foreach ($products as $product) {
            yield $product;
        }
    }

    private function getSearchCriteriaForActiveProducts(): Criteria
    {
        $searchCriteria = new Criteria();
        $searchCriteria->addFilter(new EqualsFilter('product.active', true));

        return $searchCriteria;
    }

    private function overflowContentWithCdata(string $content = null): string
    {
        return '<![CDATA[' . ($content ?: '') . ']]>';
    }
}
