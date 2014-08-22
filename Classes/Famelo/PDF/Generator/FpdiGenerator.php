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

use Famelo\PDF\View\StandaloneView;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("prototype")
 */
class FpdiGenerator implements PdfGeneratorInterface {

	/**
	 * @var string
	 */
	protected $format;

	/**
	 * @var StandaloneView
	 */
	protected $view;

	public function __construct($options, $view) {
		$this->view = $view;
	}

	public function setFormat($format) {
		$this->format = $format;
	}

	public function setHeader($content) {

	}

	public function setFooter($content) {

	}

	public function setOption($name, $value) {

	}

	public function sendPdf($content, $filename = NULL) {
		$fpdi = $this->generate();
		$fpdi->Output();
	}

	public function downloadPdf($content, $filename = NULL) {
		$fpdi = $this->generate();
		$fpdi->Output($filename, 'D');
	}

	public function savePdf($content, $filename) {
		$fpdi = $this->generate();
		$fpdi->Output($filename, 'F');
	}

	public function generate() {
		$container = $this->view->getViewHelperVariableContainer();
		if ($container->exists('Famelo\Pdf\ViewHelpers\Fpdi\DefaultsViewHelper', 'defaults')) {
			$defaults = $container->get('Famelo\Pdf\ViewHelpers\Fpdi\DefaultsViewHelper', 'defaults');
		}
		if ($container->exists('Famelo\Pdf\ViewHelpers\Fpdi\TemplateViewHelper', 'template')) {
			$template = $container->get('Famelo\Pdf\ViewHelpers\Fpdi\TemplateViewHelper', 'template');
		}
		if ($container->exists('Famelo\Pdf\ViewHelpers\Fpdi\TextViewHelper', 'texts')) {
			$texts = $container->get('Famelo\Pdf\ViewHelpers\Fpdi\TextViewHelper', 'texts');
		}

		$fpdi = new \fpdi\FPDI();
		$fpdi->AddPage();
		$fpdi->setSourceFile($template);
		$fpdi->useTemplate($fpdi->importPage(1), 0, 0, 0);

		foreach ($texts as $text) {
			foreach ($defaults as $key => $value) {
				if (isset($text[$key]) && !empty($text[$key])) {
					continue;
				}
				$text[$key] = $value;
			}

			$text['font-weight'] = $text['font-weight'] == 'bold' ? 'B' : '';

			$fpdi->SetFont($text['font'], $text['font-weight'], $text['font-size']);
			#$fpdi->SetTextColor($page[$i][$x]['color_r'], $page[$i][$x]['color_b'], $page[$i][$x]['color_g']);
			$fpdi->SetXY($text['x'], $text['y']);
			$fpdi->Write(0, $text['content']);
		}
		return $fpdi;
	}
}
?>
