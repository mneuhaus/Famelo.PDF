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
use TYPO3\Flow\Exception;

/**
 * @Flow\Scope("prototype")
 */
class RemoteGenerator implements PdfGeneratorInterface {

	/**
	 * @var mixed
	 */
	protected $format;

	/**
	 * @var string
	 */
	protected $options = array(
		'margin-bottom' => 0,
		'margin-top' => 0,
		'margin-left' => 0,
		'margin-right' => 0
	);

	/**
	 * @var string
	 */
	protected $host;

	/**
	 * @var object
	 */
	protected $snappyPdf;

	public function __construct($options) {
		if (!isset($options['host'])) {
			throw Exception('You need to configure you\'r a remote host in "Famelo.Pdf.DefaultGeneratorOptions.host".');
		}
		$this->host = $options['host'];
	}

	public function setFormat($format) {
		if (substr($format, -2) == '-L') {
			$this->snappyPdf->setOption('orientation', 'Landscape');
			$format = substr($format, 0, -2);
		}
		$this->options['page-size'] = $format;
	}

	public function setHeader($content) {
		$this->options['header-html'] = $content;
	}

	public function setFooter($content) {
		$this->options['footer-html'] = $content;
	}

	public function setOption($name, $value) {
		$this->options[$name] = $value;
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

	public function generate($content) {
		$this->options['content'] = $content;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->host);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->options));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec ($ch);

		curl_close ($ch);
		return $result;
	}
}
?>
