<?php

namespace PhpSchool\CliMdRendererTest\Renderer;

use PhpSchool\CliMdRenderer\CliRenderer;
use PhpSchool\CliMdRenderer\Renderer\DocumentRenderer;
use PhpSchool\CliMdRenderer\Renderer\ParagraphRenderer;
use PhpSchool\CliMdRendererTest\RendererTestInterface;
use Colors\Color;
use League\CommonMark\Block\Element\Document;
use League\CommonMark\Block\Element\Paragraph;
use League\CommonMark\Environment;

class DocumentRendererTest extends AbstractRendererTest implements RendererTestInterface
{
    public function getRendererClass(): string
    {
        return DocumentRenderer::class;
    }

    public function testRender(): void
    {
        $class = $this->getRendererClass();
        $renderer = new $class();
        $doc = new Document();
        $doc->appendChild(new Paragraph());

        $color = new Color();
        $color->setForceStyle(true);
        $environment = (new Environment())->addBlockRenderer(Paragraph::class, new ParagraphRenderer());
        $cliRenderer = new CliRenderer($environment, $color);

        $this->assertEquals(
            "\n\n",
            $renderer->render($doc, $cliRenderer)
        );
    }
}
