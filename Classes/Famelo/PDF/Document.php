<?php
namespace Famelo\PDF;

/*                                                                        *
 * This script belongs to the FLOW3 package "Famelo.PDF".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Famelo\PDF\Generator\PdfGeneratorInterface;
use TYPO3\Flow\Annotations as Flow;

/**
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
	 * @var \Famelo\PDF\View\StandaloneView
	 * @Flow\Inject
	 */
	protected $view;

	/**
	 * @var string
	 */
	protected $format;

	/**
	 * @var array
	 */
	protected $options = array();

	/**
	 *
	 * @Flow\Inject(setting="DefaultGenerator", package="Famelo.PDF")
	 * @var string
	 */
	protected $defaultGenerator;

	/**
	 *
	 * @Flow\Inject(setting="DefaultGeneratorOptions", package="Famelo.PDF")
	 * @var array
	 */
	protected $defaultGeneratorOptions;

	/**
	 * @var PdfGeneratorInterface
	 */
	protected $generator;

	/**
	 * @var string
	 */
	protected $templateSource;

	public function __construct($document, $format = 'A4') {
		$this->setDocument($document);
		$this->format = $format;
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

	public function setTemplateSource($templateSource) {
		$this->templateSource = $templateSource;
	}

	public function setFormat($format) {
		$this->format = $format;
	}

	public function getGenerator() {
		if (!$this->generator instanceof PdfGeneratorInterface) {
			$this->generator = new $this->defaultGenerator($this->defaultGeneratorOptions, $this->view);
		}
		foreach ($this->options as $name => $value) {
			$this->generator->setOption($name, $value);
		}
		return $this->generator;
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
		if ($this->templateSource === NULL) {
			$template = str_replace(array_keys($replacements), array_values($replacements), $this->templatePath);
			$this->view->setTemplatePathAndFilename($template);

			$layoutRootPath = str_replace(array_keys($replacements), array_values($replacements), $this->layoutRootPath);
			$this->view->setLayoutRootPath($layoutRootPath);

			$partialRootPath = str_replace(array_keys($replacements), array_values($replacements), $this->partialRootPath);
			$this->view->setPartialRootPath($partialRootPath);
		} else {
			$this->view->setTemplateSource($this->templateSource);
		}

		$this->view->setFormat('html');

		$this->view->getRequest()->setControllerPackageKey($this->package);

		$content = $this->view->render();
		return $content;
	}

	public function setOptionsByViewHelper($generator) {
		$viewHelperVariableContainer = $this->view->getViewHelperVariableContainer();
		if ($viewHelperVariableContainer->exists('Famelo\Pdf\ViewHelpers\HeaderViewHelper', 'header')) {
			$header = $viewHelperVariableContainer->get('Famelo\Pdf\ViewHelpers\HeaderViewHelper', 'header');
			$generator->setHeader($header);
		}
		$viewHelperVariableContainer = $this->view->getViewHelperVariableContainer();
		if ($viewHelperVariableContainer->exists('Famelo\Pdf\ViewHelpers\FooterViewHelper', 'footer')) {
			$footer = $viewHelperVariableContainer->get('Famelo\Pdf\ViewHelpers\FooterViewHelper', 'footer');
			$generator->setFooter($footer);
		}
	}

	public function setOption($name, $value) {
		$this->options[$name] = $value;
	}

	public function send($filename = NULL) {
		$content = $this->render();
		$generator = $this->getGenerator();
		$this->setOptionsByViewHelper($generator);
		$generator->setFormat($this->format);
		$generator->sendPdf($content, $filename);
	}

	public function download($filename = NULL) {
		$content = $this->render();
		$generator = $this->getGenerator();
		$this->setOptionsByViewHelper($generator);
		$generator->setFormat($this->format);
		$generator->downloadPdf($content, $filename);
	}

	public function save($filename) {
		$content = $this->render();
		$generator = $this->getGenerator();
		$this->setOptionsByViewHelper($generator);
		$generator->setFormat($this->format);
		$generator->savePdf($content, $filename);
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
