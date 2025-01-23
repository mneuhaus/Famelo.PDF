<?php
declare(strict_types=1);

namespace Famelo\PDF\View;

/*
 * This file is part of the Famelo.PDF package.
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperVariableContainer;

/**
 * A standalone template view.
 */
class StandaloneView extends \Neos\FluidAdaptor\View\StandaloneView
{
    public function initializeObject(): void
    {
        parent::initializeObject();

        $this->request->setFormat('html');
    }

    public function getViewHelperVariableContainer(): ViewHelperVariableContainer
    {
        return $this->baseRenderingContext->getViewHelperVariableContainer();
    }
}
