<?php declare(strict_types=1);

namespace Ruchlewicz\Ceneo\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

class GenerateProductFeed extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'ruchlewicz_ceneo.generate_product_feed';
    }

    public static function getDefaultInterval(): int
    {
        return 300; // 5 minutes
    }
}
