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

class TemplateViewHelper extends AbstractViewHelper
{
    /**
     * @see AbstractViewHelper::isOutputEscapingEnabled()
     * @var bool
     */
    protected $escapeOutput = false;

    public function __construct()
    {
        $this->registerArgument('path', 'string', 'template path', TRUE);
    }

    public function render(): void
    {
        $this->viewHelperVariableContainer->add(TemplateViewHelper::class, 'template', $this->arguments['path']);
    }
}
