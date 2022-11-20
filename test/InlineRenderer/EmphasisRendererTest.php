<?php

namespace PhpSchool\CliMdRendererTest\InlineRenderer;

use PhpSchool\CliMdRenderer\CliRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\EmphasisRenderer;
use PhpSchool\CliMdRenderer\InlineRenderer\TextRenderer;
use PhpSchool\CliMdRendererTest\RendererTestInterface;
use Colors\Color;
use League\CommonMark\Environment;
use League\CommonMark\Inline\Element\Emphasis;
use League\CommonMark\Inline\Element\Text;

class EmphasisRendererTest extends AbstractInlineRendererTest implements RendererTestInterface
{
    public function getRendererClass(): string
    {
        return EmphasisRenderer::class;
    }

    public function testRender(): void
    {
        $class = $this->getRendererClass();
        $renderer = new $class();
        $emphasis = new Emphasis();
        $emphasis->appendChild(new Text('Some Text'));

        $color = new Color();
        $color->setForceStyle(true);
        $environment = (new Environment())->addInlineRenderer(Text::class, new TextRenderer());
        $cliRenderer = new CliRenderer($environment, $color);

        $this->assertEquals(
            "[3mSome Text[0m",
            $renderer->render($emphasis, $cliRenderer)
        );
    }
}
