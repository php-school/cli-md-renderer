<?php

namespace PhpSchool\CliMdRendererTest\InlineRenderer;

use PhpSchool\CliMdRenderer\CliRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\StrongRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\TextRenderer;
use PhpSchool\CliMdRendererTest\RendererTestInterface;
use Colors\Color;
use League\CommonMark\Environment;
use League\CommonMark\Inline\Element\Strong;
use League\CommonMark\Inline\Element\Text;

class StrongRendererTest extends AbstractInlineRendererTest implements RendererTestInterface
{
    public function getRendererClass(): string
    {
        return StrongRenderer::class;
    }

    public function testRender(): void
    {
        $class = $this->getRendererClass();
        $renderer = new $class();
        $emphasis = new Strong();
        $emphasis->appendChild(new Text('Some Text'));

        $color = new Color();
        $color->setForceStyle(true);
        $environment = (new Environment())->addInlineRenderer(Text::class, new TextRenderer());
        $cliRenderer = new CliRenderer($environment, $color);

        $this->assertEquals(
            "[1mSome Text[0m",
            $renderer->render($emphasis, $cliRenderer)
        );
    }
}
