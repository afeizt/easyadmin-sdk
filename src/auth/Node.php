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
use Mycm\Annotations\Scanner;

/**
 * 节点处理类
 */
class Node
{

	private string $dir;

	private string $namespacePrefix = '';

	private string $authFieldName = 'auth';

	private ?Scanner $scanner = null;

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
	 * 已废弃，保留兼容旧调用
	 */
	public function setAnnotationCacheDir(string $annotationCacheDir): static
	{
		return $this;
	}

	/**
	 * 已废弃，保留兼容旧调用
	 */
	public function setAnnotationDebug(bool $annotationDebug = true): static
	{
		return $this;
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
	 * 获取所有节点
	 */
	public function getNodelist(): array
	{
		$controllers = $this->getScanner()->scan($this->dir, $this->buildScanOptions());

		$nodeList = [];
		foreach ($controllers as $controller) {
			if (empty($controller->nodes)) {
				continue;
			}

			$nodeList[] = [
				'node'    => $controller->controllerNode,
				'title'   => $controller->title,
				'is_auth' => true,
				'type'    => 1,
			];

			foreach ($controller->nodes as $node) {
				$nodeList[] = [
					'node'    => $node->getNode(),
					'title'   => $node->title,
					'is_auth' => $node->isAuth,
					'type'    => 2,
				];
			}
		}

		return $nodeList;
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
	 * 构建 Scanner 扫描选项，使用 EasyAdmin 自己的注解类
	 */
	private function buildScanOptions(): array
	{
		return [
			'namespace_prefix'           => $this->namespacePrefix,
			'controller_attribute_class' => ControllerAnnotation::class,
			'node_attribute_class'       => NodeAnnotation::class,
			'auth_field_name'            => $this->authFieldName,
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
