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

use Famelo\PDF\View\StandaloneView;
use fpdi\FPDI;
use Neos\Flow\Exception;

class FpdiGenerator implements PdfGeneratorInterface
{

    protected string $format;

    protected StandaloneView $view;

    public function __construct(array $options, StandaloneView $view)
    {
        if (!class_exists(FPDI::class)) {
            throw new Exception('You need to install "itbz/fpdi" to use the FpdiGenerator!');
        }

        $this->view = $view;
    }

    public function setFormat(string|array $format): void
    {
        $this->format = $format;
    }

    public function setHeader(string $content): void
    {
    }

    public function setFooter(string $content): void
    {
    }

    public function setOption(string $name, mixed $value): void
    {
    }

    public function sendPdf(string $content, string $filename = null): void
    {
        $fpdi = $this->generate();
        $fpdi->Output();
    }

    public function downloadPdf(string $content, string $filename = NULL): void
    {
        $fpdi = $this->generate();
        $fpdi->Output($filename, 'D');
    }

    public function savePdf(string $content, string $filename): void
    {
        $fpdi = $this->generate();
        $fpdi->Output($filename, 'F');
    }

    public function getPdfStream(string $content): string
    {
        throw new \RuntimeException('Not implemented');
    }

    public function generate(): FPDI
    {
        $container = $this->view->getViewHelperVariableContainer();
        if ($container->exists('Famelo\Pdf\ViewHelpers\Fpdi\DefaultsViewHelper', 'defaults')) {
            $defaults = $container->get('Famelo\Pdf\ViewHelpers\Fpdi\DefaultsViewHelper', 'defaults');
        } else {
            $defaults = [];
        }
        if ($container->exists('Famelo\Pdf\ViewHelpers\Fpdi\TemplateViewHelper', 'template')) {
            $template = $container->get('Famelo\Pdf\ViewHelpers\Fpdi\TemplateViewHelper', 'template');
        } else {
            $template = '';
        }
        if ($container->exists('Famelo\Pdf\ViewHelpers\Fpdi\TextViewHelper', 'texts')) {
            $texts = $container->get('Famelo\Pdf\ViewHelpers\Fpdi\TextViewHelper', 'texts');
        } else {
            $texts = [];
        }

        $fpdi = new FPDI();
        $fpdi->AddPage();
        $fpdi->setSourceFile($template);
        $fpdi->useTemplate($fpdi->importPage(1), 0, 0, 0);

        foreach ($texts as $text) {
            foreach ($defaults as $key => $value) {
                if (!empty($text[$key])) {
                    continue;
                }
                $text[$key] = $value;
            }

            $text['font-weight'] = $text['font-weight'] === 'bold' ? 'B' : '';

            $fpdi->SetFont($text['font'], $text['font-weight'], $text['font-size']);
            $fpdi->SetXY($text['x'], $text['y']);
            $fpdi->Write(0, $text['content']);
        }
        return $fpdi;
    }
}
