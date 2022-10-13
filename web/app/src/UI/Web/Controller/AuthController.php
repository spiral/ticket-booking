<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UI\Web\Controller;

use App\Application\Command\CreateUserCommand;
use App\Security\Authenticator;
use App\UI\Web\Request\CredentialsRequest;
use App\UI\Web\Request\LogoutRequest;
use Psr\Http\Message\ResponseInterface;
use Spiral\Filters\Exception\ValidationException;
use Spiral\Router\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/login', name: 'login', methods: 'GET')]
    public function loginForm(): ResponseInterface
    {
        return $this->render('auth/login');
    }

    #[Route('/login', name: 'login.post', methods: 'POST')]
    public function login(CredentialsRequest $request, Authenticator $authenticator): ResponseInterface
    {
        try {
            $authenticator->authenticate($request->getCredentials());
        } catch (\Throwable $e) {
            dumprr($e->getMessage());
            $errors = $e instanceof ValidationException ? ['errors' => $e->errors] : ['errors' => [$e->getMessage()]];

            return $this->render('auth/login', $errors);
        }

        return $this->redirectToRoute('personal.tickets');
    }

    #[Route('/logout', name: 'logout', methods: 'GET')]
    public function logout(LogoutRequest $logout, Authenticator $authenticator): ResponseInterface
    {
        $authenticator->close($logout->token);

        return $this->redirectToRoute('index');
    }

    #[Route('/register', name: 'register', methods: 'GET')]
    public function registerForm(): ResponseInterface
    {
        return $this->render('auth/register');
    }

    #[Route('/register', name: 'register.post', methods: 'POST')]
    public function register(CredentialsRequest $request, Authenticator $authenticator): ResponseInterface
    {
        try {
            $this->exec(new CreateUserCommand($request->getEmail(), $request->getPassword()));
            $authenticator->authenticate($request->getCredentials());
        } catch (\Throwable $e) {
            return $this->render('auth/register', ['errors' => [$e->getMessage()]]);
        }

        return $this->redirectToRoute('personal.tickets');
    }
}
