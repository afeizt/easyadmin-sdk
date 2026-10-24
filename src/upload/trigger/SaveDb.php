<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace EasyAdmin\upload\trigger;


use think\facade\Db;

/**
 * 数据库保存触发器
 *
 * 将上传文件信息保存到数据库中。
 */
class SaveDb
{

	/**
	 * 保存上传文件信息
	 *
	 * @param string $tableName 数据表名
	 * @param array  $data      文件数据
	 */
	public static function trigger($tableName, $data)
	{
		Db::name($tableName)->save($data);
	}
}
