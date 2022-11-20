<?php

namespace PhpSchool\CliMdRendererTest\InlineRenderer;

use PhpSchool\CliMdRenderer\CliRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\TextRenderer;
use PhpSchool\CliMdRendererTest\RendererTestInterface;
use Colors\Color;
use League\CommonMark\Environment;
use League\CommonMark\Inline\Element\Text;

class TextRendererTest extends AbstractInlineRendererTest implements RendererTestInterface
{
    public function getRendererClass(): string
    {
        return TextRenderer::class;
    }

    public function testRender(): void
    {
        $class = $this->getRendererClass();
        $renderer = new $class();
        $text = new Text('HEY');

        $color = new Color();
        $color->setForceStyle(true);
        $cliRenderer = new CliRenderer(new Environment(), $color);

        $this->assertEquals(
            "HEY",
            $renderer->render($text, $cliRenderer)
        );
    }
}
