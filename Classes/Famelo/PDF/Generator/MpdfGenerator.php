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

/**
 * @Flow\Scope("prototype")
 */
class MpdfGenerator implements PdfGeneratorInterface {

	/**
	 * @var string
	 */
	protected $format;

	public function setFormat($format) {
		$this->format = $format;
	}

	public function setHeader($content) {

	}

	public function setFooter($content) {

	}

	public function sendPdf($content, $filename = NULL) {
		$previousErrorReporting = error_reporting(0);
		$pdf = new \mPDF('', $this->format);
		$pdf->WriteHTML($content);
		$pdf->Output($filename, 'i');
		error_reporting($previousErrorReporting);
	}

	public function downloadPdf($content, $filename = NULL) {
		$previousErrorReporting = error_reporting(0);
		$pdf = new \mPDF('', $this->format);
		$pdf->WriteHTML($content);
		$pdf->Output($filename, 'd');
		error_reporting($previousErrorReporting);
	}

	public function savePdf($content, $filename) {
		$previousErrorReporting = error_reporting(0);
		$pdf = new \mPDF('', $this->format);
		$pdf->WriteHTML($content);
		$pdf->Output($filename, 'f');
		error_reporting($previousErrorReporting);
	}
}
?>
