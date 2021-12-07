<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFmBundle\Tests\EventDispatcher;

use Nucleos\LastFmBundle\Event\AuthFailedEvent;
use Nucleos\LastFmBundle\Event\AuthSuccessEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class TestEventDispatcher implements EventDispatcherInterface
{
    private ?Response $response = null;

    public function dispatch($event, $eventName = null): object
    {
        if ($event instanceof AuthFailedEvent || $event instanceof AuthSuccessEvent) {
            $event->setResponse($this->response);
        }

        return $event;
    }

    public function setResponse(?Response $response): void
    {
        $this->response = $response;
    }
}
