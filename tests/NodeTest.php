<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace Test;

use EasyAdmin\auth\Node;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
	private string $controllersDir;

	protected function setUp(): void
	{
		$this->controllersDir = __DIR__ . '/Fixtures/Controllers';
	}

	public function testGetNodelist(): void
	{
		$node = new Node(
			baseDir: $this->controllersDir,
			namespacePrefix: 'Test\\Fixtures\\Controllers'
		);
		$list = $node->getNodelist();

		$this->assertNotEmpty($list);
		$this->assertIsArray($list);

		foreach ($list as $item) {
			$this->assertArrayHasKey('node', $item);
			$this->assertArrayHasKey('title', $item);
			$this->assertArrayHasKey('is_auth', $item);
			$this->assertArrayHasKey('type', $item);
		}
	}

	public function testGetNodelistType1ControllerNode(): void
	{
		$node = new Node(
			baseDir: $this->controllersDir,
			namespacePrefix: 'Test\\Fixtures\\Controllers'
		);
		$list = $node->getNodelist();

		$controllerNodes = array_filter($list, fn($item) => $item['type'] === 1);
		$this->assertNotEmpty($controllerNodes);

		$methodNodes = array_filter($list, fn($item) => $item['type'] === 2);
		$this->assertNotEmpty($methodNodes);
	}

	public function testGetControllerList(): void
	{
		$node = new Node(
			baseDir: $this->controllersDir,
			namespacePrefix: 'Test\\Fixtures\\Controllers'
		);
		$controllers = $node->getControllerList();

		$this->assertNotEmpty($controllers);
		$this->assertIsArray($controllers);
	}

	public function testSetDir(): void
	{
		$node = new Node('', namespacePrefix: 'Test\\Fixtures\\Controllers');
		$node->setDir($this->controllersDir);
		$list = $node->getNodelist();

		$this->assertNotEmpty($list);
	}

	public function testSetNamespacePrefix(): void
	{
		$node = new Node($this->controllersDir);
		$node->setNamespacePrefix('Test\\Fixtures\\Controllers');
		$list = $node->getNodelist();

		$this->assertNotEmpty($list);
	}

	public function testBackwardCompatibleCacheMethods(): void
	{
		$node = new Node($this->controllersDir, '/old/cache/dir', 'Test\\Fixtures\\Controllers');
		$node->setAnnotationCacheDir('/new/cache/dir');
		$node->setAnnotationDebug(false);
		$list = $node->getNodelist();

		$this->assertNotEmpty($list);
	}

	public function testEmptyDirectory(): void
	{
		$emptyDir = __DIR__ . '/Fixtures/EmptyDir';
		if (!is_dir($emptyDir)) {
			mkdir($emptyDir, 0777, true);
		}

		$node = new Node($emptyDir);
		$list = $node->getNodelist();

		$this->assertEmpty($list);

		rmdir($emptyDir);
	}
}
