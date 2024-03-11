<?php

declare(strict_types=1);

namespace Duyler\Router\Test\Unit;

use Duyler\Router\CurrentRoute;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class ResultTest extends TestCase
{
    public function testStatus(): void
    {
        $this->assertFalse(self::getResult()->status);
    }

    public function testTarget(): void
    {
        $this->assertEquals('', self::getResult()->target);
    }

    public function testAttributes(): void
    {
        $this->assertEquals([], self::getResult()->attributes);
    }

    public function testLanguage(): void
    {
        $this->assertEquals('', self::getResult()->language);
    }

    private static function getResult(): CurrentRoute
    {
        return new CurrentRoute();
    }
}
