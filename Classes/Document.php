<?php
declare(strict_types=1);

namespace Famelo\PDF;

/*
 * This file is part of the Famelo.PDF package.
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Famelo\PDF\Generator\PdfGeneratorInterface;
use Famelo\PDF\View\StandaloneView;
use Famelo\PDF\ViewHelpers\FooterViewHelper;
use Famelo\PDF\ViewHelpers\HeaderViewHelper;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("prototype")
 */
class Document
{
    protected string $templatePath = 'resource://@package/Private/Documents/@document.html';

    protected string $layoutRootPath = 'resource://@package/Private/Layouts/';

    protected string $partialRootPath = 'resource://@package/Private/Partials/';

    protected string $document = 'Standard';

    protected ?string $package = null;

    /**
     * @Flow\Inject
     * @var StandaloneView
     */
    protected $view;

    protected string|array $format;

    protected array $options = [];

    /**
     * @Flow\InjectConfiguration(path="DefaultGenerator", package="Famelo.PDF")
     * @var string
     */
    protected $defaultGenerator;

    /**
     * @Flow\InjectConfiguration(path="DefaultGeneratorOptions", package="Famelo.PDF")
     * @var array
     */
    protected $defaultGeneratorOptions;

    protected ?PdfGeneratorInterface $generator = null;

    protected string $templateSource = '';

    public function __construct(string $document, string|array $format = 'A4')
    {
        $this->setDocument($document);
        $this->format = $format;
    }

    public function setDocument(string $document): Document
    {
        $parts = explode(':', $document);
        if (count($parts) > 1) {
            $this->package = $parts[0];
            $this->document = $parts[1];
        } else {
            $this->document = $document;
        }
        return $this;
    }

    public function setTemplateSource(string $templateSource): void
    {
        $this->templateSource = $templateSource;
    }

    public function setFormat(string|array $format): void
    {
        $this->format = $format;
    }

    /**
     * @return PdfGeneratorInterface
     */
    public function getGenerator(): PdfGeneratorInterface
    {
        if (!$this->generator instanceof PdfGeneratorInterface) {
            $this->generator = new $this->defaultGenerator($this->defaultGeneratorOptions, $this->view);
        }
        foreach ($this->options as $name => $value) {
            $this->generator->setOption($name, $value);
        }
        return $this->generator;
    }

    public function render(): string
    {
        if ($this->package === null) {
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            $class = $trace[0]['class'];
            preg_match('/([A-Za-z]*)\\\\([A-Za-z]*)/', $class, $match);
            $this->package = $match[1] . '.' . $match[2];
        }

        $replacements = [
            '@package' => $this->package,
            '@document' => $this->document
        ];
        if ($this->templateSource === '') {
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

        $this->view->getRequest()?->setControllerPackageKey($this->package);

        return $this->view->render();
    }

    public function setOptionsByViewHelper(PdfGeneratorInterface $generator): void
    {
        $viewHelperVariableContainer = $this->view->getViewHelperVariableContainer();
        if ($viewHelperVariableContainer->exists(HeaderViewHelper::class, 'header')) {
            $header = $viewHelperVariableContainer->get(HeaderViewHelper::class, 'header');
            $generator->setHeader($header);
        }
        $viewHelperVariableContainer = $this->view->getViewHelperVariableContainer();
        if ($viewHelperVariableContainer->exists(FooterViewHelper::class, 'footer')) {
            $footer = $viewHelperVariableContainer->get(FooterViewHelper::class, 'footer');
            $generator->setFooter($footer);
        }
    }

    public function setOption(string $name, mixed $value): void
    {
        $this->options[$name] = $value;
    }

    public function getStream(): string
    {
        $content = $this->render();
        $generator = $this->getGenerator();
        $this->setOptionsByViewHelper($generator);
        $generator->setFormat($this->format);
        return $generator->getPdfStream($content);
    }

    public function send(string $filename = null): void
    {
        $content = $this->render();
        $generator = $this->getGenerator();
        $this->setOptionsByViewHelper($generator);
        $generator->setFormat($this->format);
        $generator->sendPdf($content, $filename);
        exit();
    }

    public function download(string $filename = NULL): void
    {
        $content = $this->render();
        $generator = $this->getGenerator();

        $this->setOptionsByViewHelper($generator);
        $generator->setFormat($this->format);
        $generator->downloadPdf($content, $filename);
        exit();
    }

    public function save(string $filename): void
    {
        $content = $this->render();
        $generator = $this->getGenerator();
        $this->setOptionsByViewHelper($generator);
        $generator->setFormat($this->format);
        $generator->savePdf($content, $filename);
    }

    public function assign(string $key, mixed $value): Document
    {
        $this->view->assign($key, $value);
        return $this;
    }

    public function assignMultiple(array $values): Document
    {
        foreach ($values as $key => $value) {
            $this->assign($key, $value);
        }
        return $this;
    }
}
