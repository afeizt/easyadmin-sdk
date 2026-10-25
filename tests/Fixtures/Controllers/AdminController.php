<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace Test\Fixtures\Controllers;

use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnnotation;

#[ControllerAnnotation(title: '管理员管理')]
class AdminController
{
	#[NodeAnnotation(title: '列表')]
	public function index(): void {}

	#[NodeAnnotation(title: '新增')]
	public function add(): void {}

	#[NodeAnnotation(title: '删除', auth: false)]
	public function delete(): void {}
}
