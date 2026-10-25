<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace EasyAdmin\tool;


/**
 * 通用工具类
 *
 * 提供字符串格式转换、IP 获取、文件遍历等常用工具方法。
 */
class CommonTool
{

	/**
	 * 下划线转驼峰
	 * @param string $str
	 * @return string|null
	 */
	public static function lineToHump(string $str): ?string
	{
		$str = preg_replace_callback('/([-_]+([a-z]{1}))/i', function ($matches) {
			return strtoupper($matches[2]);
		}, $str);
		return $str;
	}

	/**
	 * 驼峰转下划线
	 * @param string $str
	 * @return string|null
	 */
	public static function humpToLine(string $str): ?string
	{
		$str = preg_replace_callback('/([A-Z]{1})/', function ($matches) {
			return '_' . strtolower($matches[0]);
		}, $str);
		return $str;
	}

	/**
	 * 获取客户端真实 IP
	 *
	 * 依次检查 HTTP_X_FORWARDED_FOR / HTTP_CLIENT_IP / HTTP_CF_CONNECTING_IP / HTTP_X_REAL_IP，
	 * 过滤内网地址后返回最外层公网 IP，用于透传代理/CDN 后的真实客户端地址。
	 * @return string
	 */
	public static function getRealIp(): string
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
			foreach ($matches[0] as $xip) {
				if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
					$ip = $xip;
					break;
				}
			}
		} elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
			$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
		} elseif (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
			$ip = $_SERVER['HTTP_X_REAL_IP'];
		}
		return $ip;
	}

	/**
	 * 读取文件夹下的所有文件
	 * @param string $path
	 * @param string $basePath
	 * @return array
	 */
	public static function readDirAllFiles(string $path, string $basePath = ''): array
	{
		list($list, $temp_list) = [[], scandir($path)];
		empty($basePath) && $basePath = $path;
		foreach ($temp_list as $file) {
			if ($file != ".." && $file != ".") {
				if (is_dir($path . DIRECTORY_SEPARATOR . $file)) {
					$childFiles = self::readDirAllFiles($path . DIRECTORY_SEPARATOR . $file, $basePath);
					$list = array_merge($childFiles, $list);
				} else {
					$filePath = $path . DIRECTORY_SEPARATOR . $file;
					$fileName = str_replace($basePath . DIRECTORY_SEPARATOR, '', $filePath);
					$list[$fileName] = $filePath;
				}
			}
		}
		return $list;
	}

	/**
	 * 模板值替换
	 * @param string $string
	 * @param array  $array
	 * @return string
	 */
	public static function replaceTemplate(string $string, array $array): string
	{
		foreach ($array as $key => $val) {
			$string = str_replace("{{" . $key . "}}", $val, $string);
		}
		return $string;
	}
}
