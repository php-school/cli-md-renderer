<?php

namespace PhpSchool\CliMdRendererTest\Renderer;

use PhpSchool\CliMdRenderer\CliRenderer;
use PhpSchool\CliMdRenderer\Highlighter\PhpHighlighter;
use PhpSchool\CliMdRenderer\SyntaxHighlighterInterface;
use PhpSchool\CliMdRendererTest\RendererTestInterface;
use Colors\Color;
use InvalidArgumentException;
use Kadet\Highlighter\KeyLighter;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use PhpSchool\CliMdRenderer\Renderer\FencedCodeRenderer;
use League\CommonMark\Environment\Environment;

class FencedCodeRendererTest extends AbstractRendererTest implements RendererTestInterface
{
    public function getRendererClass(): string
    {
        return FencedCodeRenderer::class;
    }

    public function testAddSyntaxHighlighterViaConstructor(): void
    {
        $renderer = new FencedCodeRenderer(
            ['php' => $highlighter = $this->createStub(SyntaxHighlighterInterface::class)]
        );

        $this->assertSame($renderer->getSyntaxHighlighters(), ['php' => $highlighter]);
    }

    public function testAddSyntaxHighlighter(): void
    {
        $renderer = new FencedCodeRenderer();
        $renderer->addSyntaxHighlighter('php', $highlighter = $this->createStub(SyntaxHighlighterInterface::class));

        $this->assertSame($renderer->getSyntaxHighlighters(), ['php' => $highlighter]);
    }

    public function testRenderPhpCode(): void
    {
        $renderer = $this->getRenderer();
        $code = new FencedCode(3, '`', 0);
        $code->setInfo('php');
        $code->setLiteral('<?php echo \'Hello World\';');

        $color = new Color();
        $color->setForceStyle(true);
        $cliRenderer = new CliRenderer(new Environment(), $color);

        $this->assertEquals(
            "    \e[93;1m<?php\e[0m \e[33mecho\e[0m \e[32m'Hello World'\e[0m\e[33m;\e[0m\e[0m\e[0m\n    ",
            $renderer->render($code, $cliRenderer)
        );
    }

    public function testRenderNonePhpCodeIsRendererYellow(): void
    {
        $renderer = $this->getRenderer();

        $code = new FencedCode(3, '`', 0);
        $code->setInfo('js');
        $code->setLiteral('console.log("lol js???")');

        $color = new Color();
        $color->setForceStyle(true);
        $cliRenderer = new CliRenderer(new Environment(), $color);

        $this->assertEquals(
            '    [33mconsole.log("lol js???")[0m',
            $renderer->render($code, $cliRenderer)
        );
    }

    public function testExceptionIsThrownIfNotCorrectBlock(): void
    {
        $block = $this->createStub(AbstractBlock::class);

        $cliRenderer = $this->createStub(CliRenderer::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Incompatible block type: "%s"', get_class($block)));

        $this->getRenderer()->render($block, $cliRenderer);
    }

    private function getRenderer(): FencedCodeRenderer
    {
        $class = $this->getRendererClass();
        $renderer = new $class();
        $renderer->addSyntaxHighlighter(
            'php',
            new PhpHighlighter(new KeyLighter())
        );

        return $renderer;
    }
}
