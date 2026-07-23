<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/MIT-license.php MIT
 *
 */
declare(strict_types=1);

namespace Aura\View;

/**
 *
 * An abstract TemplateView/TwoStepView pattern implementation. We use an
 * abstract so that the extended "real" View class does not have access to the
 * private support properties herein.
 *
 * @package Aura.View
 *
 */
abstract class AbstractView
{
    /**
     *
     * The stack of section names currently being captured.
     *
     * @var list<string>
     *
     */
    private array $capture = [];

    /**
     *
     * The content to be placed into the layout.
     *
     */
    private string $content = '';

    /**
     *
     * Data assigned to the template.
     *
     */
    private object $data;

    /**
     *
     * An arbitrary object for helpers.
     *
     * Deliberately typed `object` and not HelperRegistryInterface: the view
     * reaches its helper manager only through `__call()`, so any object with a
     * `__call()` method will do -- including Aura.Html's _HelperLocator_,
     * which cannot implement an Aura.View interface without depending on
     * Aura.View.
     *
     */
    private ?object $helpers;

    /**
     *
     * The name of the layout template in the layout template registry.
     *
     */
    private ?string $layout = null;

    /**
     *
     * The layout template registry.
     *
     */
    private TemplateRegistryInterface $layout_registry;

    /**
     *
     * A collection point for section content.
     *
     * @var array<string, string>
     *
     */
    private array $section = [];

    /**
     *
     * The template registry currently in use.
     *
     */
    private TemplateRegistryInterface $template_registry;

    /**
     *
     * The name of the view template in the view template registry.
     *
     */
    private ?string $view = null;

    /**
     *
     * The view template registry.
     *
     */
    private TemplateRegistryInterface $view_registry;

    /**
     *
     * Constructor.
     *
     * @param TemplateRegistryInterface $view_registry A registry for view
     * templates.
     *
     * @param TemplateRegistryInterface $layout_registry A registry for layout
     * templates.
     *
     * @param object|null $helpers An arbitrary helper object.
     *
     */
    public function __construct(
        TemplateRegistryInterface $view_registry,
        TemplateRegistryInterface $layout_registry,
        ?object $helpers = null
    ) {
        $this->data = (object) [];
        $this->view_registry = $view_registry;
        $this->layout_registry = $layout_registry;
        $this->helpers = $helpers;
    }

    /**
     *
     * Magic read access to template variables.
     *
     * @param string $key The template variable name.
     *
     */
    public function __get(string $key): mixed
    {
        return $this->data->$key;
    }

    /**
     *
     * Magic write access to template variables.
     *
     * @param string $key The template variable name.
     *
     * @param mixed $val The template variable value.
     *
     */
    public function __set(string $key, mixed $val): void
    {
        $this->data->$key = $val;
    }

    /**
     *
     * Magic isset() for template variables.
     *
     * @param string $key The template variable name.
     *
     */
    public function __isset(string $key): bool
    {
        return isset($this->data->$key);
    }

    /**
     *
     * Magic unset() for template variables.
     *
     * @param string $key The template variable name.
     *
     */
    public function __unset(string $key): void
    {
        unset($this->data->$key);
    }

    /**
     *
     * Magic call to expose helper object methods as template methods.
     *
     * @param string $name The helper object method name.
     *
     * @param array<int, mixed> $args The arguments to pass to the helper.
     *
     */
    public function __call(string $name, array $args): mixed
    {
        $helper = [$this->helpers, $name];

        if (! is_callable($helper)) {
            throw new Exception\HelperNotFound($name);
        }

        return $helper(...$args);
    }

    /**
     *
     * Sets the data object.
     *
     * @param array<string, mixed>|object $data An array or object where the
     * keys or properties are variable names, and the corresponding values are
     * the variable values. (This param is cast to an object.)
     *
     */
    public function setData(array|object $data): void
    {
        $this->data = (object) $data;
    }

    /**
     *
     * Adds to the view data.
     *
     * @param iterable<string, mixed> $data An array or object where the keys
     * or properties are variable names, and the corresponding values are the
     * variable values; these are looped over and added to the view data.
     *
     */
    public function addData(iterable $data): void
    {
        foreach ($data as $key => $val) {
            $this->data->$key = $val;
        }
    }

    /**
     *
     * Gets the data object.
     *
     */
    public function getData(): object
    {
        return $this->data;
    }

    /**
     *
     * Gets the arbitrary object for helpers.
     *
     */
    public function getHelpers(): ?object
    {
        return $this->helpers;
    }

    /**
     *
     * Sets the name of the layout template to render.
     *
     * @param string|null $layout The name of the layout template to render;
     * null or an empty string means "no layout".
     *
     */
    public function setLayout(?string $layout): void
    {
        $this->layout = $layout;
    }

    /**
     *
     * Gets the name of the layout template to be rendered.
     *
     */
    public function getLayout(): ?string
    {
        return $this->layout;
    }

    /**
     *
     * Gets the layout template registry.
     *
     */
    public function getLayoutRegistry(): TemplateRegistryInterface
    {
        return $this->layout_registry;
    }

    /**
     *
     * Sets the name of the view template to render.
     *
     * @param string|null $view The name of the view template to render.
     *
     */
    public function setView(?string $view): void
    {
        $this->view = $view;
    }

    /**
     *
     * Gets the name of the view template to be rendered.
     *
     */
    public function getView(): ?string
    {
        return $this->view;
    }

    /**
     *
     * Gets the view template registry.
     *
     */
    public function getViewRegistry(): TemplateRegistryInterface
    {
        return $this->view_registry;
    }

    /**
     *
     * Sets the template registry currently in use.
     *
     */
    protected function setTemplateRegistry(
        TemplateRegistryInterface $template_registry
    ): void {
        $this->template_registry = $template_registry;
    }

    /**
     *
     * Gets a template from the registry and binds $this to it.
     *
     * A static closure cannot be bound; it is returned as-is.
     *
     */
    protected function getTemplate(string $name): \Closure
    {
        $tmpl = $this->template_registry->get($name);
        return $tmpl->bindTo($this, static::class) ?? $tmpl;
    }

    /**
     *
     * Invokes a template and captures its output.
     *
     * Output is discarded rather than flushed if the template throws, so a
     * failed render does not leak half-rendered output into the enclosing one.
     *
     * A template can leave more than the render buffer open: beginSection()
     * opens its own buffer and pushes a capture frame, so a template that
     * throws mid-section has two buffers and a frame in flight. Unwinding only
     * the innermost buffer would leave the render buffer orphaned -- later
     * output would disappear into it -- and leave a stale frame for a later
     * endSection() to consume, silently capturing the wrong content under the
     * wrong name. Restore both to the depth they had on entry.
     *
     * @param array<string, mixed> $vars Variables for the template.
     *
     */
    protected function captureTemplate(\Closure $template, array $vars): string
    {
        $buffer_level = ob_get_level();
        $capture_level = count($this->capture);

        ob_start();

        try {
            $template->__invoke($vars);
        } catch (\Throwable $e) {
            while (ob_get_level() > $buffer_level) {
                ob_end_clean();
            }
            array_splice($this->capture, $capture_level);
            throw $e;
        }

        return (string) ob_get_clean();
    }

    /**
     *
     * Sets the content to be used in the layout.
     *
     */
    protected function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     *
     * Gets the content to be used in the layout.
     *
     */
    protected function getContent(): string
    {
        return $this->content;
    }

    /**
     *
     * Is a particular named section available?
     *
     */
    protected function hasSection(string $name): bool
    {
        return isset($this->section[$name]);
    }

    /**
     *
     * Sets the body of a named section directly, as opposed to buffering and
     * capturing output.
     *
     */
    protected function setSection(string $name, string $body): void
    {
        $this->section[$name] = $body;
    }

    /**
     *
     * Gets the body of a named section.
     *
     */
    protected function getSection(string $name): string
    {
        return $this->section[$name];
    }

    /**
     *
     * Begins output buffering for a named section.
     *
     */
    protected function beginSection(string $name): void
    {
        $this->capture[] = $name;
        ob_start();
    }

    /**
     *
     * Ends buffering and retains output for the most-recent section.
     *
     */
    protected function endSection(): void
    {
        $body = ob_get_clean();
        $name = array_pop($this->capture);

        if ($name === null) {
            throw new Exception('endSection() without a matching beginSection()');
        }

        $this->setSection($name, (string) $body);
    }
}
