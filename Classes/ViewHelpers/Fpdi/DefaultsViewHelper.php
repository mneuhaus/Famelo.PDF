<?php
declare(strict_types=1);

namespace Famelo\PDF\ViewHelpers\Fpdi;

/*
 * This file is part of the Famelo.PDF package.
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;

class DefaultsViewHelper extends AbstractViewHelper
{
    /**
     * @see AbstractViewHelper::isOutputEscapingEnabled()
     * @var bool
     */
    protected $escapeOutput = false;

    public function __construct()
    {
        $this->registerArgument('font', 'string', 'font name');
        $this->registerArgument('font-size', 'integer', 'font size');
        $this->registerArgument('font-weight', 'string', 'font weight');
        $this->registerArgument('color', 'string', 'font color');
    }

    public function render(): void
    {
        $this->viewHelperVariableContainer->add(DefaultsViewHelper::class, 'defaults', $this->arguments);
    }
}
