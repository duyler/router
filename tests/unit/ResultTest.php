<?php

declare(strict_types=1);

namespace Duyler\Router\Test;

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

    public function testScenario(): void
    {
        $this->assertEquals('', self::getResult()->scenario);
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
