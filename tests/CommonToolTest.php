<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace Test;

use EasyAdmin\tool\CommonTool;
use PHPUnit\Framework\TestCase;

class CommonToolTest extends TestCase
{

	public function testLineToHump()
	{
		$this->assertEquals('userName', CommonTool::lineToHump('user_name'));
		$this->assertEquals('abcDefGhi', CommonTool::lineToHump('abc_def_ghi'));
		$this->assertEquals('helloWorld', CommonTool::lineToHump('hello-world'));
	}

	public function testLineToHumpAlreadyHump()
	{
		$this->assertEquals('UserName', CommonTool::lineToHump('UserName'));
	}

	public function testLineToHumpWithSingleWord()
	{
		$this->assertEquals('username', CommonTool::lineToHump('username'));
	}

	public function testHumpToLine()
	{
		$this->assertEquals('_user_name', CommonTool::humpToLine('UserName'));
		$this->assertEquals('_hello_world', CommonTool::humpToLine('HelloWorld'));
	}

	public function testHumpToLineAlreadyLine()
	{
		$this->assertEquals('user_name', CommonTool::humpToLine('user_name'));
	}

	public function testReplaceTemplate()
	{
		$result = CommonTool::replaceTemplate('Hello {{name}}', ['name' => 'World']);
		$this->assertEquals('Hello World', $result);
	}

	public function testReplaceTemplateMultipleVars()
	{
		$template = '{{greeting}} {{name}}, you are {{age}} years old';
		$vars = ['greeting' => 'Hello', 'name' => 'John', 'age' => '25'];
		$result = CommonTool::replaceTemplate($template, $vars);
		$this->assertEquals('Hello John, you are 25 years old', $result);
	}

	public function testReplaceTemplateNoPlaceholders()
	{
		$result = CommonTool::replaceTemplate('Plain text', ['key' => 'val']);
		$this->assertEquals('Plain text', $result);
	}

	public function testReadDirAllFiles()
	{
		$testDir = __DIR__ . DIRECTORY_SEPARATOR . '_test_dir';
		$subDir = $testDir . DIRECTORY_SEPARATOR . 'sub';

		mkdir($testDir, 0777, true);
		mkdir($subDir, 0777, true);
		file_put_contents($testDir . DIRECTORY_SEPARATOR . 'a.txt', 'a');
		file_put_contents($testDir . DIRECTORY_SEPARATOR . 'b.txt', 'b');
		file_put_contents($subDir . DIRECTORY_SEPARATOR . 'c.txt', 'c');

		$files = CommonTool::readDirAllFiles($testDir);

		$this->assertArrayHasKey('a.txt', $files);
		$this->assertArrayHasKey('b.txt', $files);
		$this->assertArrayHasKey('sub' . DIRECTORY_SEPARATOR . 'c.txt', $files);

		// Cleanup
		unlink($subDir . DIRECTORY_SEPARATOR . 'c.txt');
		unlink($testDir . DIRECTORY_SEPARATOR . 'a.txt');
		unlink($testDir . DIRECTORY_SEPARATOR . 'b.txt');
		rmdir($subDir);
		rmdir($testDir);
	}

	public function testGetRealIpWithRemoteAddr()
	{
		$_SERVER['REMOTE_ADDR'] = '192.168.1.1';
		$this->assertEquals('192.168.1.1', CommonTool::getRealIp());
	}
}
