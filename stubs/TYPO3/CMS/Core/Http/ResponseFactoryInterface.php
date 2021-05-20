<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\Http;

use Psr\Http\Message\ResponseInterface;

if(class_exists('TYPO3\CMS\Core\Http\ResponseFactoryInterface')) {
    return;
}

interface ResponseFactoryInterface
{
    public function createHtmlResponse(string $html): ResponseInterface;
    public function createJsonResponse(string $json): ResponseInterface;
}
