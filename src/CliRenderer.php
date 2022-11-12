<?php

namespace AydinHassan\CliMdRenderer;

use AydinHassan\CliMdRenderer\InlineRenderer\CliInlineRendererInterface;
use AydinHassan\CliMdRenderer\Renderer\CliBlockRendererInterface;
use Colors\Color;
use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\EnvironmentInterface;
use League\CommonMark\Inline\Element\AbstractInline;
use RuntimeException;

class CliRenderer implements ElementRendererInterface
{
    /**
     * @var EnvironmentInterface
     */
    private $environment;

    /**
     * @var Color
     */
    private $color;

    public function __construct(EnvironmentInterface $environment, Color $color)
    {
        $this->environment = $environment;
        $this->color = $color;
    }

    /**
     * @param string $option
     * @param mixed  $default
     *
     * @return mixed|null
     */
    public function getOption(string $option, $default = null)
    {
        return $this->environment->getConfig('renderer/' . $option, $default);
    }

    /**
     * @param string $string
     * @param array<string>|string $colourOrStyle
     *
     * @return string
     */
    public function style(string $string, $colourOrStyle): string
    {
        if (is_array($colourOrStyle)) {
            $this->color->__invoke($string);

            while ($style = array_shift($colourOrStyle)) {
                $this->color->apply($style);
            }
            return $this->color->__toString();
        }

        return $this->color->__invoke($string)->apply($colourOrStyle, $string);
    }

    public function renderInline(AbstractInline $inline): string
    {
        $renderers = $this->environment->getInlineRenderersForClass(get_class($inline));

        foreach ($renderers as $renderer) {
            if (($result = $renderer->render($inline, $this)) !== null) {
                return $result;
            }
        }

        throw new RuntimeException(
            sprintf('Unable to find corresponding renderer for inline type: "%s"', get_class($inline))
        );
    }

    /**
     * @param AbstractInline[] $inlines
     *
     * @return string
     */
    public function renderInlines(iterable $inlines): string
    {
        $inlines = is_array($inlines) ? $inlines : iterator_to_array($inlines);

        return implode(
            "",
            array_map(
                function (AbstractInline $inline) {
                    return $this->renderInline($inline);
                },
                $inlines
            )
        );
    }

    public function renderBlock(AbstractBlock $block, bool $inTightList = false): string
    {
        $renderers = $this->environment->getBlockRenderersForClass(\get_class($block));

        /** @var BlockRendererInterface $renderer */
        foreach ($renderers as $renderer) {
            if (($result = $renderer->render($block, $this, $inTightList)) !== null) {
                return $result;
            }
        }

        throw new RuntimeException(
            sprintf('Unable to find corresponding renderer for block type: "%s"', get_class($block))
        );
    }

    /**
     * @param AbstractBlock[] $blocks
     *
     * @return string
     */
    public function renderBlocks(iterable $blocks, bool $inTightList = false): string
    {
        $blocks = is_array($blocks) ? $blocks : iterator_to_array($blocks);

        return implode(
            "\n",
            array_map(
                function (AbstractBlock $block) {
                    return $this->renderBlock($block);
                },
                $blocks
            )
        );
    }
}
