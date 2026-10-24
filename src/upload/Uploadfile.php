<?php
namespace EasyAdmin\upload;

use EasyAdmin\upload\driver\Alioss;
use EasyAdmin\upload\driver\Local;
use EasyAdmin\upload\driver\Qnoss;
use EasyAdmin\upload\driver\Txcos;
use think\File;

/**
 * 上传组件
 * Class Uploadfile.
 */
class Uploadfile
{
	/**
	 * 当前实例对象
	 *
	 * @var object
	 */
	protected static $instance;

	/**
	 * 上传方式.
	 *
	 * @var string
	 */
	protected $uploadType = 'local';

	/**
	 * 上传配置文件.
	 *
	 * @var array
	 */
	protected $uploadConfig;

	/**
	 * 需要上传的文件对象
	 *
	 * @var File
	 */
	protected $file;

	/**
	 * 保存上传文件的数据表.
	 *
	 * @var string
	 */
	protected $tableName = 'system_uploadfile';

	/**
	 * 获取对象实例.
	 *
	 * @return Uploadfile|object
	 */
	public static function instance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = new static ();
		}

		return self::$instance;
	}

	/**
	 * 设置上传对象
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
	 * 设置上传文件.
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
	 * 设置保存数据表.
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
	 *
	 * @return array|void
	 */
	public function save()
	{
		$obj = null;
		if ($this->uploadType == 'local')
		{
			$obj = new Local();
		}
		elseif ($this->uploadType == 'alioss')
		{
			$obj = new Alioss();
		}
		elseif ($this->uploadType == 'qnoss')
		{
			$obj = new Qnoss();
		}
		elseif ($this->uploadType == 'txcos')
		{
			$obj = new Txcos();
		}
		$save = $obj->setUploadConfig($this->uploadConfig)
		 ->setUploadType($this->uploadType)
		 ->setWaterMarkImage($this->uploadConfig['watermark_image'] ?? '')
		 ->setWaterMarkType($this->uploadConfig['watermark_type'] ?? 0)
		 ->setWatermarkTextSize($this->uploadConfig['watermark_text_size'] ?? 5)
		 ->setWaterMarkContent($this->uploadConfig['watermark_text'] ?? '')
		 ->setAddWatermark($this->uploadConfig['add_watermark'] ?? 0)
		 ->setTableName($this->tableName)
		 ->setImageThumb($this->uploadConfig['image_thumb'] ?? 0)
		 ->setImageThumbWidth($this->uploadConfig['image_thumb_width'] ?? 500)
		 ->setImageThumbHeight($this->uploadConfig['image_thumb_height'] ?? 500)
		 ->setFile($this->file)
		 ->setUploadFullPath($this->uploadConfig['upload_full_path'] ?? 0)
		 ->setUploadCustomDomain($this->uploadConfig['upload_custom_domain'] ?? '')
		 ->save();

		return $save;
	}
}
