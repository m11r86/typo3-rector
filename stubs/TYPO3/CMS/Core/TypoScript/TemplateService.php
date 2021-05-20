<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\TypoScript;

if (class_exists('TYPO3\CMS\Core\TypoScript\TemplateService')) {
    return;
}

class TemplateService
{
    /**
     * @var bool
     */
    public $forceTemplateParsing = false;

    public function getFileName($file): void
    {
    }

    public function fileContent($fileName): void
    {
    }

    public static function init(): void
    {
    }
}
