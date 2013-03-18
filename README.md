Magick
======

## What it is

An ultra lightweight ImageMagick wrapper for PHP offering some of the more common uses of the `convert` command, like cropping and resizing.

## Setup

Most web hosts make the primary ImageMagick command `convert` callable as is. However, if this is different in your environment, simply include the full path in your constructor. For example:

```php
<?php

$magick = new Magick('/opt/ImageMagick/bin/convert');
```

## Example

The following example shows how you can use `Magick` to create five different image sizes, with varying crop ratios, from one original image file.

```php
<?php

	// Create Magick instance
	$magick = new Magick();

	// Set the path to the file you want to modify
	$magick->setFilePath('original.jpg');

	// Create an extra large version
	// Restricted to a maximum of 1200px high
	$magick->setHeight(1200)->convert('xlarge.jpg');

	// Create a large version
	// Set a specific cropping ratio
	// Set height to null (to clear the 1200px restriction)
	// Add new width restriction of 1175px wide
	$magick->setCropByRatio(1175/660)->setHeight(null)->setWidth(1175)->convert('large.jpg');

	// Create a medium version
	// Adjust the crop ratio
	// Set a smaller width restriction of 750px
	$magick->setCropByRatio(750/500)->setWidth(750)->convert('medium.jpg');

	// Create a small version
	// Set a smaller width restriction of 250px
	$magick->setWidth(250)->convert('small.jpg');

	// Create an extra small version
	// Set the crop ratio as a square
	// Set a small width restriction of 75px
	$magick->setCropByRatio(1)->setWidth(75)->convert('xsmall.jpg');
```