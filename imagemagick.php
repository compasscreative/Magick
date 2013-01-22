<?php
/**
 * An ultra lightweight ImageMagick wrapper for PHP.
 *
 * @package  ImageMagick
 * @version  1.0
 * @author   Jonathan Reinink <jonathan@reininks.com>
 * @link     https://github.com/reinink/ImageMagick
 */

namespace Reinink;

class ImageMagick
{
	/**
	 * The convert command path.
	 *
	 * @var string
	 */
	private $convert_path;

	/**
	 * The source image path.
	 *
	 * @var string
	 */
	private $file_path;

	/**
	 * The convert crop command.
	 *
	 * @var int,string
	 */
	private $crop;

	/**
	 * The convert width in pixels.
	 *
	 * @var int
	 */
	private $width;

	/**
	 * The convert height in pixels.
	 *
	 * @var int
	 */
	private $height;

	/**
	 * The convert quality.
	 *
	 * @var int
	 */
	private $quality = 90;

	/**
	 * Create a new ImageMagick instance.
	 *
	 * @param	string	$convert_path
	 * @param	string	$file_path
	 * @return	void
	 */
	public function __construct($convert_path = null, $file_path = null)
	{
		$this->convert_path = $convert_path;
		$this->file_path = $file_path;
	}

	/**
	 * Set the convert command path.
	 *
	 * @param	string	$convert_path
	 * @return	ImageMagick
	 */
	public function set_convert_path($convert_path)
	{
		$this->convert_path = $convert_path;
		return $this;
	}

	/**
	 * Set the source image path.
	 *
	 * @param	string	$file_path
	 * @return	ImageMagick
	 */
	public function set_file_path($file_path)
	{
		$this->file_path = $file_path;
		return $this;
	}

	/**
	 * Set the convert cropping by ratio with optional gravity parameter.
	 *
	 * <code>
	 *		$image->set_crop(16/9);
	 * </code>
	 *
	 * @param	int		$width
	 * @param	string	$gravity
	 * @return	ImageMagick
	 */
	public function set_crop_by_ratio($ratio, $gravity = 'center')
	{
		// Get original image size
		$size = getimagesize($this->file_path);

		// Set dimensions
		$width = $size[0];
		$height = $width / $ratio;

		// Update dimensions if out of document bounds
		if ($height > $size[1])
		{
			$width = $size[1] * $ratio;
			$height = $size[1];
		}

		// Build crop command
		$this->crop = ' -gravity ' . $gravity . ' -extent ' . $width . 'x' . $height . ' ';

		return $this;
	}

	/**
	 * Set the convert cropping by coordinates.
	 *
	 * @param	int		$width
	 * @param	int		$height
	 * @param	int		$x_pos
	 * @param	int		$y_pos
	 * @return	ImageMagick
	 */
	public function set_crop_by_coordinates($width, $height, $x_pos, $y_pos)
	{
		$this->crop = ' -crop ' . $width . 'x' . $height . '+' . $x_pos . '+' . $y_pos . '';
		return $this;
	}

	/**
	 * Set the convert width in pixels.
	 *
	 * @param	int		$width
	 * @return	ImageMagick
	 */
	public function set_width($width)
	{
		$this->width = $width;
		return $this;
	}

	/**
	 * Set the convert height in pixels.
	 *
	 * @param	int		$height
	 * @return	ImageMagick
	 */
	public function set_height($height)
	{
		$this->height = $height;
		return $this;
	}

	/**
	 * Set the convert quality.
	 *
	 * @param	int		$quality
	 * @return	ImageMagick
	 */
	public function set_quality($quality)
	{
		$this->quality = $quality;
		return $this;
	}

	/**
	 * Generate a new image by executing the convert command.
	 *
	 * @param	string	$dest_path
	 * @return	bool
	 */
	public function convert($dest_path)
	{
		// Has convert path been set?
		if (is_null($this->convert_path))
		{
			return false;
		}

		// Does the file exist?
		if (!is_file($this->file_path))
		{
			return false;
		}

		// Run command
		exec($this->command($dest_path));

		// Return success
		return is_file($dest_path);
	}

	/**
	 * Build the convert command using the current object parameters.
	 *
	 * @param	string	$dest_path
	 * @return	string
	 */
	private function command($dest_path)
	{
		// Build convert command
		$command = $this->convert_path . ' ';
		$command .= $this->file_path;

		// Crop
		if (!is_null($this->crop))
		{
			$command .= $this->crop;
		}

		// Resize
		if (!is_null($this->width) and !is_null($this->height))
		{
			$command .= ' -resize ';
			$command .= $this->width;
			$command .= 'x';
			$command .= $this->height;
		}
		else if (!is_null($this->width) and is_null($this->height))
		{
			$command .= ' -resize ';
			$command .= $this->width;
		}
		else if (is_null($this->width) and !is_null($this->height))
		{
			$command .= ' -resize ';
			$command .= 'x';
			$command .= $this->height;
		}

		// Auto-rotate and flatten image
		$command .= ' -background white -flatten -auto-orient';

		// Set image quality
		$command .= ' -quality ';
		$command .= $this->quality . ' ';

		// Set image destination
		$command .= $dest_path;

		// Return command
		return $command;
	}
}