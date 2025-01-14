<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v11\v0\ExtbaseControllerActionsMustReturnResponseInterfaceRector;
use Ssch\TYPO3Rector\Rector\v11\v0\ForwardResponseInsteadOfForwardMethodRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->rule(ForwardResponseInsteadOfForwardMethodRector::class);
    $rectorConfig->ruleWithConfiguration(ExtbaseControllerActionsMustReturnResponseInterfaceRector::class, [
        ExtbaseControllerActionsMustReturnResponseInterfaceRector::REDIRECT_METHODS => [
            'redirect',
            'redirectToUri',
            'additionalRedirectMethod',
        ],
    ]);
};
