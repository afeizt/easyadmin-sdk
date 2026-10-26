<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace EasyAdmin\auth;

use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnnotation;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * 认证配置值对象
 *
 * 将注解中 auth 字段的原始值（bool 或 array）解析为统一对象。
 * 本类属于 auth 认证层，不依赖注解基础设施，仅调用注解类读取原始值。
 *
 * 注解写法示例：
 *   #[ControllerAnnotation(title: '登录', auth: false)]                    // 公开接口
 *   #[ControllerAnnotation(title: '用户管理', auth: true)]                  // 需要登录
 *   #[NodeAnnotation(title: '删除', auth: ['login' => true, 'rbac' => true])] // 需要登录且校验RBAC节点权限
 *
 * 使用方式（中间件）：
 *   $cfg = AuthConfig::fromController($ctrlClass, $action);
 *   if ($cfg?->isPublic()) { ... }
 *   if ($cfg?->needRbac())  { checkNodePermission(...); }
 */
final readonly class AuthConfig
{
	public function __construct(
		public bool $login = true,
		public bool $rbac  = false,
		/** rbac 是否由注解显式声明（true=用户明确指定, false=使用默认值），供中间件决策是否启用默认 RBAC */
		public bool $rbacExplicit = false,
	) {}

	/**
	 * 从注解原始值解析为 AuthConfig
	 *
	 * - bool $auth            → AuthConfig(login: $auth, rbacExplicit: false)
	 * - array{login, rbac}    → AuthConfig(login: ..., rbac: ..., rbacExplicit: array_key_exists('rbac', $auth))
	 * - 其他类型              → AuthConfig() 默认需要登录
	 */
	public static function resolve(mixed $auth): self
	{
		if (is_bool($auth)) {
			return new self(login: $auth);
		}
		if (is_array($auth)) {
			return new self(
				login: $auth['login'] ?? true,
				rbac: $auth['rbac']  ?? false,
				rbacExplicit: array_key_exists('rbac', $auth),
			);
		}
		return new self();
	}

	/**
	 * 从控制器/方法注解反射读取 AuthConfig
	 *
	 * 查找优先级：方法级 NodeAnnotation > 类级 ControllerAnnotation。
	 * 未找到任何注解时返回 null，调用方可按旧逻辑兜底。
	 *
	 * @return self|null
	 */
	public static function fromController(string $controllerClass, string $action = ''): ?self
	{
		if ($action !== '') {
			try {
				$refMethod = new ReflectionMethod($controllerClass, $action);
				$attrs = $refMethod->getAttributes(NodeAnnotation::class);
				if (! empty($attrs)) {
					return self::resolve($attrs[0]->newInstance()->auth);
				}
			} catch (ReflectionException) {
				// 方法不存在时 fallback 到类级注解
			}
		}

		try {
			$refClass = new ReflectionClass($controllerClass);
			$attrs = $refClass->getAttributes(ControllerAnnotation::class);
			if (! empty($attrs)) {
				return self::resolve($attrs[0]->newInstance()->auth);
			}
		} catch (ReflectionException) {
		}

		return null;
	}

	/** 是否需要登录校验 */
	public function needLogin(): bool
	{
		return $this->login;
	}

	/**
	 * 是否需要 RBAC 节点权限校验
	 *
	 * 隐含 needLogin() = true（rbac 依赖登录态），调用前应先校验登录。
	 */
	public function needRbac(): bool
	{
		return $this->rbac;
	}

	/** 是否为公开接口（无需登录） */
	public function isPublic(): bool
	{
		return ! $this->login;
	}
}
