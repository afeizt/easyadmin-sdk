<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace EasyAdmin\upload\driver\alioss;

use EasyAdmin\upload\interfaces\OssDriver;
use OSS\Core\OssException;
use OSS\OssClient;

/**
 * 阿里云 OSS 上传驱动
 *
 * 封装阿里云 OSS SDK，实现文件上传到阿里云对象存储。
 */
class Oss implements OssDriver
{

	/**
	 * 单例实例
	 *
	 * @var static
	 */
	protected static $instance;

	/**
	 * @var string
	 */
	protected $accessKeyId;

	/**
	 * @var string
	 */
	protected $accessKeySecret;

	/**
	 * @var string
	 */
	protected $endpoint;

	/**
	 * @var string
	 */
	protected $bucket;

	/**
	 * @var string
	 */
	protected $domain;

	/**
	 * @var OssClient
	 */
	protected $ossClient;

	/**
	 * 构造方法
	 *
	 * @param array $config 阿里云 OSS 配置
	 */
	protected function __construct($config)
	{
		$this->accessKeyId = $config['alioss_access_key_id'];
		$this->accessKeySecret = $config['alioss_access_key_secret'];
		$this->endpoint = $config['alioss_endpoint'];
		$this->bucket = $config['alioss_bucket'];
		$this->domain = $config['alioss_domain'];
		$this->ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
		return $this;
	}

	/**
	 * 获取单例实例
	 *
	 * @param array $config 阿里云 OSS 配置
	 * @return static
	 */
	public static function instance($config)
	{
		if (is_null(self::$instance)) {
			self::$instance = new static($config);
		}
		return self::$instance;
	}

	/**
	 * 上传文件到阿里云 OSS
	 *
	 * @param string $objectName 对象名称
	 * @param string $filePath   本地文件路径
	 * @return array
	 */
	public function save($objectName, $filePath)
	{
		try {
			$upload = $this->ossClient->uploadFile($this->bucket, $objectName, $filePath);
		} catch (OssException $e) {
			return [
				'save' => false,
				'msg'  => $e->getMessage(),
			];
		}
		if (!isset($upload['info']['url'])) {
			return [
				'save' => false,
				'msg'  => '保存失败',
			];
		}
		return [
			'save' => true,
			'msg'  => '上传成功',
			'url'  => $upload['info']['url'],
		];
	}
}
