<?php
declare(strict_types=1);

namespace App;

use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Psr\Http\Message\ServerRequestInterface;

class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
    public function bootstrap(): void
    {
        parent::bootstrap();

        // Load Authentication plugin
        $this->addPlugin('Authentication');

        if (PHP_SAPI !== 'cli') {
            FactoryLocator::add('Table', (new TableLocator())->allowFallbackClass(false));
        }
    }

    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // Error handler
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))

            // Static assets
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Routing
            ->add(new RoutingMiddleware($this))

            // Parse JSON / form data
            ->add(new BodyParserMiddleware())

            // Authentication (MUST be before CSRF)
            ->add(new AuthenticationMiddleware($this))

            // CSRF Protection (ONLY ONE)
            ->add(new CsrfProtectionMiddleware([
                'httponly' => true,

                // Skip CSRF for specific routes (login/register/API)
                'skipCheckCallback' => function ($request) {
                    $path = $request->getUri()->getPath();

                    return in_array($path, [
                        '/users/login',
                        '/users/register',
                    ]) || str_starts_with($path, '/api/');
                },
            ]));

        return $middlewareQueue;
    }

    // Authentication setup
   public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
{
    $service = new AuthenticationService([
        'unauthenticatedRedirect' => '/users/login',
        'queryParam' => 'redirect',
    ]);

    // Session Auth
    $service->loadAuthenticator('Authentication.Session');

    // ✅ Correct Form Auth
    $service->loadAuthenticator('Authentication.Form', [
        'loginUrl' => '/users/login',

        'fields' => [
            'username' => 'email',
            'password' => 'password',
        ],

        'identifier' => [
            'className' => 'Authentication.Password',
            'fields' => [
                'username' => 'email',
                'password' => 'password',
            ]
        ]
    ]);

    return $service;
}

    public function services(ContainerInterface $container): void
    {
        // You can register services here if needed
    }
}