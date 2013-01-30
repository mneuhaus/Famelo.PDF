<?php
namespace Famelo\PDF;

/*                                                                        *
 * This script belongs to the FLOW3 package "SwiftMailer".                *
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
 * Document class for the SwiftMailer package
 *
 * @Flow\Scope("prototype")
 */
class Document {
	/*
	 * @var string
	 */
	protected $templatePath = 'resource://@package/Private/Documents/@document.html';

	/*
	 * @var string
	 */
	protected $layoutRootPath = 'resource://@package/Private/Layouts/';

	/*
	 * @var string
	 */
	protected $partialRootPath = 'resource://@package/Private/Partials/';

	/**
	 * @var string
	 */
	protected $document = 'Standard';

	/**
	 * @var string
	 */
	protected $package = NULL;

	/**
	 * The view
	 *
	 * @var \TYPO3\Fluid\View\StandaloneView
	 * @Flow\Inject
	 */
	protected $view;

	public function __construct($document) {
		$this->setDocument($document);
	}

	public function setDocument($document) {
		$parts = explode(':', $document);
		if (count($parts) > 1) {
			$this->package = $parts[0];
			$this->document = $parts[1];
		} else {
			$this->document = $document;
		}
		return $this;
	}

	public function render() {
		if ($this->package === NULL) {
			$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
			$class = $trace[0]['class'];
			preg_match('/([A-Za-z]*)\\\\([A-Za-z]*)/', $class, $match);
			$this->package = $match[1] . '.' . $match[2];
		}

		$replacements = array(
			'@package' => $this->package,
			'@document' => $this->document
		);
		$template = str_replace(array_keys($replacements), array_values($replacements), $this->templatePath);
		$this->view->setTemplatePathAndFilename($template);

		$layoutRootPath = str_replace(array_keys($replacements), array_values($replacements), $this->layoutRootPath);
		$this->view->setLayoutRootPath($layoutRootPath);

		$partialRootPath = str_replace(array_keys($replacements), array_values($replacements), $this->partialRootPath);
		$this->view->setPartialRootPath($partialRootPath);

		$this->view->setFormat('html');

		$this->view->getRequest()->setControllerPackageKey($this->package);

		return $this->view->render();
	}

	public function send($filename = NULL) {
		$content = $this->render();

		$previousErrorReporting = error_reporting(0);
		$pdf = new \mPDF();
		$pdf->WriteHTML($content);
		$output = $pdf->Output('', 'i');
		error_reporting($previousErrorReporting);
	}

	public function download($filename = NULL) {
		$content = $this->render();

		$previousErrorReporting = error_reporting(0);
		$pdf = new \mPDF();
		$pdf->WriteHTML($content);
		$output = $pdf->Output($filename, 'd');
		error_reporting($previousErrorReporting);
	}

	public function save($filename) {
		$previousErrorReporting = error_reporting(0);
		$pdf = new \mPDF();
		$pdf->WriteHTML($content);
		$pdf->Output($filename, 'f');
		error_reporting($previousErrorReporting);
		exit;
	}

	public function assign($key, $value) {
		$this->view->assign($key, $value);
		return $this;
	}

	public function assignMultiple(array $values) {
		foreach ($values as $key => $value) {
			$this->assign($key, $value);
		}
		return $this;
	}
}
?>