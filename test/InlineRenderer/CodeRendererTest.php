<?php

namespace PhpSchool\CliMdRendererTest\InlineRenderer;

use PhpSchool\CliMdRenderer\CliRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\CodeRenderer;
use PhpSchool\CliMdRendererTest\RendererTestInterface;
use Colors\Color;
use League\CommonMark\Environment;
use League\CommonMark\Inline\Element\Code;

class CodeRendererTest extends AbstractInlineRendererTest implements RendererTestInterface
{
    public function getRendererClass(): string
    {
        return CodeRenderer::class;
    }

    public function testRender(): void
    {
        $class = $this->getRendererClass();
        $renderer = new $class();
        $code = new Code('SOME CODE');
        $color = new Color();
        $color->setForceStyle(true);
        $cliRenderer = new CliRenderer(new Environment(), $color);

        $this->assertEquals(
            "[33mSOME CODE[0m",
            $renderer->render($code, $cliRenderer)
        );
    }
}
