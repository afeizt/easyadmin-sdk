<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

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
	protected array $uploadConfig;

	/**
	 * 上传文件对象
	 *
	 * @var object
	 */
	protected $file;

	protected int $watermark_text_size = 50;
	/**
	 * 上传完成的文件路径.
	 *
	 * @var string
	 */
	protected string $completeFilePath;

	/**
	 * 上传完成的文件的URL.
	 *
	 * @var string
	 */
	protected string $completeFileUrl;

	/**
	 * completeThumbFilePath.
	 *
	 * @var string|null
	 */
	protected ?string $completeThumbFilePath = null;

	/**
	 * completeThumbFileUrl传完成的缩略图文件的URL.
	 *
	 * @var string|null
	 */
	protected ?string $completeThumbFileUrl = null;
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
	 * @var int|null
	 */
	protected ?int $image_thumb_width = null;
	/**
	 * image_thumb_height缩略图最大高度.
	 *
	 * @var int|null
	 */
	protected ?int $image_thumb_height = null;
	/**
	 * add_watermark是否启用水印.
	 *
	 * @var bool
	 */
	protected bool $add_watermark = false;
	/**
	 * watertype水印类型.
	 *
	 * @var string
	 */
	protected $watermark_type = 'image';

	/**
	 * upload_custom_domain自定义域名地址
	 *
	 * @var string
	 */
	protected $upload_custom_domain = '';
	/**
	 * upload_full_path是否使用完整路径（包含域名，仅对本地上传路径有效）
	 *
	 * @var bool
	 */
	protected $upload_full_path = false;
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
	public function setImageThumb($value): self
	{
		$this->image_thumb = (bool)$value;

		return $this;
	}

	/**
	 * setImageThumbWidth设置缩略图宽度.
	 *
	 * @param int $width
	 *
	 * @return $this
	 */
	public function setImageThumbWidth(int $width): self
	{
		$this->image_thumb_width = $width;

		return $this;
	}

	/**
	 * setImageThumbHeight设置缩略图高度.
	 *
	 * @param int $height
	 *
	 * @return $this
	 */
	public function setImageThumbHeight(int $height): self
	{
		$this->image_thumb_height = $height;

		return $this;
	}

	/**
	 * setWaterMarkBackground设置水印背景.
	 *
	 * @param mixed $color
	 *
	 * @return $this
	 */
	public function setWaterMarkBackground($color): self
	{
		$this->watermark_background = $color;

		return $this;
	}
	/**
	 * setWatermarkTextSize设置水印文字大小
	 *
	 * @param  int $size
	 * @return $this
	 */
	public function setWatermarkTextSize(int $size): self
	{
		if ($size > 0)
			$this->watermark_text_size = $size;
		return $this;
	}
	/**
	 * setWaterMarkImage设置水印图片.
	 *
	 * @param string $image
	 *
	 * @return $this
	 */
	public function setWaterMarkImage(string $image): self
	{
		$this->watermark_image = $image;

		return $this;
	}

	/**
	 * setWaterMarkType设置水印类型.
	 *
	 * @param string $text
	 *
	 * @return $this
	 */
	public function setWaterMarkContent(string $text): self
	{
		$this->watermark_string = $text;

		return $this;
	}

	/**
	 * setWaterMarkType设置水印类型.
	 *
	 * @param string $value
	 *
	 * @return $this
	 */
	public function setWaterMarkType(string $value): self
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
	public function setAddWatermark($value): self
	{
		$this->add_watermark = (bool)$value;

		return $this;
	}

	/**
	 * 设置上传方式.
	 *
	 * @param string $value
	 *
	 * @return $this
	 */
	public function setUploadType(string $value): self
	{
		$this->uploadType = $value;

		return $this;
	}
	public function setUploadCustomDomain(string $value): self
	{
		$this->upload_custom_domain = $value;
		return $this;
	}
	public function setUploadFullPath($value): self
	{
		$this->upload_full_path = (bool)$value;
		return $this;
	}
	/**
	 * 设置上传配置.
	 *
	 * @param array $value
	 *
	 * @return $this
	 */
	public function setUploadConfig(array $value): self
	{
		$this->uploadConfig = $value;

		return $this;
	}

	/**
	 * 设置上传配置.
	 *
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function setFile($value): self
	{
		$this->file = $value;

		return $this;
	}

	/**
	 * 设置保存文件数据表.
	 *
	 * @param string $value
	 *
	 * @return $this
	 */
	public function setTableName(string $value): self
	{
		$this->tableName = $value;

		return $this;
	}

	/**
	 * 保存文件到本地磁盘
	 *
	 * 流程：写入磁盘 → 可选水印 → 可选缩略图。
	 * 路径中的目录分隔符统一替换为 / 以保证跨平台兼容。
	 */
	public function save()
	{
		error_reporting(E_ERROR);
		$this->completeFilePath = Filesystem::disk('public')->putFile('upload', $this->file);
		$this->completeFilePath = str_replace(DIRECTORY_SEPARATOR, '/', $this->completeFilePath);
		$this->completeFileUrl = self::getUrlPrefix() . str_replace(DIRECTORY_SEPARATOR, '/', $this->completeFilePath);
		$ext = $this->file->extension();
		$image_exts = ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'webp'];

		// 仅对常见图片格式添加水印
		if ($this->add_watermark && in_array($ext, $image_exts)) {
			$this->addWaterMark($this->completeFilePath);
		}
		// 仅对常见图片格式生成缩略图
		if ($this->image_thumb && in_array($ext, $image_exts)) {
			$this->completeThumbFilePath = $this->createThumbImage($this->completeFilePath, $ext);
			$this->completeThumbFilePath = str_replace(DIRECTORY_SEPARATOR, '/', $this->completeThumbFilePath);
			$this->completeThumbFileUrl = self::getUrlPrefix() . str_replace(DIRECTORY_SEPARATOR, '/', $this->completeThumbFilePath);
		}
	}
	/**
	 * 根据上传配置拼接文件访问 URL 前缀
	 *
	 * 优先级：自定义域名 + 完整路径 > 当前域名完整路径 > 相对路径 /
	 */
	private function getUrlPrefix(): string
	{
		if ($this->upload_custom_domain != '' && $this->upload_full_path == true) {
			$match = preg_match('/http?:\/\//', $this->upload_custom_domain);
			if (!$match)
				$this->setUploadCustomDomain('//' . $this->upload_custom_domain);
			if (strripos($this->upload_custom_domain, '/') < strlen($this->upload_custom_domain) - 1)
				$this->setUploadCustomDomain($this->upload_custom_domain . '/');
			return $this->upload_custom_domain;
		} elseif ($this->upload_full_path == true) {
			return request()->domain() . '/';
		} else {
			return '/';
		}
	}
	/**
	 * 根据原图生成缩略图
	 *
	 * 按 mime 类型调用对应 GD 函数创建图像资源，等比缩放后保存。
	 * 文件名规则：原文件名后加 _thumb 后缀（由 getThumbName 生成）。
	 *
	 * @param string $file 原图完整路径
	 * @param string $ext  文件扩展名
	 * @return string|false 缩略图路径，非图片格式返回 false
	 */
	private function createThumbImage(string $file, string $ext)
	{
		error_reporting(E_ERROR);
		$mimeType = $this->file->getOriginalMime();
		if ($file) {
			if ($mimeType == 'image/pjpeg' || $mimeType == 'image/jpeg') {
				$imgtype = 'jpeg';
				$im = imagecreatefromjpeg($file);
			} elseif ($mimeType == 'image/x-png' || $mimeType == 'image/png') {
				$imgtype = 'png';
				$im = imagecreatefrompng($file);
				imagesavealpha($im, true); //这里很重要;
			} elseif ($mimeType == 'image/gif') {
				$imgtype = 'gif';
				$im = imagecreatefromgif($file);
				imagesavealpha($im, true); //这里很重要;
			} elseif ($mimeType == 'image/webp') {
				$imgtype = 'webp';
				$im = imagecreatefromwebp($file);
			} else {
				return false; //非图片
			}
			if (!empty($im)) {
				$thumb_name = $this->getThumbName($file, $ext);
				$this->resizeImage($im, $thumb_name, $mimeType);
				imagedestroy($im);
			}
		}

		return $thumb_name;
	}

	/**
	 * 生成缩略图文件名（原文件名 + _thumb 后缀）
	 *
	 * 例如：/upload/202310/abc.jpg → /upload/202310/abc_thumb.jpg
	 */
	private function getThumbName(string $file, string $ext): string
	{
		$index = strpos($file, $ext);
		$basename = substr($file, 0, $index - 1);

		return $basename . '_thumb.' . $ext;
	}

	/**
	 * 等比缩放图片并保存
	 *
	 * 根据设定的最大宽高（image_thumb_width / image_thumb_height）计算缩放比例，
	 * 取宽高比例中较小值保证图片不超出限定范围。处理透明通道避免 PNG/GIF 背景变黑。
	 *
	 * @param \GdImage $im       GD 图像资源句柄
	 * @param string   $file     输出文件路径
	 * @param string   $mimeType 输出 mime 类型（影响 imagepng/imagejpeg 选择）
	 */
	private function resizeImage($im, string $file, string $mimeType = 'jpeg'): void
	{
		error_reporting(E_ERROR);
		$width = imagesx($im);
		$height = imagesy($im);
		$maxwidth = $this->image_thumb_width;
		$maxheight = $this->image_thumb_height;
		if (($maxwidth && $width > $maxwidth) || ($maxheight && $height > $maxheight)) {
			$RESIZEWIDTH = false;
			$RESIZEHEIGHT = false;
			if ($maxwidth && $width > $maxwidth) {
				$widthratio = $maxwidth / $width;
				$RESIZEWIDTH = true;
			}
			if ($maxheight && $height > $maxheight) {
				$heightratio = $maxheight / $height;
				$RESIZEHEIGHT = true;
			}
			if ($RESIZEWIDTH && $RESIZEHEIGHT) {
				if ($widthratio < $heightratio) {
					$ratio = $widthratio;
				} else {
					$ratio = $heightratio;
				}
			} elseif ($RESIZEWIDTH) {
				$ratio = $widthratio;
			} elseif ($RESIZEHEIGHT) {
				$ratio = $heightratio;
			}

			$newwidth = intval($width * $ratio);
			$newheight = intval($height * $ratio);
			imagesavealpha($im, true);
			if (function_exists('imagecopyresampled')) {
				$newim = imagecreatetruecolor($newwidth, $newheight);
				imagesavealpha($newim, true); //不要丢了$resize_im图像的透明色;
				imagealphablending($newim, false); //不合并颜色,直接用$im图像颜色替换,包括透明色;
				//新增两行解决png图片背景发黑问题
				$transparent = imagecolorallocatealpha($newim, 0, 0, 0, 127); // 设置透明背景色
				imagefill($newim, 0, 0, $transparent); // 填充透明背景色

				imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			} else {
				$newim = imagecreate($newwidth, $newheight);
				imagealphablending($newim, false); //不合并颜色,直接用$im图像颜色替换,包括透明色;
				imagesavealpha($newim, true); //不要丢了$resize_im图像的透明色;
				imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			}
			if ($mimeType == 'image/png') {
				imagepng($newim, $file);
			} elseif ($mimeType == 'image/gif') {
				$color = imagecolorallocate($newim, 255, 255, 255); //解决gif背景黑色问题
				imagecolortransparent($newim, $color); //解决gif背景黑色问题
				imagefill($newim, 0, 0, $color); //解决gif背景黑色问题
				imagegif($newim, $file);
			} else {
				imagejpeg($newim, $file);
			}

			imagedestroy($newim);
		} else {
			imagejpeg($im, $file);
		}
	}

	/**
	 * 为图片添加水印
	 *
	 * 支持文字水印和图片水印两种模式，水印居中放置。
	 * 文字水印使用 TrueType 字体，可选半透明背景底板。
	 *
	 * @param string $file 待处理的原图路径（处理后会直接覆盖原文件）
	 */
	private function addWaterMark(string $file): void
	{
		error_reporting(E_ERROR);
		$file_width = 0;
		$file_height = 0;
		$file_size = $this->file->getSize();

		$iinfo = getimagesize($file, $iinfo);
		if (empty($iinfo)) {
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
		switch ($iinfo[2]) {
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
		switch ($watermark_type) {
			case 'text': //加水印字符串
				$watermark_text = $this->watermark_string;
				$watermark_text_count = strlen($watermark_text);
				$font_size = $this->watermark_text_size * 50;
				$font_angle = 0;
				$font_url = __DIR__ . '/fonts/SourceHanSansK-Bold.ttf'; //水印字体;
				$f = imagettfbbox($font_size, $font_angle, $font_url, $watermark_text);
				// $watermark_width = imagefontwidth($fontv) * ($watermark_text_count + 2); //字体宽度,留2px边距
				$watermark_width = $f[2] - $f[0];
				//  $watermark_height = imagefontheight($fontv) + 2; //字体高度,留2px边距
				$watermark_height = $f[1] - $f[7];
				$x1 = intval($file_width / 2 - $watermark_width / 2); //得到居中水印的左边坐标;
				$y1 = intval($file_height / 2 - $watermark_height / 2); //得到居中水印的上边坐标
				$y_base = intval($file_height / 2 + $watermark_height / 2); //得到左下角基线左边近似值
				$x2 = intval($x1 + $watermark_width);
				$y2 = intval($y1 + $watermark_height);
				//居中画一个80x15的水印背景
				if ($this->watermark_background) {
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
				$x1 = intval($file_width / 2 - $watermark_width / 2); //得到居中水印的左边坐标;
				$y1 = intval($file_height / 2 - $watermark_height / 2); //得到居中水印的上边坐标
				$x2 = intval($x1 + $watermark_width);
				$y2 = intval($y1 + $watermark_height);
				if ($watermark_type == 3) {
					$simage1 = imagecreatefrompng($image);
				} elseif ($watermark_type == 1) {
					$simage1 = imagecreatefromgif($image);
				} elseif ($watermark_type == 2) {
					$simage1 = imagecreatefromjpeg($image);
				} elseif ($watermark_type == 6) {
					$simage1 = imagecreatefromwbmp($image);
				} elseif ($watermark_type == 18) {
					$simage1 = imagecreatefromwebp($image);
				} else {
					break;
				}

				imagecopy($nimage, $simage1, $x1, $y1, 0, 0, $watermark_width, $watermark_height);
				imagedestroy($simage1);
				break;
		}

		//生成水印覆盖原上传文件
		switch ($iinfo[2]) {
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
	public function rmLocalSave(): bool|string
	{
		try {
			$rm = unlink($this->completeFilePath);
			if (!empty($this->completeThumbFilePath)) {
				unlink($this->completeThumbFilePath);
			}
		} catch (\Exception $e) {
			return $e->getMessage();
		}

		return $rm;
	}
}
