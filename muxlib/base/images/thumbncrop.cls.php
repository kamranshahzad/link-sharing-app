<?php
	class ThumbAndCrop
	{
	
/*		private $handleimg;
		private $original = "";
		private $handlethumb;
		private $oldoriginal;*/
		
		var $handleimg;
		var $original = "";
		var $handlethumb;
		var $oldoriginal;

		function openImage($file)
		{
			$this->original = $file;
			if($this->extension($file) == 'jpg' || $this->extension($file) == 'jpeg')
			{
				$this->handleimg = imagecreatefromjpeg($file);
			}
			elseif($this->extension($file) == 'png')
			{
				$this->handleimg = imagecreatefrompng($file);
			}
			elseif($this->extension($file) == 'gif')
			{
				$this->handleimg = imagecreatefromgif($file);
			}
			elseif($this->extension($file) == 'bmp')
			{
				$this->handleimg = imagecreatefromwbmp($file);
			}
		}
		

		function getWidth()
		{
			return imageSX($this->handleimg);
		}
		
		function getHeight()
		{
			return imageSY($this->handleimg);
		}
		
		function getRightWidth($newheight)
		{
			$oldw = $this->getWidth();
			$oldh = $this->getHeight();
			$neww = ($oldw * $newheight) / $oldh;
			return $neww;
		}
		
		function getRightHeight($newwidth)
		{
			$oldw = $this->getWidth();
			$oldh = $this->getHeight();
			$newh = ($oldh * $newwidth) / $oldw;
			return $newh;
		}
		

		function createThumb($newWidth, $newHeight)
		{
			$oldw = $this->getWidth();
			$oldh = $this->getHeight();
			$this->handlethumb = imagecreatetruecolor($newWidth, $newHeight);
			return imagecopyresampled($this->handlethumb, $this->handleimg, 0, 0, 0, 0, $newWidth, $newHeight, $oldw, $oldh);
		}
		
		
		function cropThumb($width, $height, $x, $y)
		{
			$oldw = $this->getWidth();
			$oldh = $this->getHeight();
			$this->handlethumb = imagecreatetruecolor($width, $height);
			return imagecopy($this->handlethumb, $this->handleimg, 0, 0, $x, $y, $width, $height);
		}
		
		
		function saveThumb($path, $qualityJpg = 100)
		{
			if($this->extension($this->original) == 'jpg' || $this->extension($this->original) == 'jpeg')
			{
				return imagejpeg($this->handlethumb, $path, $qualityJpg);
			}
			elseif($this->extension($this->original) == 'png')
			{
				return imagepng($this->handlethumb, $path);
			}
			elseif($this->extension($this->original) == 'gif')
			{
				return imagegif($this->handlethumb, $path);
			}
			elseif($this->extension($this->original) == 'bmp')
			{
				return imagewbmp($this->handlethumb, $path);
			}
		}
		
		
		
		function getAspectRatio($toWidth , $toHeight){
				$xscale = $this->getWidth() / $toWidth;
				$yscale = $this->getHeight()/ $toHeight;
				
				if ( $yscale > $xscale ){
					$new_width = round($this->getWidth() * (1/$yscale));
					$new_height = round($this->getHeight() * (1/$yscale));
				}
				else {
					$new_width = round($this->getWidth() * (1/$xscale));
					$new_height = round($this->getHeight() * (1/$xscale));
				}
			
			
				return array($new_width,$new_height);
		}
		
		
		function printThumb()
		{
			if($this->extension($this->original) == 'jpg' || $this->xtension($this->original) == 'jpeg')
			{
				header("Content-Type: image/jpeg");
				imagejpeg($this->handlethumb);
			}
			elseif($this->extension($this->original) == 'png')
			{
				header("Content-Type: image/png");
				imagepng($this->handlethumb);
			}
			elseif($this->extension($this->original) == 'gif')
			{
				header("Content-Type: image/gif");
				imagegif($this->handlethumb);
			}
			elseif($this->extension($this->original) == 'bmp')
			{
				header("Content-Type: image/bmp");
				imagewbmp($this->handlethumb);
			}
		}
		

		function closeImg()
		{
			imagedestroy($this->handleimg);
			imagedestroy($this->handlethumb);
		}
		
		/*
			Set thumbnail as source image,
			so that we can combine the function creates the crop function
		*/
		function setThumbAsOriginal()
		{
			$this->oldoriginal = $this->handleimg;
			$this->handleimg = $this->handlethumb;
		}
		
		function resetOriginal()
		{
			$this->handleimg = $this->oldoriginal;
		}
		
		function extension($percorso)
		{
			if(eregi("[\|\\]", $percorso))
			{
				// da percorso
				$nome = $this->nomefile($percorso);
				
				$spezzo = explode(".", $nome);
				
				return strtolower(trim(array_pop($spezzo)));
			}
			else
			{
				//da file
				$spezzo = explode(".", $percorso);
				
				return strtolower(trim(array_pop($spezzo)));
			}
		}
		

		function nomefile($path, $ext = true)
		{
			$diviso = spliti("[/|\\]", $path);
			
			if($ext)
			{
				return trim(array_pop($diviso));
			}
			else
			{
				$nome = explode(".", trim(array_pop($diviso)));
				
				array_pop($nome);
				
				return trim(implode(".", $nome));
			}
		}
		
		
	} // $class
?>