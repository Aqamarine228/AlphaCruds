# AlphaCruds (Laravel Package)

[![Tests](https://github.com/Aqamarine228/AlphaCruds/workflows/Test/badge.svg)](https://github.com/Aqamarine228/AlphaCruds/actions)
![GitHub](https://img.shields.io/github/license/aqamarine228/alphacruds)
[![Latest Stable Version](https://poser.pugx.org/aqamarine/alphacruds/v)](https://packagist.org/packages/aqamarine/alphacruds)
[![Latest Unstable Version](https://poser.pugx.org/aqamarine/alphacruds/v/unstable)](https://github.com/Aqamarine228/AlphaCruds)

[//]: # ([![PHP Version Require]&#40;http://poser.pugx.org/alpha-dev-team/alphacruds/require/php&#41;]&#40;https://packagist.org/packages/alpha-dev-team/alphacruds&#41;)

[//]: # ([![Dependents]&#40;http://poser.pugx.org/alpha-dev-team/alphacruds/dependents&#41;]&#40;https://packagist.org/packages/alpha-dev-team/alphacruds&#41;)

Package used to automate Admin panel CRUDS creation process in AlphaDev style

## Installation

Using the package manager [composer](https://getcomposer.org).

```bash
composer require aqamarine/alphacruds
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

