<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace Test\Tool;

use EasyAdmin\tool\CommonTool;
use PHPUnit\Framework\TestCase;

class CommonToolTest extends TestCase
{
	public function testLineToHump(): void
	{
		$this->assertSame('helloWorld', CommonTool::lineToHump('hello_world'));
		$this->assertSame('myName', CommonTool::lineToHump('my-name'));
		$this->assertSame('helloWorldAgain', CommonTool::lineToHump('hello_world_again'));
	}

	public function testHumpToLine(): void
	{
		$this->assertSame('hello_world', CommonTool::humpToLine('helloWorld'));
		$this->assertSame('_my_name', CommonTool::humpToLine('MyName'));
		$this->assertSame('hello_world_again', CommonTool::humpToLine('helloWorldAgain'));
	}

	public function testGetRealIp(): void
	{
		$ip = CommonTool::getRealIp();
		$this->assertIsString($ip);
		$this->assertNotEmpty($ip);
	}

	public function testReadDirAllFiles(): void
	{
		$tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'easyadmin_test_' . uniqid();
		mkdir($tempDir . '/subdir', 0777, true);
		file_put_contents($tempDir . '/test1.txt', 'content1');
		file_put_contents($tempDir . '/test2.txt', 'content2');
		file_put_contents($tempDir . '/subdir/test3.txt', 'content3');

		$files = CommonTool::readDirAllFiles($tempDir);

		$this->assertIsArray($files);
		$this->assertCount(3, $files);
		$this->assertArrayHasKey('test1.txt', $files);
		$this->assertArrayHasKey('subdir' . DIRECTORY_SEPARATOR . 'test3.txt', $files);

		unlink($tempDir . '/test1.txt');
		unlink($tempDir . '/test2.txt');
		unlink($tempDir . '/subdir/test3.txt');
		rmdir($tempDir . '/subdir');
		rmdir($tempDir);
	}

	public function testReplaceTemplate(): void
	{
		$result = CommonTool::replaceTemplate('Hello {{name}}', ['name' => 'World']);
		$this->assertSame('Hello World', $result);

		$result = CommonTool::replaceTemplate('{{greeting}} {{name}}', [
			'greeting' => 'Hi',
			'name' => 'Jack',
		]);
		$this->assertSame('Hi Jack', $result);
	}

	public function testReplaceTemplateNoPlaceholders(): void
	{
		$result = CommonTool::replaceTemplate('No placeholders here', ['key' => 'val']);
		$this->assertSame('No placeholders here', $result);
	}
}
