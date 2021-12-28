<?php



namespace EasyAdmin\upload\driver;

use EasyAdmin\upload\FileBase;
use EasyAdmin\upload\trigger\SaveDb;

/**
 * 本地上传
 * Class Local.
 */
class Local extends FileBase
{
    /**
     * 重写上传方法.
     *
     * @return array|void
     */
    public function save()
    {
        parent::save();
        SaveDb::trigger($this->tableName, [
            'upload_type' => $this->uploadType,
            'original_name' => $this->file->getOriginalName(),
            'mime_type' => $this->file->getOriginalMime(),
            'file_ext' => strtolower($this->file->getOriginalExtension()),
            'url' => $this->completeFileUrl,
            'thumb_url' => $this->completeThumbFileUrl,
            'create_time' => time(),
        ]);

        return [
            'save' => true,
            'msg' => '上传成功',
            'url' => $this->completeFileUrl,
            'thumb_url' => $this->completeThumbFileUrl,
        ];
    }
}
