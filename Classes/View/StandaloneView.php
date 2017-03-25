<?php
namespace Famelo\PDF\View;

/*                                                                        *
 * This script belongs to the FLOW package "Famelo.PDF".                  *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\View\ViewInterface;

/**
 * A standalone template view.
 * Helpful if you want to use Fluid separately from MVC
 * E.g. to generate template based emails.
 *
 * @api
 */
class StandaloneView extends \Neos\FluidAdaptor\View\StandaloneView {
    public function __construct() {
        parent::__construct();
    }

    public function initializeObject() {
        parent::initializeObject();

        $this->request->setFormat('html');
    }

    public function getViewHelperVariableContainer() {
        return $this->baseRenderingContext->getViewHelperVariableContainer();
    }
}
