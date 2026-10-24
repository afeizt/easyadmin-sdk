# EasyAdmin SDK

基于 PHP 注解的权限节点管理与文件上传工具包，适用于 ThinkPHP 8 及以上版本。

## 安装

```bash
composer require mycm/easyadmin-sdk
```

## 模块概览

| 模块 | 命名空间 | 说明 |
|------|----------|------|
| 注解 | `EasyAdmin\annotation` | 控制器与方法的权限注解类 |
| 权限 | `EasyAdmin\auth` | 控制器扫描与权限节点生成 |
| 控制台 | `EasyAdmin\console` | 命令行彩色输出工具 |
| 工具 | `EasyAdmin\tool` | 通用工具方法集合 |
| 上传 | `EasyAdmin\upload` | 多驱动文件上传（本地 / 阿里云 / 七牛 / 腾讯云） |

---

## 1. 注解模块 (`EasyAdmin\annotation`)

### ControllerAnnotation

控制器注解，用于标记控制器类，定义控制器标题和权限控制。

```php
use EasyAdmin\annotation\ControllerAnnotation;

/**
 * @ControllerAnnotation(title="商品管理", auth=true)
 */
class GoodsController
{
    // ...
}
```

| 属性 | 类型 | 说明 |
|------|------|------|
| `title` | `string` | 控制器标题（必填） |
| `auth` | `bool` | 是否开启权限控制，默认 `true` |

### NodeAnnotation

节点注解，用于标记控制器方法，定义节点名称和权限控制。

```php
use EasyAdmin\annotation\NodeAnnotation;

/**
 * @NodeAnnotation(title="商品列表")
 */
public function index()
{
    // ...
}
```

| 属性 | 类型 | 说明 |
|------|------|------|
| `title` | `string` | 节点名称（必填） |
| `auth` | `bool` | 是否开启权限控制，默认 `true` |

---

## 2. 权限节点模块 (`EasyAdmin\auth`)

### Node

扫描控制器目录，解析控制器和方法的注解，生成完整权限节点列表。

```php
use EasyAdmin\auth\Node;

$node = new Node(
    $baseDir,              // 控制器所在目录
    $annotationCacheDir,   // 注解缓存目录
    'app\admin\controller' // 命名空间前缀
);

$nodeList = $node->getNodelist();
```

返回格式：

```php
[
    ['node' => 'goods',         'title' => '商品管理', 'is_auth' => true,  'type' => 1], // 控制器节点
    ['node' => 'goods/index',   'title' => '商品列表', 'is_auth' => true,  'type' => 2], // 方法节点
    ['node' => 'goods/add',     'title' => '添加商品', 'is_auth' => false, 'type' => 2],
]
```

| 方法 | 说明 |
|------|------|
| `setDir(string $dir)` | 设置控制器目录 |
| `setNamespacePrefix(string $prefix)` | 设置命名空间前缀 |
| `setAnnotationCacheDir(string $dir)` | 设置注解缓存目录 |
| `setAnnotationDebug(bool $debug)` | 设置 debug 模式 |
| `getNodelist()` | 获取所有权限节点 |
| `getControllerList()` | 获取所有控制器列表 |

---

## 3. 控制台模块 (`EasyAdmin\console`)

### CliEcho

命令行彩色输出工具，支持多种前景色和背景色。

```php
use EasyAdmin\console\CliEcho;

CliEcho::success('操作成功');
CliEcho::error('操作失败');
CliEcho::warn('警告信息');
CliEcho::notice('提示信息');

// 自定义颜色
echo CliEcho::initColoredString('自定义文字', 'green', 'light_gray');
```

**前景色**：`black` / `dark_gray` / `blue` / `light_blue` / `green` / `light_green` / `cyan` / `light_cyan` / `red` / `light_red` / `purple` / `light_purple` / `brown` / `yellow` / `light_gray` / `white`

**背景色**：`black` / `red` / `green` / `yellow` / `blue` / `magenta` / `cyan` / `light_gray`

---

## 4. 工具模块 (`EasyAdmin\tool`)

### CommonTool

通用工具方法集合。

```php
use EasyAdmin\tool\CommonTool;

// 下划线转驼峰
CommonTool::lineToHump('user_name');       // 'UserName'

// 驼峰转下划线
CommonTool::humpToLine('UserName');        // 'user_name'

// 获取真实客户端 IP
$ip = CommonTool::getRealIp();

// 递归读取目录下所有文件
$files = CommonTool::readDirAllFiles('/path/to/dir');

// 模板变量替换
$result = CommonTool::replaceTemplate('Hello {{name}}', ['name' => 'World']);
// 'Hello World'
```

---

## 5. 上传模块 (`EasyAdmin\upload`)

支持 **本地 / 阿里云 OSS / 七牛云 / 腾讯云 COS** 四种上传方式，内置图片缩略图和水印功能。

### 基本用法

```php
use EasyAdmin\upload\Uploadfile;

$upload = Uploadfile::instance()
    ->setUploadType('local')              // local / alioss / qnoss / txcos
    ->setUploadConfig($uploadConfig)
    ->setFile($file)
    ->setTableName('system_uploadfile')
    ->save();
```

### 配置项

可通过 `setUploadConfig()` 传入以下配置数组：

```php
$uploadConfig = [
    // 通用
    'upload_type'           => 'local',
    'upload_full_path'      => false,
    'upload_custom_domain'  => '',

    // 缩略图
    'image_thumb'           => false,
    'image_thumb_width'     => 500,
    'image_thumb_height'    => 500,

    // 水印
    'add_watermark'         => false,
    'watermark_type'        => 'text',     // text / image
    'watermark_text'        => 'My Watermark',
    'watermark_text_size'   => 50,
    'watermark_image'       => '/path/to/watermark.png',

    // 阿里云 OSS
    'alioss_access_key_id'  => '',
    'alioss_access_key_secret' => '',
    'alioss_endpoint'       => '',
    'alioss_bucket'         => '',
    'alioss_domain'         => '',

    // 七牛云
    'qnoss_access_key'      => '',
    'qnoss_secret_key'      => '',
    'qnoss_bucket'          => '',
    'qnoss_domain'          => '',

    // 腾讯云 COS
    'txcos_secret_id'       => '',
    'txcos_secret_key'      => '',
    'txcos_region'          => '',
    'txcos_bucket'          => '',
];
```

### 上传驱动

| 驱动 | 命名空间 | 说明 |
|------|----------|------|
| Local | `EasyAdmin\upload\driver\Local` | 本地上传 |
| Alioss | `EasyAdmin\upload\driver\Alioss` | 阿里云 OSS |
| Qnoss | `EasyAdmin\upload\driver\Qnoss` | 七牛云 |
| Txcos | `EasyAdmin\upload\driver\Txcos` | 腾讯云 COS |

### 扩展自定义驱动

实现 `EasyAdmin\upload\interfaces\OssDriver` 接口即可扩展新的云存储驱动：

```php
use EasyAdmin\upload\interfaces\OssDriver;

class MyDriver implements OssDriver
{
    public function save($objectName, $filePath)
    {
        // 实现上传逻辑
        return [
            'save' => true,
            'msg'  => '上传成功',
            'url'  => 'https://...',
        ];
    }
}
```

### 文件上传保存触发

上传成功后会通过 `EasyAdmin\upload\trigger\SaveDb` 自动将文件信息保存到数据库，默认表名为 `system_uploadfile`，可通过 `setTableName()` 自定义。

### 类关系

```
Uploadfile              # 上传入口，选择驱动
  +-- FileBase          # 上传基类（图片处理、缩略图、水印）
  |     +-- Local       # 本地上传
  |     +-- Alioss      # 阿里云（调用 alioss/Oss）
  |     +-- Qnoss       # 七牛云（调用 qnoss/Oss）
  |     +-- Txcos       # 腾讯云（调用 txcos/Cos）

OssDriver (interface)  -->  alioss/Oss  |  qnoss/Oss  |  txcos/Cos
SaveDb                 -->  保存记录到数据库
```

---

## 测试

```bash
composer test
```

## License

MIT License.