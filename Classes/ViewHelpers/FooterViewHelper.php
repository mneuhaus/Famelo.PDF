<?php
declare(strict_types=1);

namespace Famelo\PDF\ViewHelpers;

/*
 * This file is part of the Famelo.PDF package.
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;

class FooterViewHelper extends AbstractViewHelper
{
    /**
     * @see AbstractViewHelper::isOutputEscapingEnabled()
     * @var bool
     */
    protected $escapeOutput = false;

    public function render(): void
    {
        $content = $this->renderChildren();
        $this->viewHelperVariableContainer->add(FooterViewHelper::class, 'footer', $content);
    }
}
