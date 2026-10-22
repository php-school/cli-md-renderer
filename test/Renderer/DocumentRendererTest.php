<?php

namespace PhpSchool\CliMdRendererTest\Renderer;

use PhpSchool\CliMdRenderer\CliRenderer;
use PhpSchool\CliMdRenderer\Renderer\DocumentRenderer;
use PhpSchool\CliMdRenderer\Renderer\ParagraphRenderer;
use PhpSchool\CliMdRendererTest\RendererTestInterface;
use Colors\Color;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Environment\Environment;

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
        $environment = (new Environment())->addRenderer(Paragraph::class, new ParagraphRenderer());
        $cliRenderer = new CliRenderer($environment, $color);

        $this->assertEquals(
            "\n\n",
            $renderer->render($doc, $cliRenderer)
        );
    }
}
