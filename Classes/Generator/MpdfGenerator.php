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

use Famelo\PDF\Error\UnknownGeneratorOptionException;
use Mpdf\Mpdf;

class MpdfGenerator implements PdfGeneratorInterface
{

    protected string|array $format;

    protected string $header = '';

    protected string $footer = '';

    protected array $options = [
        'encoding' => '',
        'format' => 'A4',
        'orientation' => 'P',
        'default_font_size' => 0,
        'default_font' => '',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 16,
        'margin_bottom' => 16,
        'margin_header' => 9,
        'margin_footer' => 9,
    ];

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
        $backwardsCompatabilityOptionNames = [
            'marginLeft' => 'margin_left',
            'marginRight' => 'margin_right',
            'marginTop' => 'margin_top',
            'marginBottom' => 'margin_bottom',
            'marginHeader' => 'margin_header',
            'marginFooter' => 'margin_footer',
            'fontSize' => 'default_font_size',
            'margin-left' => 'margin_left',
            'margin-right' => 'margin_right',
            'margin-top' => 'margin_top',
            'margin-bottom' => 'margin_bottom',
            'margin-header' => 'margin_header',
            'margin-footer' => 'margin_footer',
            'font-size' => 'default_font_size',
            'font' => 'default_font'
        ];
        if (isset($backwardsCompatabilityOptionNames[$name])) {
            $name = $backwardsCompatabilityOptionNames[$name];
        }
        if (!isset($this->options[$name])) {
            throw new UnknownGeneratorOptionException('The option "' . $name . '" you\'re trying to set does not exist. This generator supports these options: ' . chr(10) . chr(10) . implode(chr(10), array_keys($this->options)), 1421314368);
        }
        $this->options[$name] = $value;
    }

    public function getMpdfInstance(): Mpdf
    {
        $mpdf = new Mpdf([
            'encoding' => $this->options['encoding'],
            'format' => $this->options['format'],
            'default_font_size' => $this->options['default_font_size'],
            'default_font' => $this->options['default_font'],
            'margin_left' => $this->options['margin_left'],
            'margin_right' => $this->options['margin_right'],
            'margin_top' => $this->options['margin_top'],
            'margin_bottom' => $this->options['margin_bottom'],
            'margin_header' => $this->options['margin_header'],
            'margin_footer' => $this->options['margin_footer'],
            'orientation' => $this->options['orientation']
        ]);

        if ($this->footer !== '') {
            $mpdf->SetHTMLFooter($this->footer);
        }
        if ($this->header !== '') {
            $mpdf->SetHTMLHeader($this->header);
        }
        return $mpdf;
    }

    public function getPdfStream(string $content): string
    {
        $previousErrorReporting = error_reporting(0);
        $pdf = $this->getMpdfInstance();
        $pdf->WriteHTML($content);
        error_reporting($previousErrorReporting);
        return (string)$pdf->Output('', 'S');
    }

    public function sendPdf(string $content, string $filename = null): void
    {
        $previousErrorReporting = error_reporting(0);
        $pdf = $this->getMpdfInstance();
        $pdf->WriteHTML($content);
        $pdf->Output($filename, 'i');
        error_reporting($previousErrorReporting);
    }

    public function downloadPdf(string $content, string $filename = null): void
    {
        $previousErrorReporting = error_reporting(0);
        $pdf = $this->getMpdfInstance();
        $pdf->WriteHTML($content);
        $pdf->Output($filename, 'd');
        error_reporting($previousErrorReporting);
    }

    public function savePdf(string $content, string $filename): void
    {
        $previousErrorReporting = error_reporting(0);
        $pdf = $this->getMpdfInstance();
        $pdf->WriteHTML($content);
        $pdf->Output($filename, 'f');
        error_reporting($previousErrorReporting);
    }
}
