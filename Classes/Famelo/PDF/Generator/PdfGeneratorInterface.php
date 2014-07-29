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

interface PdfGeneratorInterface {

	public function setFormat($format);

	public function setHeader($content);

	public function setFooter($content);

	public function setOption($name, $value);

	public function sendPdf($content, $filename = NULL);

	public function downloadPdf($content, $filename = NULL);

	public function savePdf($content, $filename);

}
?>
