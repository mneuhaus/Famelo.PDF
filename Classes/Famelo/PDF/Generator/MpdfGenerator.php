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
require_once(FLOW_PATH_PACKAGES . 'Application/Famelo.PDF/Resources/Private/PHP/mpdf/mpdf.php');

/**
 * @Flow\Scope("prototype")
 */
class MpdfGenerator implements PdfGeneratorInterface {

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
	protected $options = array(
		'encoding' => '',
		'format' => 'A4',
		'orientation' => 'P',
		'font-size' => 0,
		'font' => '',
		'margin-left' => 15,
		'margin-right' => 15,
		'margin-top' => 16,
		'margin-bottom' => 16,
		'margin-header' => 9,
		'margin-footer' => 9,
	);

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
		$backwardsCompatabilityOptionNames = array(
			'marginLeft' => 'margin-left',
			'marginRight' => 'margin-right',
			'marginTop' => 'margin-top',
			'marginBottom' => 'margin-bottom',
			'marginHeader' => 'margin-header',
			'marginFooter' => 'margin-footer',
			'fontSize' => 'font-size'
		);
		if (isset($backwardsCompatabilityOptionNames[$name])) {
			$name = $backwardsCompatabilityOptionNames[$name];
		}
		if (!isset($this->options[$name])) {
			throw new \Famelo\PDF\Error\UnknownGeneratorOptionException('The option "' . $name . '" you\'re trying to set does not exist. This generator supports these options: ' . chr(10) . chr(10) . implode(chr(10), array_keys($this->options)), 1421314368);
		}
		$this->options[$name] = $value;
	}

	public function getMpdfInstance() {
		$mpdf = new \mPDF(
			$this->options['encoding'],
			$this->options['format'],
			$this->options['font-size'],
			$this->options['font'],
			$this->options['margin-left'],
			$this->options['margin-right'],
			$this->options['margin-top'],
			$this->options['margin-bottom'],
			$this->options['margin-header'],
			$this->options['margin-footer'],
			$this->options['orientation']
		);

		if ($this->footer !== NULL) {
			$mpdf->SetHTMLFooter($this->footer);
		}
		if ($this->header !== NULL) {
			$mpdf->SetHTMLHeader($this->header);
		}
		return $mpdf;
	}

	public function sendPdf($content, $filename = NULL) {
		$previousErrorReporting = error_reporting(0);
		$pdf = $this->getMpdfInstance();
		$pdf->WriteHTML($content);
		$pdf->Output($filename, 'i');
		error_reporting($previousErrorReporting);
	}

	public function downloadPdf($content, $filename = NULL) {
		$previousErrorReporting = error_reporting(0);
		$pdf = $this->getMpdfInstance();
		$pdf->WriteHTML($content);
		$pdf->Output($filename, 'd');
		error_reporting($previousErrorReporting);
	}

	public function savePdf($content, $filename) {
		$previousErrorReporting = error_reporting(0);
		$pdf = $this->getMpdfInstance();
		$pdf->WriteHTML($content);
		$pdf->Output($filename, 'f');
		error_reporting($previousErrorReporting);
	}
}
?>
