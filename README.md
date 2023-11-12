# Phpinfo

**Phpinfo** allows you to easily retrieve and process information from the `phpinfo()` function. 
It provides a structured and organized representation of the information, making it more accessible and convenient for developers.

## Features

- Retrieve and parse PHP configuration information.
- Organize PHP `phpinfo()` output into structured sections.
- Filter and format relevant data for easy consumption.

## Installation

To use **Phpinfo** in your project, you can install it via Composer:

```shell
composer require shepherdmat/phpinfo
```

## Usage

Here's an example of how to use **Phpinfo** to retrieve and structure PHP information:

```php
<?php
require 'vendor/autoload.php';
use Shepherdmat\Phpinfo\Phpinfo;

// Retrieve and parse all PHP information
$info = Phpinfo::build();

var_dump($info);

// Access information by section
$generalInfo = Phpinfo::buildSection(Phpinfo::INFO_GENERAL);
var_dump($generalInfo);
```

## License

This bundle is under the MIT license.  
For the whole copyright, see the [LICENSE](LICENSE) file distributed with this source code.


