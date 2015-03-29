<?php
namespace Famelo\PDF\Generator;

/*                                                                        *
 * This script belongs to the FLOW3 package "Famelo.PDF".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
require_once(FLOW_PATH_PACKAGES . 'Application/Famelo.PDF/Resources/Private/PHP/pdfcrowd/pdfcrowd.php');

/**
 * @Flow\Scope("prototype")
 */
class PdfCrowdGenerator implements PdfGeneratorInterface {

    /**
     * @var string
     */
    protected $format;

    /**
     * @var string
     */
    protected $header;

    /**
     * @var string
     */
    protected $footer;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $apikey;

    /**
     * @var string
     */
    protected $options = array(
        'encoding' => '',
        'format' => 'A4',
        'orientation' => 'P',
        'margin-left' => 15,
        'margin-right' => 15,
        'margin-top' => 16,
        'margin-bottom' => 16
    );

    public function __construct($options) {
        if (!isset($options['username'])) {
            throw \Exception('You need to configure you\'r a username in "Famelo.Pdf.DefaultGeneratorOptions.username".');
        }
        if (!isset($options['apiKey'])) {
            throw \Exception('You need to configure you\'r a apiKey in "Famelo.Pdf.DefaultGeneratorOptions.apiKey".');
        }
        $this->apiKey = $options['apiKey'];
        $this->username = $options['username'];
    }

    public function setFormat($format) {
        $this->setOption('format', $format);
    }

    public function setHeader($content) {
        $this->header = $content;
    }

    public function setFooter($content) {
        $this->footer = $content;
    }

    public function setOption($name, $value) {
        $this->options[$name] = $value;
    }

    public function generate($content) {
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

    public function sendPdf($content, $filename = NULL) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        echo $this->generate($content);
    }

    public function downloadPdf($content, $filename = NULL) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo $this->generate($content);
    }

    public function savePdf($content, $filename) {
        $result = $this->generate($content);
        file_put_contents($filename, $result);
    }
}
?>
