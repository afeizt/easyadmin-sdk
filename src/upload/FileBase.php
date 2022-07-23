<?php



namespace EasyAdmin\upload;

use think\facade\Filesystem;
use think\File;

/**
 * 基类
 * Class Base.
 */
class FileBase
{
	/**
	 * 上传配置.
	 *
	 * @var array
	 */
	protected $uploadConfig;

	/**
	 * 上传文件对象
	 *
	 * @var object
	 */
	protected $file;

	/**
	 * 上传完成的文件路径.
	 *
	 * @var string
	 */
	protected $completeFilePath;

	/**
	 * 上传完成的文件的URL.
	 *
	 * @var string
	 */
	protected $completeFileUrl;

	/**
	 * completeThumbFilePath.
	 *
	 * @var mixed
	 */
	protected $completeThumbFilePath;

	/**
	 * completeThumbFileUrl传完成的缩略图文件的URL.
	 *
	 * @var mixed
	 */
	protected $completeThumbFileUrl;
	/**
	 * 保存上传文件的数据表.
	 *
	 * @var string
	 */
	protected $tableName;

	/**
	 * 上传类型.
	 *
	 * @var string
	 */
	protected $uploadType = 'local';

	/**
	 * image_thumb是否使用缩略图.
	 *
	 * @var bool
	 */
	protected $image_thumb = false;
	/**
	 * image_thumb_width缩略图最大宽度.
	 *
	 * @var mixed
	 */
	protected $image_thumb_width;
	/**
	 * image_thumb_height缩略图最大高度.
	 *
	 * @var mixed
	 */
	protected $image_thumb_height;
	/**
	 * add_watermark是否启用水印.
	 *
	 * @var mixed
	 */
	protected $add_watermark = false;
	/**
	 * watertype水印类型.
	 *
	 * @var string
	 */
	protected $watermark_type = 'image';

	/**
	 * watermark_image水印图片.
	 *
	 * @var string
	 */
	protected $watermark_image = '';
	/**
	 * waterstring水印文字.
	 *
	 * @var string
	 */
	protected $watermark_string = 'watermark';

	/**
	 * watermark_background使用水印背景色.
	 *
	 * @var bool
	 */
	protected $watermark_background = false;

	/**
	 * setImageThumb设置是否使用缩略图.
	 *
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function setImageThumb($value)
	{
		$this->image_thumb = (bool)$value;

		return $this;
	}

	/**
	 * setImageThumbWidth设置缩略图宽度.
	 *
	 * @param mixed $width
	 *
	 * @return $this
	 */
	public function setImageThumbWidth($width)
	{
		$this->image_thumb_width = $width;

		return $this;
	}

	/**
	 * setImageThumbHeight设置缩略图高度.
	 *
	 * @param mixed $height
	 *
	 * @return $this
	 */
	public function setImageThumbHeight($height)
	{
		$this->image_thumb_height = $height;

		return $this;
	}

	/**
	 * setWaterMarkBackground设置水印背景.
	 *
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function setWaterMarkBackground($color)
	{
		$this->watermark_background = $color;

		return $this;
	}

	/**
	 * setWaterMarkImage设置水印图片.
	 *
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function setWaterMarkImage($image)
	{
		$this->watermark_image = $image;

		return $this;
	}

	/**
	 * setWaterMarkType设置水印类型.
	 *
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function setWaterMarkContent($text)
	{
		$this->watermark_string = $text;

		return $this;
	}

	/**
	 * setWaterMarkType设置水印类型.
	 *
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function setWaterMarkType($value)
	{
		$this->watermark_type = $value;

		return $this;
	}

	/**
	 * setAddWatermark设置是否启用水印.
	 *
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function setAddWatermark($value)
	{
		$this->add_watermark = (bool)$value;

		return $this;
	}

	/**
	 * 设置上传方式.
	 *
	 * @param $value
	 *
	 * @return $this
	 */
	public function setUploadType($value)
	{
		$this->uploadType = $value;

		return $this;
	}

	/**
	 * 设置上传配置.
	 *
	 * @param $value
	 *
	 * @return $this
	 */
	public function setUploadConfig($value)
	{
		$this->uploadConfig = $value;

		return $this;
	}

	/**
	 * 设置上传配置.
	 *
	 * @param $value
	 *
	 * @return $this
	 */
	public function setFile($value)
	{
		$this->file = $value;

		return $this;
	}

	/**
	 * 设置保存文件数据表.
	 *
	 * @param $value
	 *
	 * @return $this
	 */
	public function setTableName($value)
	{
		$this->tableName = $value;

		return $this;
	}

	/**
	 * 保存文件.
	 */
	public function save()
	{
		$this->completeFilePath = Filesystem::disk('public')->putFile('upload', $this->file);
		$this->completeFilePath = str_replace(DIRECTORY_SEPARATOR, '/', $this->completeFilePath);
		$this->completeFileUrl = request()->domain() . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $this->completeFilePath);
		$ext = $this->file->extension();
		$image_exts = ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'webp'];

		if ($this->add_watermark && in_array($ext, $image_exts))
		{
			$this->addWaterMark($this->completeFilePath);
		}
		if ($this->image_thumb && in_array($ext, $image_exts))
		{
			$this->completeThumbFilePath = $this->createThumbImage($this->completeFilePath, $ext);
			$this->completeThumbFilePath = str_replace(DIRECTORY_SEPARATOR, '/', $this->completeThumbFilePath);
			$this->completeThumbFileUrl = request()->domain() . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $this->completeThumbFilePath);
		}
	}

	private function createThumbImage($file, $ext)
	{
		$mimeType = $this->file->getOriginalMime();
		if ($file)
		{
			if ($mimeType == 'image/pjpeg' || $mimeType == 'image/jpeg')
			{
				$imgtype = 'jpeg';
				$im = imagecreatefromjpeg($file);
			}
			elseif ($mimeType == 'image/x-png' || $mimeType == 'image/png')
			{
				$imgtype = 'png';
				$im = imagecreatefrompng($file);
				imagesavealpha($im, true); //这里很重要;
			}
			elseif ($mimeType == 'image/gif')
			{
				$imgtype = 'gif';
				$im = imagecreatefromgif($file);
				imagesavealpha($im, true); //这里很重要;
			}
			elseif ($mimeType == 'image/webp')
			{
				$imgtype = 'webp';
				$im = imagecreatefromwebp($file);
			}
			else
			{
				return false; //非图片
			}
			if (!empty($im))
			{
				$thumb_name = $this->getThumbName($file, $ext);
				$this->resizeImage($im, $thumb_name, $mimeType);
				imagedestroy($im);
			}
		}

		return $thumb_name;
	}

	private function getThumbName($file, $ext)
	{
		$index = strpos($file, $ext);
		$basename = substr($file, 0, $index - 1);

		return $basename . '_thumb.' . $ext;
	}

	/**
	 * resizeImage.
	 *
	 * @param mixed $im原图句柄
	 * @param mixed $file
	 * @param mixed $mimeType
	 *
	 * @return void
	 */
	private function resizeImage($im, $file, $mimeType = 'jpeg')
	{
		$width = imagesx($im);
		$height = imagesy($im);
		$maxwidth = $this->image_thumb_width;
		$maxheight = $this->image_thumb_height;
		if (($maxwidth && $width > $maxwidth) || ($maxheight && $height > $maxheight))
		{
			$RESIZEWIDTH = false;
			$RESIZEHEIGHT = false;
			if ($maxwidth && $width > $maxwidth)
			{
				$widthratio = $maxwidth / $width;
				$RESIZEWIDTH = true;
			}
			if ($maxheight && $height > $maxheight)
			{
				$heightratio = $maxheight / $height;
				$RESIZEHEIGHT = true;
			}
			if ($RESIZEWIDTH && $RESIZEHEIGHT)
			{
				if ($widthratio < $heightratio)
				{
					$ratio = $widthratio;
				}
				else
				{
					$ratio = $heightratio;
				}
			}
			elseif ($RESIZEWIDTH)
			{
				$ratio = $widthratio;
			}
			elseif ($RESIZEHEIGHT)
			{
				$ratio = $heightratio;
			}

			$newwidth = intval($width * $ratio);
			$newheight = intval($height * $ratio);
			imagesavealpha($im, true);
			if (function_exists('imagecopyresampled'))
			{
				$newim = imagecreatetruecolor($newwidth, $newheight);
				imagealphablending($newim, false); //不合并颜色,直接用$im图像颜色替换,包括透明色;
				imagesavealpha($newim, true); //不要丢了$resize_im图像的透明色;
				imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			}
			else
			{
				$newim = imagecreate($newwidth, $newheight);
				imagealphablending($newim, false); //不合并颜色,直接用$im图像颜色替换,包括透明色;
				imagesavealpha($newim, true); //不要丢了$resize_im图像的透明色;
				imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			}
			if ($mimeType == 'image/png')
			{
				imagepng($newim, $file);
			}
			elseif ($mimeType == 'image/gif')
			{
				$color = imagecolorallocate($newim, 255, 255, 255); //解决gif背景黑色问题
				imagecolortransparent($newim, $color); //解决gif背景黑色问题
				imagefill($newim, 0, 0, $color); //解决gif背景黑色问题
				imagegif($newim, $file);
			}
			else
			{
				imagejpeg($newim, $file);
			}

			imagedestroy($newim);
		}
		else
		{
			imagejpeg($im, $file);
		}
	}

	private function addWaterMark($file)
	{
		// $mimeType = $this->file->getOriginalMime();
		$file_width = 0;
		$file_height = 0;
		$file_size = $this->file->getSize();

		$iinfo = getimagesize($file, $iinfo);
		if (empty($iinfo))
		{
			return;
		} //非图片
		$file_width = $iinfo[0];
		$file_height = $iinfo[1];
		$nimage = imagecreatetruecolor($file_width, $file_height);
		$gray = imagecolorallocate($nimage, 170, 172, 172);
		$white = imagecolorallocate($nimage, 255, 255, 255);
		$black = imagecolorallocate($nimage, 0, 0, 0);
		$red = imagecolorallocate($nimage, 255, 0, 0);
		imagefill($nimage, 0, 0, $white);
		switch ($iinfo[2])
		{
			case 1:
				$simage = imagecreatefromgif($file);
				break;
			case 2:
				$simage = imagecreatefromjpeg($file);
				break;
			case 3:
				$simage = imagecreatefrompng($file);
				break;
			case 6:
				$simage = imagecreatefromwbmp($file);
				break;
			case 18:
				$simage = imagecreatefromwebp($file);
				break;
			default:
				return; //非支持图片
		}

		imagecopy($nimage, $simage, 0, 0, 0, 0, $file_width, $file_height);

		$watermark_type = $this->watermark_type;


		// $fontv = imageloadfont($font_url);
		switch ($watermark_type)
		{
			case 'text': //加水印字符串
				$watermark_text = $this->watermark_string;
				$watermark_text_count = strlen($watermark_text);
				$font_size = 45;
				$font_angle = 0;
				$font_url = $_SERVER['DOCUMENT_ROOT'] . '/fonts/SourceHanSansK-Bold.ttf'; //水印字体;
				$f = imagettfbbox($font_size, $font_angle, $font_url, $watermark_text);
				// $watermark_width = imagefontwidth($fontv) * ($watermark_text_count + 2); //字体宽度,留2px边距
				$watermark_width = $f[2] - $f[0];
				//  $watermark_height = imagefontheight($fontv) + 2; //字体高度,留2px边距
				$watermark_height = $f[1] - $f[7];
				$x1 = $file_width / 2 - $watermark_width / 2; //得到居中水印的左边坐标;
				$y1 = $file_height / 2 - $watermark_height / 2; //得到居中水印的上边坐标
				$y_base = $file_height / 2 + $watermark_height / 2; //得到左下角基线左边近似值
				$x2 = $x1 + $watermark_width;
				$y2 = $y1 + $watermark_height;
				//居中画一个80x15的水印背景
				if ($this->watermark_background)
				{
					imagefilledrectangle($nimage, $x1, $y1, $x2, $y2, $white);
				}


				imagettftext($nimage, $font_size, $font_angle, $x1, $y_base, $gray, $font_url, $this->watermark_string);
				break;
			case 'image': //加水印图片
				$image = empty($this->watermark_image) ? $_SERVER['DOCUMENT_ROOT'] . '/static/watermark.png' : $this->watermark_image;
				$watermark_image = getimagesize($image);
				$watermark_width = $watermark_image[0];
				$watermark_height = $watermark_image[1];
				$watermark_type = $watermark_image[2];
				$x1 = $file_width / 2 - $watermark_width / 2; //得到居中水印的左边坐标;
				$y1 = $file_height / 2 - $watermark_height / 2; //得到居中水印的上边坐标
				$x2 = $x1 + $watermark_width;
				$y2 = $y1 + $watermark_height;
				if ($watermark_type == 3)
				{
					$simage1 = imagecreatefrompng($image);
				}
				elseif ($watermark_type == 1)
				{
					$simage1 = imagecreatefromgif($image);
				}
				elseif ($watermark_type == 2)
				{
					$simage1 = imagecreatefromjpeg($image);
				}
				elseif ($watermark_type == 6)
				{
					$simage1 = imagecreatefromwbmp($image);
				}
				elseif ($watermark_type == 18)
				{
					$simage1 = imagecreatefromwebp($image);
				}
				else
				{
					break;
				}

				imagecopy($nimage, $simage1, $x1, $y1, 0, 0, $watermark_width, $watermark_height);
				imagedestroy($simage1);
				break;
		}

		//生成水印覆盖原上传文件
		switch ($iinfo[2])
		{
			case 1:
				//imagegif($nimage, $destination);
				imagejpeg($nimage, $file);
				break;
			case 2:
				imagejpeg($nimage, $file);
				break;
			case 3:
				imagepng($nimage, $file);
				break;
			case 6:
				imagewbmp($nimage, $file);
			case 18:
				imagewebp($nimage, $file);
				break;
		}

		imagedestroy($nimage);
		imagedestroy($simage);
	}

	/**
	 * 删除保存在本地的文件.
	 *
	 * @return bool|string
	 */
	public function rmLocalSave()
	{
		try
		{
			$rm = unlink($this->completeFilePath);
			if (!empty($this->completeThumbFilePath))
			{
				unlink($this->completeThumbFilePath);
			}
		}
		catch (\Exception $e)
		{
			return $e->getMessage();
		}

		return $rm;
	}
}
