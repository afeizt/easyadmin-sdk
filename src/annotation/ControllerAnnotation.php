<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace EasyAdmin\annotation;

use Attribute;

/**
 * 控制器级注解
 *
 * auth 字段支持两种写法：
 *   bool:  auth: true（默认需登录）, auth: false（公开接口）
 *   array: auth: ['login' => true, 'rbac' => true]（需登录且校验节点权限）
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class ControllerAnnotation
{
	/**
	 * @param string      $title 控制器标题
	 * @param bool|array  $auth  认证配置，bool 控制登录，array 可额外指定 rbac
	 */
	public function __construct(
		public string      $title = '',
		public bool|array  $auth  = true,
	) {}
}
