# Aura.View

Provides an implementation of the [TemplateView](http://martinfowler.com/eaaCatalog/templateView.html) and [TwoStepView](http://martinfowler.com/eaaCatalog/twoStepView.html) patterns using PHP itself as the templating language. It supports both file-based and closure-based templates along with helpers and sections.

It is preceded by systems such as
[`Savant`](http://phpsavant.com),
[`Zend_View`](http://framework.zend.com/manual/en/zend.view.html), and
[`Solar_View`](http://solarphp.com/class/Solar_View).

## Foreword

### Installation

This library requires PHP 8.4 or later. It has **no runtime dependencies** -- not even an escaper, by design; see [Escaping Output](./docs/escaping.md).

It is installable and autoloadable via Composer as [aura/view](https://packagist.org/packages/aura/view).

Alternatively, [download a release](https://github.com/auraphp/Aura.View/releases) or clone this repository, then require or include its _autoload.php_ file.

Upgrading from 2.x? See [Upgrading from 2.x](./docs/upgrading.md).

### Quality

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/auraphp/Aura.View/badges/quality-score.png?b=7.x)](https://scrutinizer-ci.com/g/auraphp/Aura.View/)
[![codecov](https://codecov.io/gh/auraphp/Aura.View/branch/7.x/graph/badge.svg?token=UASDouLxyc)](https://codecov.io/gh/auraphp/Aura.View)
[![Continuous Integration](https://github.com/auraphp/Aura.View/actions/workflows/continuous-integration.yml/badge.svg?branch=7.x)](https://github.com/auraphp/Aura.View/actions/workflows/continuous-integration.yml)

To run the unit tests at the command line, issue `composer install` and then `vendor/bin/phpunit` at the package root. This requires [Composer](http://getcomposer.org/) to be available as `composer`.

Static analysis is via PHPStan; `composer phpstan` runs the suite's level, and `src/` is clean at level 9.

This library attempts to comply with [PSR-1][], [PSR-12][], and [PSR-4][].

[PSR-1]: https://www.php-fig.org/psr/psr-1/
[PSR-12]: https://www.php-fig.org/psr/psr-12/
[PSR-4]: https://www.php-fig.org/psr/psr-4/

### Community

To ask questions, provide feedback, or otherwise communicate with the Aura community, please join our [Google Group](http://groups.google.com/group/auraphp), or follow [@auraphp on X](https://x.com/auraphp).

## Documentation

This package is fully documented [here](./docs/index.md).
