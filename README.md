CLI Markdown Renderer
===========
    <img src="https://github.com/AydinHassan/cli-md-renderer/workflows/CliMdRenderer/badge.svg">

[![Build Status](https://github.com/AydinHassan/cli-md-renderer/workflows/CliMdRenderer/badge.svg)](https://github.com/AydinHassan/cli-md-renderer/actions)
[![Windows Build Status](https://img.shields.io/appveyor/ci/AydinHassan/cli-md-renderer/master.svg?style=flat-square&label=Windows)](https://ci.appveyor.com/project/AydinHassan/cli-md-renderer)
[![Coverage Status](https://img.shields.io/codecov/c/github/AydinHassan/cli-md-renderer.svg?style=flat-square)](https://codecov.io/github/AydinHassan/cli-md-renderer)

### Usage

```php
<?php
require_once 'vendor/autoload.php';

use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use PhpSchool\CliMdRenderer\CliRendererFactory;

$parser = new DocParser(Environment::createCommonMarkEnvironment());
$cliRenderer = (new CliRendererFactory)->__invoke();
$ast = $parser->parse(file_get_contents('path/to/file.md'));

echo $cliRenderer->renderBlock($ast);
```

### Syntax Highlighting

`FencedCode` can be syntax highlighted. By default only PHP source code is Syntax Highlighted using: [kadet/keylighter](https://github.com/kadet1090/KeyLighter)
If you want to add syntax highlighting for other languages you should create a class which implements `\AydinHassan\CliMdRenderer\SyntaxHighlighterInterface`

It accepts code as a string and should return highlighted code as a string. You register your highlighter like so

```php
<?php

use PhpSchool\CliMdRenderer\Renderer\FencedCodeRenderer;

$codeRenderer = new FencedCodeRenderer;
$codeRenderer->addSyntaxHighlighter('js', new JsSyntaxHighlighter);
```

If you need to do this you cannot use the factory so construction will look something like:

```php
<?php 
require_once 'vendor/autoload.php';

use Colors\Color;
use League\CommonMark\Environment;

$environment = new Environment();
$environment->addExtension(new CliExtension());

$colors = new Color();
$colors->setForceStyle(true);

return new CliRenderer($environment, $colors);
```


### To Do
- [ ] Make configurable (Line Endings, colors, styles)
- [x] Image Renderer
- [x] List Renderer
- [x] Code Syntax Highlighting
- [x] Documentation 
