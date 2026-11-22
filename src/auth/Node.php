<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace EasyAdmin\auth;

use EasyAdmin\auth\AuthConfig;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnnotation;
use Mycm\Annotations\Scanner;

/**
 * 节点处理类
 *
 * 负责扫描控制器目录，通过 MyCm Annotations Scanner 读取注解元数据，
 * 并使用 AuthConfig 解析 auth 字段，输出节点权限列表。
 *
 * 使用示例：
 *   $node = new Node('/path/to/controller', 'app\admin\controller');
 *   $node->setCachePath(runtime_path() . 'scanner');
 *   $list = $node->getNodelist();
 */
class Node
{

	private string $dir;

	private string $namespacePrefix = '';

	private string $authFieldName = 'auth';

	private ?Scanner $scanner = null;

	/**
	 * Scanner 缓存目录路径，null 表示不启用
	 */
	private ?string $cachePath = null;

	/**
	 * 是否调试模式，true 时跳过缓存
	 */
	private bool $debug = false;

	/**
	 * rbac 未由注解显式声明时的兜底值
	 *
	 * 后端应用默认开启（与 CheckAdmin 中间件一致），前台公开接口默认关闭，
	 * 由调用方按场景显式指定，避免节点表 is_auth 与实际鉴权口径不一致。
	 */
	private bool $defaultRbac = false;

	/**
	 * @param string $baseDir           控制器目录绝对路径
	 * @param string $namespacePrefix    命名空间前缀，如 "app\admin\controller"
	 * @param string $annotationCacheDir 已废弃，保留兼容旧调用
	 */
	public function __construct(string $baseDir, string $namespacePrefix = '', string $annotationCacheDir = '')
	{
		$this->dir = $baseDir;
		$this->namespacePrefix = $namespacePrefix;
	}

	public function setDir(string $dir): static
	{
		$this->dir = $dir;
		return $this;
	}

	public function setNamespacePrefix(string $namespacePrefix): static
	{
		$this->namespacePrefix = $namespacePrefix;
		return $this;
	}

	/**
	 * 设置 Scanner 缓存目录
	 *
	 * 传入 TP8 runtime 路径如 runtime_path() . 'scanner'，
	 * 扫描结果将序列化缓存到该目录，生产环境减少全量反射开销。
	 */
	public function setCachePath(string $cachePath): static
	{
		$this->cachePath = $cachePath;
		return $this;
	}

	/**
	 * 设置调试模式（跳过缓存，强制全量扫描）
	 */
	public function setDebug(bool $debug): static
	{
		$this->debug = $debug;
		return $this;
	}

	/**
	 * 已废弃，保留兼容旧调用
	 */
	public function setAnnotationCacheDir(string $annotationCacheDir): static
	{
		return $this->setCachePath($annotationCacheDir);
	}

	/**
	 * 已废弃，保留兼容旧调用
	 */
	public function setAnnotationDebug(bool $annotationDebug = true): static
	{
		return $this->setDebug($annotationDebug);
	}

	/**
	 * 设置注解中鉴权字段名（默认 "auth"，MyCm 默认 "is_auth"）
	 */
	public function setAuthFieldName(string $authFieldName): static
	{
		$this->authFieldName = $authFieldName;
		return $this;
	}

	/**
	 * 设置注解未显式声明 rbac 时的默认值
	 */
	public function setDefaultRbac(bool $defaultRbac): static
	{
		$this->defaultRbac = $defaultRbac;
		return $this;
	}

	/**
	 * 获取所有节点
	 *
	 * 使用 AuthConfig 解析 rawAuth → is_auth(bool)，保持输出格式兼容旧版。
	 * 控制器级节点使用 $controller->rawAuth 而非硬编码 true。
	 */
	public function getNodelist(): array
	{
		$controllers = $this->getScanner()->scan($this->dir, $this->buildScanOptions());

		$nodeList = [];
		foreach ($controllers as $controller) {
			if (empty($controller->nodes)) {
				continue;
			}

			$ctrlAuthConfig = $this->resolveAuthConfig($controller->rawAuth);
			$nodeList[] = [
				'node'    => $controller->controllerNode,
				'title'   => $controller->title,
				'is_auth' => $ctrlAuthConfig->needRbac(),
				'type'    => 1,
			];

			foreach ($controller->nodes as $node) {
				$methodAuthConfig = $this->resolveAuthConfig($node->rawAuth);
				$nodeList[] = [
					'node'    => $node->getNode(),
					'title'   => $node->title,
					'is_auth' => $methodAuthConfig->needRbac(),
					'type'    => 2,
				];
			}
		}

		return $nodeList;
	}

	/**
	 * 解析注解 auth 值；rbac 未显式声明时使用 defaultRbac 兜底
	 *
	 * 与 CheckAdmin 中间件的判定口径保持一致，避免节点表 is_auth
	 * 与运行时实际鉴权出现两套结论
	 */
	private function resolveAuthConfig(mixed $rawAuth): AuthConfig
	{
		$config = AuthConfig::resolve($rawAuth);
		if ($config->rbacExplicit) {
			return $config;
		}
		return new AuthConfig(
			login: $config->login,
			rbac: $this->defaultRbac,
			rbacExplicit: false,
		);
	}

	/**
	 * 获取所有控制器
	 */
	public function getControllerList(): array
	{
		$controllers = $this->getScanner()->scan($this->dir, $this->buildScanOptions());

		$list = [];
		foreach ($controllers as $controller) {
			$list[$controller->controllerNode] = $controller->className;
		}
		return $list;
	}

	/**
	 * 构建 Scanner 扫描选项，包含缓存配置和 EasyAdmin 注解类
	 */
	private function buildScanOptions(): array
	{
		return [
			'namespace_prefix'           => $this->namespacePrefix,
			'controller_attribute_class' => ControllerAnnotation::class,
			'node_attribute_class'       => NodeAnnotation::class,
			'auth_field_name'            => $this->authFieldName,
			'cache_path'                 => $this->cachePath,
			'debug'                      => $this->debug,
		];
	}

	private function getScanner(): Scanner
	{
		if ($this->scanner === null) {
			$this->scanner = new Scanner();
		}
		return $this->scanner;
	}
}