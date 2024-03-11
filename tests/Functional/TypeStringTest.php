<?php

declare(strict_types=1);

namespace Duyler\Router\Test\Functional;

use Duyler\Router\Enum\Type;
use Duyler\Router\Route;
use Duyler\Router\RouteCollection;
use Duyler\Router\Router;
use Duyler\Router\RouterConfig;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class TypeStringTest extends TestCase
{
    #[Test]
    public function without_language(): void
    {
        $routeCollection = new RouteCollection();
        new Route($routeCollection);
        Route::get('/page/{$pageName}')
            ->handler('Handler')
            ->where(['pageName' => Type::String]);

        $router = new Router(new RouterConfig());

        $result = $router->startRouting($routeCollection, $this->createRequest(
            path: 'page/one',
            host: 'localhost',
            scheme: 'http',
            method: 'GET',
        ));

        $this->assertTrue($result->status);
        $this->assertSame($result->attributes['pageName'], 'one');
        $this->assertEquals($result->language, '');
    }

    #[Test]
    public function with_language(): void
    {
        $routeCollection = new RouteCollection();
        new Route($routeCollection);
        Route::get('/page/{$pageName}')
            ->handler('Handler')
            ->where(['pageName' => Type::String]);

        $router = new Router(new RouterConfig(
            languages: ['ru', 'en'],
        ));

        $result = $router->startRouting($routeCollection, $this->createRequest(
            path: 'ru/page/one',
            host: 'localhost',
            scheme: 'http',
            method: 'GET',
        ));

        $this->assertTrue($result->status);
        $this->assertSame($result->attributes['pageName'], 'one');
        $this->assertEquals($result->language, 'ru');
    }

    #[Test]
    public function root_without_language(): void
    {
        $routeCollection = new RouteCollection();
        new Route($routeCollection);
        Route::get('/')
            ->handler('Handler');

        $router = new Router(new RouterConfig());

        $result = $router->startRouting($routeCollection, $this->createRequest(
            path: '/',
            host: 'localhost',
            scheme: 'http',
            method: 'GET',
        ));

        $this->assertTrue($result->status);
        $this->assertEquals($result->language, '');
    }

    protected function createRequest(string $path, string $host, string $scheme, string $method): ServerRequestInterface
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')->willReturn($path);
        $uri->method('getHost')->willReturn($host);
        $uri->method('getScheme')->willReturn($scheme);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getUri')->willReturn($uri);
        $request->method('getMethod')->willReturn($method);

        return $request;
    }
}
