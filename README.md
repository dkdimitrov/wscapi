# WSC API PHP Package

This is a simple package to connect the WSC API and fetch taxa information.

## Installation

Install the package via composer: `composer require dkdimitrov/wscapi`

##Simple use:
Make an instance passing your Api key. Then fetch the taxon passing as an argument its lsid (digits only, not the whole string)

```php
require 'vendor/autoload.php';

$wsc = new Wsc\Wsc\WscApi('yourApiKeyString');

$wsc->fetchSpecies('049542');

```

