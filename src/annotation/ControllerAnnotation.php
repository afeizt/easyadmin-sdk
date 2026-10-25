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

#[Attribute(Attribute::TARGET_CLASS)]
final class ControllerAnnotation
{
	public function __construct(
		public string $title = '',
		public bool   $auth  = true,
	) {}
}
