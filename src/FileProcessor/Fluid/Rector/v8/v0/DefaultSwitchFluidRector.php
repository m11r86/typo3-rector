<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\v8\v0;

use Nette\Utils\Strings;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FileProcessor\Fluid\Rector\FluidRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.0/Deprecation-73068-DeprecatedDefaultArgumentOnFcase.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\Fluid\Rector\v8\v0\DefaultSwitchFluidRector\DefaultSwitchFluidRectorTest
 */
final class DefaultSwitchFluidRector implements FluidRectorInterface
{
    /**
     * @var string
     */
    private const PATTERN = '#<f:case default="(1|true)">(.*)<\/f:case>#imsU';

    /**
     * @var string
     */
    private const REPLACEMENT = '<f:defaultCase>$2</f:defaultCase>';

    public function transform(File $file): void
    {
        $content = $file->getFileContent();

        $content = Strings::replace($content, self::PATTERN, self::REPLACEMENT);

        $file->changeFileContent($content);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use <f:defaultCase> instead of <f:case default="1">', [
            new CodeSample(
                <<<'CODE_SAMPLE'
<f:switch expression="{someVariable}">
    <f:case value="...">...</f:case>
    <f:case value="...">...</f:case>
    <f:case value="...">...</f:case>
    <f:case default="1">...</f:case>
</f:switch>
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
<f:switch expression="{someVariable}">
    <f:case value="...">...</f:case>
    <f:case value="...">...</f:case>
    <f:case value="...">...</f:case>
    <f:defaultCase>...</f:defaultCase>
</f:switch>
CODE_SAMPLE
            ),
        ]);
    }
}
