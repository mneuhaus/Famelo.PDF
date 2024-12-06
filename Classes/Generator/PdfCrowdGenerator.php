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

class PdfCrowdGenerator implements PdfGeneratorInterface
{
    protected string|array $format;

    protected string $header = '';

    protected string $footer = '';

    protected string $username;

    protected string $apiKey;

    protected array $options = [
        'encoding' => '',
        'format' => 'A4',
        'orientation' => 'P',
        'margin-left' => 15,
        'margin-right' => 15,
        'margin-top' => 16,
        'margin-bottom' => 16
    ];

    public function __construct(array $options)
    {
        if (!class_exists(\Pdfcrowd::class)) {
            throw new \Exception('You need to install "pdfcrowd/pdfcrowd" to use the PdfCrowdGenerator!');
        }

        if (!isset($options['username'])) {
            throw new \Exception('You need to configure your username in "Famelo.Pdf.DefaultGeneratorOptions.username".');
        }
        if (!isset($options['apiKey'])) {
            throw new \Exception('You need to configure your apiKey in "Famelo.Pdf.DefaultGeneratorOptions.apiKey".');
        }
        $this->apiKey = $options['apiKey'];
        $this->username = $options['username'];
    }

    public function setFormat(string|array $format): void
    {
        $this->setOption('format', $format);
    }

    public function setHeader(string $content): void
    {
        $this->header = $content;
    }

    public function setFooter(string $content): void
    {
        $this->footer = $content;
    }

    public function setOption(string $name, mixed $value): void
    {
        $this->options[$name] = $value;
    }

    public function generate(string $content): string
    {
        $instance = new \Pdfcrowd($this->username, $this->apiKey);
        $instance->setHeaderHtml($this->header);
        $instance->setFooterHtml($this->footer);
        $instance->setPageMargins(
            $this->options['margin-top'] * 2,
            $this->options['margin-right'],
            $this->options['margin-bottom'] * 2,
            $this->options['margin-left']
        );
        return $instance->convertHtml($content);
    }

    public function sendPdf(string $content, string $filename = null): void
    {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        echo $this->generate($content);
    }

    public function downloadPdf(string $content, string $filename = null): void
    {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo $this->generate($content);
    }

    public function savePdf(string $content, string $filename): void
    {
        $result = $this->generate($content);
        file_put_contents($filename, $result);
    }

    public function getPdfStream(string $content): string
    {
        return $this->generate($content);
    }
}
