<?php

namespace CoreCMF\admin\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
  public $table = 'admin_configs';

  public $fillable = [
      'title', 'name', 'value', 'group', 'type', 'icon', 'property', 'options', 'placeholder', 'sort', 'status'
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
      'title' => 'string',
      'name' => 'string',
      'value' => 'string'
  ];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
      'title' => 'required|string',
      'name' => 'required|string',
      'value' => 'required|string'
  ];

  /**
   * [getConfig 获取后台配置数据]
   * @param    [type]                   $name [配置常量名]
   * @return   [type]                         [description]
   */
  public function getConfig($name){
      $configData = $this->where('name','=', $name)->first();
      return $configData->value;
  }
  /**
   * [getTabsConfigGroupList 转换为Tabs]
   * @return   [type]                   [description]
   */
  public function tabsConfigGroupList(){
      return explode(',', $this->getConfig('CONFIG_GROUP_LIST'));
  }

  public function getValueAttribute($value)
  {
    if ($this->attributes['type'] == 'switch') {
        return (boolean)$value;
    }
    if ($this->attributes['type'] == 'number') {
        return (int)$value;
    }
    return $value;
  }
  protected $appends = ['imageUrl','uploadUrl','label','rows','options'];
  /**
   * [getImageUrlAttribute 获取上传图片网址]
   */
  public function getImageUrlAttribute()
  {
      if ($this->attributes['type'] == 'picture') {
          // $uploadObject = Helpers::getUploadWhereFirst($this->attributes['value']);
          // return $uploadObject->url;
      }
  }
  /**
   * [getUploadUrlAttribute 获取上传文件路径]
   */
  public function getUploadUrlAttribute()
  {
      if ($this->attributes['type'] == 'picture') {
          return '/api/admin/system/upload/image';
      }
  }
  public function getLabelAttribute()
  {
      return $this->attributes['title'];
  }
  public function getRowsAttribute()
  {
      if ($this->attributes['type'] == 'textarea') {
          return 5;
      }
  }
  /**
   * [getOptionsAttribute 获取分页选择显示数据]
   */
  public function getOptionsAttribute()
  {
      if ($this->attributes['name'] == 'ADMIN_PAGE_SIZE') {
          // return $configPageSizes= collect(Helpers::getPageSizes())
          //                             ->map(function ($value) {
          //                                 return $value.' 条/页';
          //                             });
      }
  }
}
