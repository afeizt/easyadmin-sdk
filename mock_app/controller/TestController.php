<?php

namespace MockApp\controller;

use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnnotation;
use MockApp\BaseController;


/**
 * Class TestController
 * @package MockApp\controller
 * @ControllerAnnotation(title="测试管理")
 */
class TestController extends BaseController
{

	/**
	 * @NodeAnnotation(title="列表")
	 */
	public function index()
	{
		var_dump(__METHOD__);
	}

	/**
	 * @NodeAnnotation(title="详情")
	 */
	public function details()
	{
		var_dump(__METHOD__);
	}


}