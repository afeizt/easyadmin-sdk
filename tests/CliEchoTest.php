<?php

/**
 * EasyAdmin SDK
 *
 * @author   afeizt@hotmail.com
 * @license  MIT https://mit-license.org
 * @link     https://github.com/afeizt/easyadmin-sdk
 */

namespace Test;

use EasyAdmin\console\CliEcho;
use PHPUnit\Framework\TestCase;

class CliEchoTest extends TestCase
{

	public function testInitColoredStringWithForeground()
	{
		$result = CliEcho::initColoredString('hello', 'green');
		$this->assertStringContainsString('hello', $result);
		$this->assertStringContainsString("\033[", $result);
		$this->assertStringContainsString('0;32', $result);
	}

	public function testInitColoredStringWithBackground()
	{
		$result = CliEcho::initColoredString('hello', null, 'red');
		$this->assertStringContainsString("\033[41m", $result);
	}

	public function testInitColoredStringWithBothColors()
	{
		$result = CliEcho::initColoredString('hello', 'white', 'blue');
		$this->assertStringContainsString('1;37', $result);
		$this->assertStringContainsString('44', $result);
		$this->assertStringContainsString("\033[0m", $result);
	}

	public function testInitColoredStringWithoutColors()
	{
		$result = CliEcho::initColoredString('hello');
		$this->assertStringContainsString('hello', $result);
		$this->assertStringEndsWith("\033[0m", $result);
	}

	public function testInitColoredStringWithInvalidColor()
	{
		$result = CliEcho::initColoredString('hello', 'nonexistent');
		$this->assertStringContainsString('hello', $result);
	}

	public function testGetColoredStringInstanceMethod()
	{
		$cli = new CliEcho();
		$result = $cli->getColoredString('test', 'green');
		$this->assertStringContainsString('test', $result);
		$this->assertStringContainsString("\033[", $result);
	}

	public function testGetColoredStringWithNewline()
	{
		$cli = new CliEcho();
		$result = $cli->getColoredString('test', 'green', null, true);
		$this->assertStringEndsWith(PHP_EOL, $result);
	}

	public function testGetColoredStringWithoutNewline()
	{
		$cli = new CliEcho();
		$result = $cli->getColoredString('test', 'green', null, false);
		$this->assertStringEndsNotWith(PHP_EOL, $result);
	}

	public function testGetForegroundColors()
	{
		$cli = new CliEcho();
		$colors = $cli->getForegroundColors();
		$this->assertIsArray($colors);
		$this->assertContains('green', $colors);
		$this->assertContains('red', $colors);
		$this->assertContains('blue', $colors);
	}

	public function testGetBackgroundColors()
	{
		$cli = new CliEcho();
		$colors = $cli->getBackgroundColors();
		$this->assertIsArray($colors);
		$this->assertContains('red', $colors);
		$this->assertContains('blue', $colors);
		$this->assertContains('green', $colors);
	}

	public function testNoticeContainsText()
	{
		$result = CliEcho::initColoredString('test notice', 'light_gray');
		$this->assertStringContainsString('test notice', $result);
	}

	public function testSuccessContainsText()
	{
		$result = CliEcho::initColoredString('test success', 'light_cyan');
		$this->assertStringContainsString('test success', $result);
	}

	public function testWarnContainsText()
	{
		$result = CliEcho::initColoredString('test warn', 'red', 'yellow');
		$this->assertStringContainsString('test warn', $result);
	}

	public function testErrorContainsText()
	{
		$result = CliEcho::initColoredString('test error', 'white', 'red');
		$this->assertStringContainsString('test error', $result);
	}
}
