# CHANGELOG

## 7.0.0-beta1

The first release of the `7.x` line, and a beta: the API described below is
complete and tested, but may still be adjusted before `7.0.0` final. The
version jumps 2.x -> 7.x; there is no 3.x, 4.x, 5.x, or 6.x.

**The license is now MIT**, changed from BSD-2-Clause. MIT is more permissive
than BSD-2-Clause, so this relaxes rather than restricts what consumers may
do. The `LICENSE` file, the `composer.json` `license` field, and all 12
`src/**.php` headers are updated; copyright is `2011-2026, Aura for PHP`.

Aura.View still has **no runtime dependencies**. It ships no escaper, by
design -- the package is media-type agnostic, so escaping stays explicit and
yours to choose. See the README's *Escaping Output* section.

### Breaking

- [BRK] **PHP `^8.4` is now required**, up from `>=5.4.0`.

- [BRK] **Native parameter and return types throughout, under
  `declare(strict_types=1)`.** This breaks **subclasses**, not callers: a
  userland class overriding e.g. `setData($data)` must now declare a matching
  signature or it will fatal on load. Direct callers are unaffected.

- [BRK] **Setters return `void`, they are not fluent.** Aura.View is a service,
  not a specification builder -- fluency buys nothing when there is no spec to
  assemble. Setter calls cannot be chained.
  Note that adding *any* return type is the break here; `void` versus `static`
  only decides which break is spent.

- [BRK] **`TemplateRegistry::get()` returns `\Closure`.** Non-closure callables
  given to `set()` are now normalised to closures so the _View_ can bind
  `$this` to them. Previously an array callable was stored as-is and then
  failed in `bindTo()`, so this also fixes a latent bug.

- [BRK] **The registry getters return `TemplateRegistryInterface`**, not the
  concrete _TemplateRegistry_. Code that contributes search paths should type
  against the new _SearchPathInterface_.

- [BRK] **Invoking a _View_ with no view template set returns `''`** (wrapped in
  the layout, if one is set) instead of raising _TemplateNotFound_ on a null
  template name. The old behaviour is not expressible under strict types.

- [BRK] **Calling an unresolvable helper throws
  `Aura\View\Exception\HelperNotFound`** instead of a raw PHP `Error`.

- [BRK] **`config/Common.php` and the `extra.aura` block are removed**, along
  with the `Aura\View\_Config\` PSR-4 entry and the `aura/di` dev dependency.
  The config extended `Aura\Di\Config`, a class that no longer exists in
  Aura.Di 5.x, and encoded the dead v2 kernel discovery convention. Wiring
  moves to the consuming framework's module class.

- [BRK] **`ViewFactory::newInstance()` takes a _ViewSpec_ per registry** instead
  of four flat array arguments. `$helpers` remains the first parameter, so
  `newInstance()` and `newInstance($helpers)` are unaffected; only the longer
  positional form changes, and it fails with a `\TypeError` naming the
  parameter.

      // 2.x
      $view_factory->newInstance($helpers, $view_map, $view_paths, $layout_map, $layout_paths);

      // 7.x
      $view_factory->newInstance(
          $helpers,
          new \Aura\View\ViewSpec(map: $view_map, paths: $view_paths),
          new \Aura\View\ViewSpec(map: $layout_map, paths: $layout_paths),
      );

  The four settings that describe a registry -- `map`, `paths`, `namespaces`,
  `extension` -- belong together; flat arguments interleaved the view
  registry's with the layout registry's, and adding the two missing ones would
  have made a nine-parameter method.

- [BRK] `endSection()` without a matching `beginSection()` now throws
  `Aura\View\Exception` instead of silently capturing under a null key.

- [BRK] **`HelperRegistry::set()` throws
  `Aura\View\Exception\HelperAlreadyRegistered` when the name is already
  taken.** Previously registration was silent last-one-wins: two packages
  both registering `url` meant whichever ran later replaced the other, with no
  warning and no way to tell it had happened. Pass `override: true` to replace
  deliberately.

      $helpers->set('url', $mine);                  // throws if taken
      $helpers->set('url', $mine, override: true);  // replaces

  `override: true` is not an error when the name is unregistered -- it means
  "I accept replacing whatever is there", not "something must be there" -- so
  an application can assert its own helper without first probing for a module's.
  Use `has()` to branch instead. Registering the identical callable twice also
  throws; two packages sharing an implementation still have to say which owns
  the name. `HelperRegistryInterface::set()` gains the same third parameter,
  which affects anyone who has already implemented that interface.

- [BRK] **A template name with more than one `::` throws
  `Aura\View\Exception\InvalidTemplateName`** instead of a bare
  `\InvalidArgumentException`, so every exception this package raises descends
  from `Aura\View\Exception`. Code catching `\InvalidArgumentException` around
  a name lookup must catch the new class instead.

### Deprecated

- [DEP] `Aura\View\Exception\InvalidHelpersObject` is never thrown. The
  helpers parameter is typed `?object`, so PHP's own `\TypeError` rejects a
  non-object first. The class is retained so existing `catch` blocks still
  resolve, and will be removed in 8.0.

### Added

- [ADD] **`TemplateRegistryInterface`** (`set()`, `has()`, `get()`) and
  **`SearchPathInterface`** (`getPaths()`, `setPaths()`, `prependPath()`,
  `appendPath()`, `setNamespaces()`, `hasNamespace()`,
  `getNamespaces()`, `getNamespacePaths()`,
  `setTemplateFileExtension()`). Previously `setTemplateRegistry()` type-hinted
  the concrete class, so a framework could not substitute a module-aware
  registry without extending it. Path management is kept out of
  `TemplateRegistryInterface` on purpose: a registry backed by a precompiled
  name-to-file map has no paths to manage. _TemplateRegistry_ implements both.

- [ADD] **`HelperRegistryInterface`** (`set()`, `has()`, `get()`), implemented
  by _HelperRegistry_.

  Note that the _View_ deliberately does **not** type-hint against it. The
  helper manager parameter on `ViewFactory::newInstance()` and
  `AbstractView::__construct()` is typed `?object`, because a _View_ reaches
  its helpers only through `__call()`. Typing it to the interface would exclude
  valid managers -- including Aura.Html's _HelperLocator_, which cannot
  implement an Aura.View interface without depending on Aura.View, the wrong
  direction -- while using none of that interface's methods. **The documented
  Aura.Html wiring therefore continues to work unchanged, with no adapter.**

- [ADD] **`getNamespaces()` and `getNamespacePaths()` on
  _SearchPathInterface_** (and _TemplateRegistry_). `setNamespaces()` and
  `hasNamespace()` existed with no getter, while `getPaths()` did exist -- so
  with several modules contributing paths under one namespace, "which
  directory did this template come from?" was unanswerable without reflection.
  `getNamespacePaths()` returns `[]` for an unregistered namespace rather than
  throwing; asking about a namespace that was never registered is a normal
  question, and `hasNamespace()` is there when you want to distinguish
  "unregistered" from "registered but empty".

- [ADD] **`Aura\View\Exception\InvalidTemplateName`**, thrown by name parsing.
  See *Breaking*.

- [ADD] **`Aura\View\Exception\HelperAlreadyRegistered`**, thrown by
  `HelperRegistry::set()` on a collision. See *Breaking*.

- [ADD] **`parent()` -- extend a shadowed template instead of replacing it.**
  Search paths are first-hit-wins, so a template in an earlier directory
  shadows a later one and the shadowed version was *unreachable*: changing one
  part of a package's template meant copying the whole file. `parent()`
  renders the shadowed template by resuming the search after the directory the
  current template came from, so only the difference lives in the application:

      <?php $this->beginSection('extra') ?>
          <p>Something only this application wants.</p>
      <?php $this->endSection() ?>
      <?= $this->parent() ?>

  Chains are any depth, variables can be passed down with
  `parent(['key' => $val])`, and namespaced names walk their own namespace's
  paths. `parent()` returns `''` rather than throwing when there is nothing
  further to render -- the template shadows nothing, came from the explicit
  map, or the registry has no search paths -- because overriding a template
  that turns out to shadow nothing is a normal state during development.
  Calling it outside a render throws `Aura\View\Exception`.

- [ADD] **`setStrictParent()` / `isStrictParent()`, and
  `Aura\View\Exception\ParentNotFound`.** `parent()` returning `''` when it
  finds nothing is right for production but hides misconfiguration: a typo in a
  search path, paths registered in the wrong order, or a registry with no
  paths at all all produce the same `''`, so overrides silently stop composing
  and the only symptom is missing markup. With strict parent mode on, those
  three cases throw instead, naming the template and the reason:

      parent() found no template to render for 'read': nothing after
      '/app/templates' in the search paths has that name.

  Off by default. It takes a bool rather than reading the environment itself --
  Aura.View has no config layer and no dependencies, so what counts as
  "development" belongs to whatever wires the _View_ up.

- [ADD] **`SearchPathInterface::getNext()` and `getResolvedPath()`**, plus the
  readonly **`ResolvedTemplate`** (`name`, `template`, `path`) that `getNext()`
  returns. `getResolvedPath()` reports which directory satisfied a name -- the
  answer to "which package's template won?" -- and returns null for a mapped
  or unresolvable name. `getNext()` is the resumption primitive behind
  `parent()`. It returns a _ResolvedTemplate_ rather than a bare `\Closure`
  because walking a chain past the first step needs the path the parent itself
  came from, which a closure does not carry.

  _TemplateRegistry_ now records the directory each found template came from
  alongside the template itself; this cache is cleared with the existing one
  whenever paths change.

- [ADD] **A render stack on _AbstractView_.** `render()` pushes the template
  name and its resolved path for the duration of the render and pops it in a
  `finally`, so nested renders resolve `parent()` against the template actually
  executing, and a template that throws does not leave a frame behind.
  `parent()` shares the existing `captureTemplate()` for its own buffering.
  Subclasses that override `render()` wholesale will need to push and pop
  themselves for `parent()` to work inside them.

- [ADD] **`ViewSpec`**, a readonly value object describing one template
  registry: `map`, `paths`, `namespaces`, and `extension`. Its `newRegistry()`
  method builds the corresponding _TemplateRegistry_, so it is useful when
  assembling a _View_ without the factory.

- [FIX] **Template namespaces and the template file extension are reachable
  through _ViewFactory_.** Previously the factory built each _TemplateRegistry_
  with two of its three constructor arguments -- dropping `$namespaces`
  entirely -- and never called `setTemplateFileExtension()`. Both were
  therefore unusable through the factory even though _TemplateRegistry_ has
  supported namespaces since 2.4.0.

- [ADD] `aura/html` is listed under `suggest` and `require-dev`, and the
  integration is now covered by tests. It remains optional; Aura.View does not
  require it.

### Fixed

- [FIX] `View::render()` no longer leaks an output buffer when a template
  throws.

- [FIX] **A template that throws *mid-section* no longer leaves an output
  buffer and a section frame behind.** `render()` unwound exactly one buffer,
  which is enough only when the render buffer is the sole one open;
  `beginSection()` opens a second buffer and pushes a capture frame, so a
  template throwing between `beginSection()` and `endSection()` left the render
  buffer orphaned and a stale frame on the stack. Both leaked into whatever
  rendered next: output vanished into the buffer nobody closed, and a later
  unmatched `endSection()` consumed the stale frame instead of throwing,
  silently capturing the wrong content under the wrong name. The buffering
  moves to `AbstractView::captureTemplate()`, which records the buffer and
  capture depths on entry and restores both on failure.

- [FIX] `$capture` and `$section` initialise to `[]` rather than null, so
  appending never relies on autovivification. (Appending to null still works
  silently; it is autovivification from `false` that PHP 8.1 deprecated. The
  explicit `[]` avoids depending on either.)

- [FIX] **`setPaths()` and `setNamespaces()` strip trailing directory
  separators**, as `prependPath()` and `appendPath()` always have. Previously
  the same directory had two spellings inside the registry depending on which
  setter registered it, so `getPaths()` echoed back whatever it was given.
  Beyond tidiness this broke `parent()`: the path recorded for a found
  template is handed straight back to `getNext()` to resume the search, and a
  directory stored one way but compared another made the shadowed template
  unreachable -- `parent()` returned `''` instead of rendering it, going quiet
  rather than failing loudly. `getPaths()` and `getNamespaces()` now return
  normalised paths.

- [FIX] `TemplateRegistry::setTemplateFileExtension()` now clears the cache of
  already-resolved templates, as every other path-mutating method already did. Changing
  the extension after a name had been resolved kept returning the stale hit
  under the old extension.

- [FIX] Stray double semicolon in `TemplateRegistry::isNamespaced()`.

- [FIX] 19 docblock defects found by PHPStan (15 bogus `@return null` tags, 5
  global classes missing a leading backslash, 1 `@param` naming a nonexistent
  parameter).

### Support

- [CHG] PHPUnit ^12; `yoast/phpunit-polyfills` dropped -- it exists to span
  PHPUnit 4-9 assertion signatures and is dead weight on an 8.4-only major.
  `phpunit.php` and the dead `ContainerTest` are removed.

- [CHG] PHPStan ^2 added; `src/` is clean at level 9. `composer test`,
  `composer test-coverage`, and `composer phpstan` scripts added.

- [CHG] CI matrix reduced to PHP 8.4 and 8.5.

## 2.4.0

* Added namespace support to TemplateRegistry by @jakejohns https://github.com/auraphp/Aura.View/pull/83.
* Updated CI to support php versions from 5.4 to 8.1 by @harikt

## 2.3.0

* Added ability to set map and path in ViewFactory.  https://github.com/auraphp/Aura.View/pull/73
* Removed CHANGES.md file.
* Added CHANGELOG.md

## 2.2.1

* Added ability to customize template extension. Thank you Josh Butts.

* Added documentation how to customize template extension.

## 2.2.0

* Added ability to customize template extension. Thank you Josh Butts.

* Added documentation how to customize template extension.

## 2.1.1

This release modifies the testing structure and updates other support files.


## 2.1.0

This release has one feature addition, in addition to doucmentation and support file updates.

Per @harikt, we have brought back the "finder" functionality from Aura.View v1. This means the TemplateRegistry can now search through directory paths to find templates implicitly, in addition to the existing explicitly registered templates. (Explicit mappings take precedence over search paths.)

Thanks also to @iansltx for his HHVM-related testing work.

## 2.0.1

- TST: Update testing structure, and disable  auto-resolve for container tests

- DOC: Update README and docblocks

- FIX: TemplateRegistry map now passes the array via set to make the file
  inside a closure

## 2.0.0

First stable 2.0 release.

- DOC: Update docblocks and README.

- CHG: View::render() now takes a second param, $data, for an array of vars to be extract()ed into the template scope. Closure-based templates will need to extract this on their own. (The previous technique of placing partial vars in the main template object still works.)

## 2.0.0-beta2

- [BRK] Stop using a "content variable" and begin using setContent()/getContent() instead.  In your layouts, replace `echo $this->content_var_name` with `echo $this->getContent()`. (This also removes the `setContentVar()` and `getContentVar()` methods.)

- [ADD] Add support for sections per strong desire from @harikt, which fixes #46.  The new methods are `setSection()`, `hasSection()`, and `getSection()`, along with `beginSection()` and `endSection()`.

## 2.0.0-beta1

First 2.0.0-beta1 release.

## 1.2.2

Hygiene release.

- Merge pull request #52 from harikt/v2config; adds configuration for v2 framework

- Merge pull request #50 from koriym/fix-typos; fixes some doc typos.

- Merge pull request #45 from harikt/label-issue; fix label issue pointed out in groups by guillaume ferrand and poiting to wrong docs

- Merge pull request #44 from jelofson/helpers; various helper updates:

    - Changed docblock to correct type for 'checked'

    - Fixed the ordering of the styles helper to match that of the scripts helper

    - Updated some tests

    - Added fluency to some helpers

    - Added some documentation for helpers

    - Fluent method in the Links helper and updated test.

## 1.2.1

- [FIX] TwoStepView::getView() now optionally returns the first view when
  there is no format specified; this stops exceptions from being raised when
  the client passes no Accept header and there are multiple view formats
  available.

## 1.2.0

- [TST] Add PHP 5.5 to the Travis build.

- [CHG] Escaper\Object now recursively escapes arrays instead of converting to
  ArrayObject and wrapping in an escaper

- [ADD] TwoStepView::getTemplate() to get the template out of the view

- [NEW] Helper\Form\Checkboxes

## 1.1.2

(updated to include the InstanceTest)

- [FIX] Correct the instance.php script and its associated scripts, and add
  an InstanceTest for it. Thanks, HariKT, for reporting this.

## 1.1.1

- [FIX] Correct the instance.php script and its associated scripts, and add
  an InstanceTest for it. Thanks, HariKT, for reporting this.

## 1.1.0

- [NEW] Form helpers: field, input, radios, repeat, select, and textarea.

- [NEW] List helpers: ul and ol.

- [NEW] Generic tag helper.

- [ADD] AbstractHelper methods indent(), setIndentLevel(), and void().

- [ADD] Method addCond() to the styles helper, to add a conditional style.

- [CHG] Disallow easy changing of quotes and charset via constructor; always
  go with ENT_QUOTES and UTF-8

- [CHG] Registry entries *must* be wrapped in a callable from now on

## 1.0.0

- [FIX] In scripts/instance.php, pass an EscaperFactory.

- [FIX] #15: https://github.com/auraphp/Aura.View/issues/15

- [CHG] TemplateFinder now uses is_readable() instead of SplFileObject, to
  help with testing using streams.

- [CHG] Renamed protected method TemplateFinder::fileExists() to exists(),
  because streams may not be files proper.
