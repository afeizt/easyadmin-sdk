<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace EasyAdmin\upload\driver\qnoss;

use EasyAdmin\upload\interfaces\OssDriver;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

/**
 * 七牛云 OSS 上传驱动
 *
 * 封装七牛云 SDK，实现文件上传到七牛云对象存储。
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
	protected $accessKey;

	/**
	 * @var string
	 */
	protected $secretKey;

	/**
	 * @var string
	 */
	protected $bucket;

	/**
	 * @var string
	 */
	protected $domain;

	/**
	 * @var Auth
	 */
	protected $auth;

	/**
	 * 构造方法
	 *
	 * @param array $config 七牛云配置
	 */
	public function __construct($config)
	{
		$this->accessKey = $config['qnoss_access_key'];
		$this->secretKey = $config['qnoss_secret_key'];
		$this->bucket = $config['qnoss_bucket'];
		$this->domain = $config['qnoss_domain'];
		$this->auth = new Auth($this->accessKey, $this->secretKey);
	}

	/**
	 * 获取单例实例
	 *
	 * @param array $config 七牛云配置
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
	 * 上传文件到七牛云
	 *
	 * @param string $objectName 对象名称
	 * @param string $filePath   本地文件路径
	 * @return array
	 */
	public function save($objectName, $filePath)
	{
		$token = $this->auth->uploadToken($this->bucket);
		$uploadMgr = new UploadManager();
		list($result, $error) = $uploadMgr->putFile($token, $objectName, $filePath);
		if ($error !== null) {
			return [
				'save' => false,
				'msg' => '保存失败',
			];
		} else {
			return [
				'save' => true,
				'msg' => '上传成功',
				'url' => $this->domain . '/' . $result['key'],
			];
		}
	}
}
