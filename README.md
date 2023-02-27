# AlphaCruds (Laravel Package)

[![Tests](https://github.com/alpha-dev-team/AlphaCruds/workflows/Test/badge.svg)](https://github.com/alpha-dev-team/AlphaCruds/actions)
![GitHub](https://img.shields.io/github/license/alpha-dev-team/alphacruds)
[![Latest Stable Version](http://poser.pugx.org/alpha-dev-team/alphacruds/v)](https://packagist.org/packages/alpha-dev-team/alphacruds)
[![Latest Unstable Version](http://poser.pugx.org/alpha-dev-team/alphacruds/v/unstable)](https://github.com/alpha-dev-team/AlphaCruds)

[//]: # ([![PHP Version Require]&#40;http://poser.pugx.org/alpha-dev-team/alphacruds/require/php&#41;]&#40;https://packagist.org/packages/alpha-dev-team/alphacruds&#41;)

[//]: # ([![Dependents]&#40;http://poser.pugx.org/alpha-dev-team/alphacruds/dependents&#41;]&#40;https://packagist.org/packages/alpha-dev-team/alphacruds&#41;)

Package used to automate Admin panel CRUDS creation process in AlphaDev style

## Installation

Using the package manager [composer](https://getcomposer.org).

```bash
composer require alpha-dev-team/alphacruds
```
## Package Configuration

For package to work publishing assets and migrations is needed

### Publish Assets

```bash
php artisan vendor:publish --tag="alphacruds-assets"
```

### Publish Config (Optional)

```bash
php artisan vendor:publish --tag="alphacruds-config"
```

