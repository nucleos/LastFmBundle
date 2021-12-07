<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFmBundle\Action;

use Nucleos\LastFm\Service\AuthServiceInterface;
use Nucleos\LastFmBundle\Session\SessionManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class CheckAuthAction
{
    private RouterInterface $router;

    private SessionManagerInterface $sessionManager;

    private AuthServiceInterface $authService;

    public function __construct(RouterInterface $router, SessionManagerInterface $sessionManager, AuthServiceInterface $authService)
    {
        $this->router         = $router;
        $this->sessionManager = $sessionManager;
        $this->authService    = $authService;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $token = $request->query->get('token', '');

        if ('' === $token) {
            return new RedirectResponse($this->router->generate('nucleos_lastfm_auth'));
        }

        // Store session
        $lastFmSession = $this->authService->createSession($token);

        if (null === $lastFmSession) {
            return new RedirectResponse($this->router->generate('nucleos_lastfm_error'));
        }

        $this->sessionManager->store($lastFmSession);

        return new RedirectResponse($this->router->generate('nucleos_lastfm_success'));
    }
}
