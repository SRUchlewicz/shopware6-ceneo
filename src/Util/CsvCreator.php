<?php
declare(strict_types=1);

namespace Ruchlewicz\Ceneo\Util;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;

class CsvCreator
{
    /**
     * @var EntityRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        EntityRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function generateCsvFile(): void
    {

    }
}
