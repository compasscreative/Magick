<?php

	namespace Reinink;

	use \Exception;

	class ImageMagick
	{
		private $convert_path;
		private $file_path;
		private $width;
		private $height;
		private $crop;
		private $quality = 90;

		public function __construct($convert_path = null, $file_path = null)
		{
			$this->convert_path = $convert_path;
			$this->file_path = $file_path;
		}

		public function set_convert_path($convert_path)
		{
			$this->convert_path = $convert_path;
		}

		public function set_file_path($file_path)
		{
			$this->file_path = $file_path;
		}

		public function set_crop($crop)
		{
			$this->crop = $crop;
		}

		public function set_width($width)
		{
			$this->width = $width;
		}

		public function set_height($height)
		{
			$this->height = $height;
		}

		public function set_quality($quality)
		{
			$this->quality = $quality;
		}

		public function convert($dest_path)
		{
			// Check if file exists
			if (!is_file($this->file_path))
			{
				return false;
			}

			// Build convert command
			$cmd = $this->convert_path . ' ';
			$cmd .= $this->file_path;

			// Crop
			if (is_numeric($this->crop))
			{
				// Get original image size
				$size = getimagesize($this->file_path);

				// Set dimensions
				$width = $size[0];
				$height = $width/$this->crop;

				// Update dimensions if out of document bounds
				if ($height > $size[1])
				{
					$width = $size[1]*$this->crop;
					$height = $size[1];
				}

				// Build crop command
				$cmd .= ' -gravity center -extent ' . $width . 'x' . $height . ' ';
			}
			else if (!is_null($this->crop))
			{
				$cmd .= ' -crop ';
				$cmd .= $this->crop;
			}

			// Resize
			if (!is_null($this->width) and !is_null($this->height))
			{
				$cmd .= ' -resize ';
				$cmd .= $this->width;
				$cmd .= 'x';
				$cmd .= $this->height;
			}
			else if (!is_null($this->width) and is_null($this->height))
			{
				$cmd .= ' -resize ';
				$cmd .= $this->width;
			}
			else if (is_null($this->width) and !is_null($this->height))
			{
				$cmd .= ' -resize ';
				$cmd .= 'x';
				$cmd .= $this->height;
			}

			// Flatten
			$cmd .= ' -background white -flatten -auto-orient';

			// Output
			$cmd .= ' -quality ';
			$cmd .= $this->quality . ' ';
			$cmd .= $dest_path;

			// Run command
			exec($cmd);

			// Return success
			return is_file($dest_path);
		}
	}