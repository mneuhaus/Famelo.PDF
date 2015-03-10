<?php
namespace Famelo\PDF\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.Pdf".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * @api
 */
class FooterViewHelper extends AbstractViewHelper {
	
	/**
	 * NOTE: This property has been introduced via code migration to ensure backwards-compatibility.
	 * @see AbstractViewHelper::isOutputEscapingEnabled()
	 * @var boolean
	 */
	protected $escapeOutput = FALSE;
	/**
	 * This tag will not be rendered at all.
	 *
	 * @return void
	 * @api
	 */
	public function render() {
		$content = $this->renderChildren();
		$this->viewHelperVariableContainer->add('Famelo\Pdf\ViewHelpers\FooterViewHelper', 'footer', $content);
	}
}
