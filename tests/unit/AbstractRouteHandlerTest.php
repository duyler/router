<?php

declare(strict_types=1);

namespace Duyler\Router\Test;

use Duyler\Router\Enum\Type;
use Duyler\Router\Exception\HandlerIsNotSetException;
use Duyler\Router\Exception\PlaceholdersForPatternNotFoundException;
use Duyler\Router\Handler\AbstractRouteHandler;
use Duyler\Router\MatchedRoute;
use Duyler\Router\Request;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class AbstractRouteHandlerTest extends TestCase
{
    public function testGetIntegerPlaceholderRegExp(): void
    {
        $this->assertEquals('([0-9]+)', Type::Integer->value);
    }

    public function testGetStringPlaceholderRegExp(): void
    {
        $this->assertEquals('([a-z0-9\-]+)', Type::String->value);
    }

    public function testGetArrayPlaceholderRegExp(): void
    {
        $this->assertEquals('([a-z0-9]+)/(([a-z0-9\-]+/)+|([a-z0-9\-_]+)+)($)', Type::Array->value);
    }

    public function testRoute(): void
    {
        $routeHandler = $this->routeHandler();
        $routeHandler->route('get', 'news/show/{$slug}.html');

        $fillable = $routeHandler->fillable();

        $this->assertEquals('get', $fillable['method']);
        $this->assertEquals('news/show/{$slug}.html', $fillable['pattern']);
    }

    public function testWhere(): void
    {
        $routeHandler = $this->routeHandler();
        $routeHandler->where(['slug' => '([a-z0-9\-]+)']);

        $fillable = $routeHandler->fillable();

        $this->assertEquals(['slug' => '([a-z0-9\-]+)'], $fillable['where']);
    }

    public function testName(): void
    {
        $routeHandler = $this->routeHandler();
        $routeHandler->name('news.show');

        $fillable = $routeHandler->fillable();

        $this->assertEquals('news.show', $fillable['name']);
    }

    public function testHandler(): void
    {
        $routeHandler = $this->routeHandler();
        $routeHandler->handler('News');

        $fillable = $routeHandler->fillable();

        $this->assertEquals('News', $fillable['handler']);
    }

    public function testScenario(): void
    {
        $routeHandler = $this->routeHandler();
        $routeHandler->scenario('Show');

        $fillable = $routeHandler->fillable();

        $this->assertEquals('Show', $fillable['scenario']);
    }

    public function testMatch(): void
    {
        $routeHandler = $this->routeHandler();
        $routeHandler
            ->route('get', 'news/show/${id}-{$slug}.html')
            ->handler('News')
            ->scenario('Show')
            ->where(['id' => '([0-9]+)', 'slug' => '([a-z0-9\-]+)'])
        ;

        $this->assertTrue(true);
    }

    public function testMatchWhenPlaceholderHasCamelCaseCharacters(): void
    {
        $routeHandler = $this->routeHandler();
        $routeHandler
            ->route('get', 'news/show/${id}-{$slugString}.html')
            ->handler('News')
            ->scenario('Show')
            ->where(['id' => '([0-9]+)', 'slugString' => '([a-z0-9\-]+)'])
        ;

        $this->assertTrue(true);
    }

    public function testIsMatchedWhenIsNotMatched(): void
    {
        $routeHandler = $this->routeHandler();

        $this->assertFalse($routeHandler->isMatched());
    }

    private function routeHandler(): RouteHandler
    {
        $request = $this->createMock(Request::class);

        return new RouteHandler($request);
    }
}

class RouteHandler extends AbstractRouteHandler
{
    public function fillable(): array
    {
        return $this->fillable;
    }

    public function setMatched(MatchedRoute $matchedRoute): void
    {
        $this->matched = $matchedRoute;
    }
}
