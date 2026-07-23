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
     * The stack of in-flight renders, innermost last. Each frame is the
     * template name and the search path directory it resolved from (null when
     * it came from an explicit map, or from a registry with no search paths).
     *
     * render() can nest, so parent() needs to know which template is currently
     * executing rather than which one was rendered first.
     *
     * @var list<array{0: string, 1: string|null}>
     *
     */
    private array $render_stack = [];

    /**
     *
     * Should parent() throw when it has nothing to resume into, instead of
     * returning ''?
     *
     */
    private bool $strict_parent = false;

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
     * Gets the search path directory a template name resolves from, or null
     * when the registry in use cannot say.
     *
     */
    protected function getResolvedPath(string $name): ?string
    {
        $registry = $this->template_registry;

        return $registry instanceof SearchPathInterface
            ? $registry->getResolvedPath($name)
            : null;
    }

    /**
     *
     * Pushes a render frame; call popRender() when the render finishes.
     *
     */
    protected function pushRender(string $name, ?string $path): void
    {
        $this->render_stack[] = [$name, $path];
    }

    /**
     *
     * Pops the innermost render frame.
     *
     */
    protected function popRender(): void
    {
        array_pop($this->render_stack);
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
     * Renders the template that the currently-executing one shadows.
     *
     * Ordinary resolution stops at the first hit, so a template earlier in the
     * search path replaces a later one wholesale -- to change one part you
     * copy the whole file. `parent()` resumes the search after the directory
     * the current template came from, letting the override render the thing it
     * overrode:
     *
     *     <?php $this->beginSection('extra') ?>
     *         ...
     *     <?php $this->endSection() ?>
     *     <?= $this->parent() ?>
     *
     * Returns `''` -- rather than throwing -- when there is nothing further to
     * render: the current template shadows nothing, it came from an explicit
     * map, or the registry has no search paths. Overriding a template that
     * turns out not to shadow anything is a normal state during development.
     *
     * That forgiveness is also a blind spot: a misconfigured search path
     * produces exactly the same `''`, so overrides silently stop composing and
     * nothing is raised. `setStrictParent(true)` turns these three cases into
     * Exception\ParentNotFound for development and CI.
     *
     * @param array<string, mixed> $vars Variables for the shadowed template.
     *
     * @throws Exception when called outside of a render.
     *
     * @throws Exception\ParentNotFound when there is nothing to resume into
     * and strict parent mode is on.
     *
     */
    protected function parent(array $vars = []): string
    {
        $frame = end($this->render_stack);

        if ($frame === false) {
            throw new Exception('parent() called outside of a template render');
        }

        [$name, $path] = $frame;
        $registry = $this->template_registry;

        if (! $registry instanceof SearchPathInterface) {
            return $this->noParent(
                $name,
                "the template registry has no search paths to resume along"
            );
        }

        if ($path === null) {
            return $this->noParent(
                $name,
                "it was registered in the map, which has no search path behind it"
            );
        }

        $next = $registry->getNext($name, $path);

        if ($next === null) {
            return $this->noParent(
                $name,
                "nothing after '{$path}' in the search paths has that name"
            );
        }

        $template = $next->template->bindTo($this, static::class) ?? $next->template;
        $this->pushRender($name, $next->path);

        try {
            return $this->captureTemplate($template, $vars);
        } finally {
            $this->popRender();
        }
    }

    /**
     *
     * Should parent() throw when it has nothing to resume into?
     *
     * Off by default, so that an override written before the template it
     * overrides exists is not an error. Turn it on in development and CI,
     * where a search path that resolves to nothing is far more likely to be a
     * misconfiguration than an intention -- otherwise it presents as missing
     * markup with nothing raised.
     *
     * This takes a bool rather than reading the environment: Aura.View has no
     * config layer and no dependencies, so deciding what "development" means
     * belongs to whatever wires the _View_ up.
     *
     */
    public function setStrictParent(bool $strict_parent): void
    {
        $this->strict_parent = $strict_parent;
    }

    /**
     *
     * Is strict parent mode on?
     *
     */
    public function isStrictParent(): bool
    {
        return $this->strict_parent;
    }

    /**
     *
     * Answers a parent() call that has nothing to resume into: '' normally,
     * an exception naming the template and the reason under strict mode.
     *
     * @throws Exception\ParentNotFound when strict parent mode is on.
     *
     */
    protected function noParent(string $name, string $reason): string
    {
        if (! $this->strict_parent) {
            return '';
        }

        throw new Exception\ParentNotFound(
            "parent() found no template to render for '{$name}': {$reason}."
        );
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
