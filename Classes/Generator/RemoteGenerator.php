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

class RemoteGenerator implements PdfGeneratorInterface
{
    protected string $format;

    protected array $options = [
        'margin-bottom' => 0,
        'margin-top' => 0,
        'margin-left' => 0,
        'margin-right' => 0
    ];

    protected string $host;

    public function __construct(array $options)
    {
        if (!isset($options['host'])) {
            throw new \Exception('You need to configure your remote host in "Famelo.Pdf.DefaultGeneratorOptions.host".');
        }
        $this->host = $options['host'];
    }

    public function setFormat(string|array $format): void
    {
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

    public function generate(string $content): string
    {
        $this->options['content'] = $content;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->host);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->options));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);
        return (string)$result;
    }
}
