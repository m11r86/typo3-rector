<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v7\v5;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/7.5/Deprecation-69754-TcaCtrlIconfileUsingRelativePathToExtAndFilenameOnly.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v7\v5\UseExtPrefixForTcaIconFileRector\UseExtPrefixForTcaIconFileRectorTest
 */
final class UseExtPrefixForTcaIconFileRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Deprecate relative path to extension directory and using filename only in TCA ctrl iconfile',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
[
    'ctrl' => [
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('my_extension') . 'Resources/Public/Icons/image.png'
    ]
];
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
[
    'ctrl' => [
        'iconfile' => 'EXT:my_extension/Resources/Public/Icons/image.png'
    ]
];
CODE_SAMPLE
                ),

            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Return_::class];
    }

    /**
     * @param Return_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isFullTca($node)) {
            return null;
        }

        $ctrlArrayItem = $this->extractCtrl($node);
        if (! $ctrlArrayItem instanceof ArrayItem) {
            return null;
        }

        $ctrlItems = $ctrlArrayItem->value;

        if (! $ctrlItems instanceof Array_) {
            return null;
        }

        foreach ($ctrlItems->items as $fieldValue) {
            if (! $fieldValue instanceof ArrayItem) {
                continue;
            }

            if (! $fieldValue->key instanceof Expr) {
                continue;
            }

            if ($this->valueResolver->isValue($fieldValue->key, 'iconfile')) {
                $this->refactorIconFile($fieldValue);
                return $node;
            }
        }

        return null;
    }

    private function refactorIconFile(ArrayItem $fieldValue): void
    {
        if (! $fieldValue->value instanceof Expr) {
            return;
        }

        if ($fieldValue->value instanceof Concat) {
            $staticCall = $fieldValue->value->left;

            if (! $staticCall instanceof StaticCall) {
                return;
            }

            if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
                $staticCall,
                new ObjectType('TYPO3\CMS\Core\Utility\ExtensionManagementUtility')
            )) {
                return;
            }

            if (! $this->isName($staticCall->name, 'extRelPath')) {
                return;
            }

            if (! isset($staticCall->args[0])) {
                return;
            }

            $extensionKey = $this->valueResolver->getValue($staticCall->args[0]->value);

            if ($extensionKey === null) {
                return;
            }

            $pathToFileNode = $fieldValue->value->right;
            if (! $pathToFileNode instanceof String_) {
                return;
            }

            $pathToFile = $this->valueResolver->getValue($pathToFileNode);

            if ($pathToFile === null) {
                return;
            }

            $fieldValue->value = new String_(sprintf('EXT:%s/%s', $extensionKey, ltrim((string) $pathToFile, '/')));
        }

        if (! $fieldValue->value instanceof String_) {
            return;
        }

        $pathToFile = $this->valueResolver->getValue($fieldValue->value);

        if ($pathToFile === null) {
            return;
        }

        if (str_contains((string) $pathToFile, '/')) {
            return;
        }

        $fieldValue->value = new String_(sprintf('EXT:t3skin/icons/gfx/i/%s', $pathToFile));
    }
}
