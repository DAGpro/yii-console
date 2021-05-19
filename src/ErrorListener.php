<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Event\ConsoleErrorEvent;

final class ErrorListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function onError(ConsoleErrorEvent $event): void
    {
        $exception = $event->getError();
        $command = $event->getCommand();

        if ($command !== null && $command->getName() !== null) {
            $commandName = $command->getName();
        } else {
            $commandName = 'unknown';
        }

        $message = sprintf(
            '%s: %s in %s:%s while running console command `%s`',
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $commandName
        );
        $this->logger->error($message, ['exception' => $exception]);
    }
}
