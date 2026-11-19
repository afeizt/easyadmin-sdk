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
	 * 返回自增主键，供上层补写 source 等业务字段；原先返回 void 导致调用方
	 * 只能按 url 回查记录，同 url 并发或复用时会取错行。
	 *
	 * @param string $tableName 数据表名
	 * @param array  $data      文件数据
	 *
	 * @return int 新记录的自增 ID
	 */
	public static function trigger(string $tableName, array $data): int
	{
		return (int)Db::name($tableName)->insertGetId($data);
	}
}