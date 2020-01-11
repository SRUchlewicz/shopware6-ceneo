<?php declare(strict_types=1);

namespace Ruchlewicz\Ceneo\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;

class GenerateProductFeedHandler extends ScheduledTaskHandler
{
    public static function getHandledMessages(): iterable
    {
        return [ GenerateProductFeed::class ];
    }

    public function run(): void
    {
        echo 'Do stuff!';
    }
}
