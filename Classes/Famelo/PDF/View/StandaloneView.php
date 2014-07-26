<?php
namespace Famelo\PDF\View;

/*                                                                        *
 * This script belongs to the FLOW3 package "Famelo.Messaging".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A standalone template view.
 * Helpful if you want to use Fluid separately from MVC
 * E.g. to generate template based emails.
 *
 * @api
 */
class StandaloneView extends \TYPO3\Fluid\View\StandaloneView {
	public function initializeObject() {
		parent::initializeObject();

		$this->request->setFormat('html');
	}

	public function getViewHelperVariableContainer() {
		return $this->baseRenderingContext->getViewHelperVariableContainer();
	}
}
