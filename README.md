# WSC API PHP Package

This is a simple package to connect the WSC API and fetch taxa information.

## Installation

Install the package via composer: `composer require dkdimitrov/wscapi`

## Simple use:
Make an instance passing your Api key. Then fetch the taxon passing as an argument its lsid (digits only, not the whole string)

```php
require 'vendor/autoload.php';

$wsc = new Wsc\Wsc\WscApi('yourApiKeyString');

$wsc->fetchSpecies('049542');

```

## For Laravel users:

Register the ServiceProvider in `config/app.php`

```php
        'providers' => [
		// [...]
                Wsc\Wsc\WscServiceProvider::class,
        ],
```

Then add your API Key to your .env file like this:
```php
        WSC_API_KEY=yourApiKeyString
```

## Usage
You can fetch family, genus or species information using one of the following methods:

```php
$wsc->fetchFamily('0037');

$wsc->fetchGenus('01824');

$wsc->fetchSpecies('049542');

```

If the taxon status is not VALID you can fetch the valid one by calling this method with the link as an argument:

```php
$species = $wsc->fetchSpecies('016759');

if($species->taxon->status != 'VALID'){
    $valid = $wsc->fetchValidTaxon($species->validTaxon->_href)
}

```

If you wish to fetch all updated taxa for the given period, use the method below and pass the starting date.
This will return all the result from the date you provide to now.

```php

$wsc->fetchUpdatedTaxa('YYYY-MM-DD');

```



