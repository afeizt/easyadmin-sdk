<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace Test\Fixtures\Controllers\System;

use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnnotation;

#[ControllerAnnotation(title: '配置管理')]
class ConfigController
{
	#[NodeAnnotation(title: '系统配置')]
	public function index(): void {}
}
