<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace EasyAdmin\upload\interfaces;

/**
 * 对象存储驱动接口
 *
 * 定义云存储上传驱动的统一规范。
 */
interface OssDriver
{

	/**
	 * 保存文件到云存储
	 *
	 * @param string $objectName 对象名称
	 * @param string $filePath   本地文件路径
	 * @return array
	 */
	public function save($objectName, $filePath);
}
