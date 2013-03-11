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

```php
<?php

	// Create Magick instance
	$magick = new Magick();

	// Set the path to the file you want to modify
	$magick->set_file_path($path);

	// Create an extra large version
	// Restricted to a maximum of 1200px high
	$magick->set_height(1200)->convert('xlarge.jpg');

	// Create a large version
	// Set a specific cropping ratio
	// Set height to null (to clear the 1200px restriction)
	// Add new width restriction of 1175px wide
	$magick->set_crop_by_ratio(1175/660)->set_height(null)->set_width(1175)->convert('large.jpg');

	// Create a medium version
	// Adjust the crop ratio
	// Set a smaller width restriction of 750px
	$magick->set_crop_by_ratio(750/500)->set_width(750)->convert('medium.jpg');

	// Create a small version
	// Set a smaller width restriction of 250px
	$magick->set_width(250)->convert('small.jpg');

	// Create an extra small version
	// Set the crop ratio as a square
	// Set a small width restriction of 75px
	$magick->set_crop_by_ratio(1)->set_width(75)->convert('xsmall.jpg');
```