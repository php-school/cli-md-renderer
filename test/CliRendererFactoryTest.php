<?php

namespace PhpSchool\CliMdRendererTest;

use PhpSchool\CliMdRenderer\CliRenderer;
use PhpSchool\CliMdRenderer\CliRendererFactory;
use PHPUnit\Framework\TestCase;

class CliRendererFactoryTest extends TestCase
{
    public function testFactoryReturnsInstance(): void
    {
        $factory = new CliRendererFactory();
        $this->assertInstanceOf(CliRenderer::class, $factory->__invoke());
    }
}
