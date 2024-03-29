<?php

declare(strict_types=1);

namespace Duyler\Router\Test\Unit;

use Duyler\Router\MatchedRoute;
use Duyler\Router\RouteDefinition;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class MatchedRouteTest extends TestCase
{
    public function testName(): void
    {
        $this->assertEquals('news.show', self::matchedRoute()->name);
    }

    public function testPattern(): void
    {
        $this->assertEquals('news/show/{$slug}', self::matchedRoute()->pattern);
    }

    public function testHandler(): void
    {
        $this->assertEquals('News', self::matchedRoute()->handler);
    }

    public function testScenario(): void
    {
        $this->assertEquals('Show', self::matchedRoute()->target);
    }

    public function testMethod(): void
    {
        $this->assertEquals('get', self::matchedRoute()->method);
    }

    public function testWhere(): void
    {
        $this->assertEquals(['slug' => '([a-z0-9\-]+)'], self::matchedRoute()->where);
    }

    private static function matchedRoute(): MatchedRoute
    {
        $definitionRoute = new RouteDefinition(
            method: 'get',
            pattern: 'news/show/{$slug}',
            name: 'news.show',
            handler: 'News',
            target: 'Show',
            where: ['slug' => '([a-z0-9\-]+)'],
        );
        return new MatchedRoute($definitionRoute);
    }
}
