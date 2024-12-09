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

use Knp\Snappy\Pdf;
use Neos\Flow\Exception;

class WebkitGenerator implements PdfGeneratorInterface
{
    protected string|array $format;

    protected array $options = [
        'margin-bottom' => 0,
        'margin-top' => 0,
        'margin-left' => 0,
        'margin-right' => 0
    ];

    protected Pdf $snappyPdf;

    public function __construct(array $options)
    {
        if (!class_exists(Pdf::class)) {
            throw new Exception('You need to install "knplabs/knp-snappy" to use the WebkitGenerator!');
        }

        if (!isset($options['Binary'])) {
            throw new Exception('You need to configure your wkhtmltopdf binary in "Famelo.Pdf.DefaultGeneratorOptions.Binary".');
        }

        $this->snappyPdf = new Pdf($options['Binary']);
    }

    public function setFormat(string|array $format): void
    {
        if (str_ends_with($format, '-L')) {
            $this->snappyPdf->setOption('orientation', 'Landscape');
            $format = substr($format, 0, -2);
        }
        $this->options['page-size'] = $format;
    }

    public function setHeader(string $content): void
    {
        $this->options['header-html'] = $content;
    }

    public function setFooter(string $content): void
    {
        $this->options['footer-html'] = $content;
    }

    public function setOption(string $name, mixed $value): void
    {
        $this->options[$name] = $value;
    }

    public function sendPdf(string $content, string $filename = null): void
    {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        echo $this->snappyPdf->getOutputFromHtml($content, $this->options);
    }

    public function downloadPdf(string $content, string $filename = null): void
    {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo $this->snappyPdf->getOutputFromHtml($content, $this->options);
    }

    public function savePdf(string $content, string $filename): void
    {
        $this->snappyPdf->generateFromHtml($content, $filename, $this->options);
    }

    public function getPdfStream(string $content): string
    {
        return $this->snappyPdf->getOutputFromHtml($content, $this->options);
    }
}
