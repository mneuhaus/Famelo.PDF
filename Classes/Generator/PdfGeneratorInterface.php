<?php
declare(strict_types=1);

namespace Famelo\PDF\Generator;

/*
 * This file is part of the Famelo.PDF package.
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

interface PdfGeneratorInterface
{

    public function setFormat(string|array $format): void;

    public function setHeader(string $content): void;

    public function setFooter(string $content): void;

    public function setOption(string $name, mixed $value): void;

    public function sendPdf(string $content, string $filename = null): void;

    public function downloadPdf(string $content, string $filename = null): void;

    public function savePdf(string $content, string $filename): void;

    public function getPdfStream(string $content): string;

}
