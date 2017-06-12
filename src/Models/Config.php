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
}
