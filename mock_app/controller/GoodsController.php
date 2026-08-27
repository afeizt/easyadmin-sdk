<?php

namespace MockApp\controller;

use MockApp\BaseController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnnotation;


/**
 * Class GoodsController
 * @package MockApp\controller
 * @ControllerAnnotation(title="管理员管理")
 */
class GoodsController extends BaseController
{

	/**
	 * @NodeAnnotation(title="列表")
	 */
	public function index()
	{
		var_dump(__METHOD__);
	}

	/**
	 * @NodeAnnotation(title="修改")
	 */
	public function edit()
	{
		var_dump(__METHOD__);
	}

	/**
	 * @NodeAnnotation(title="详情")
	 */
	public function detail()
	{
		var_dump(__METHOD__);
	}

	/**
	 * @NodeAnnotation(title="新增")
	 */
	public function add()
	{
		var_dump(__METHOD__);
	}


}