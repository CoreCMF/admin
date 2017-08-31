<?php

namespace CoreCMF\Admin\Validator;

use CoreCMF\Core\Support\Validator\Rules as coreRules;
class PermissionRules extends coreRules
{
    public function permission(){
        return [
            'name'=> [
                [ 'min'=> 2, 'max'=> 32, 'message'=> '长度在 2 到 32 个字符', 'trigger'=> 'blur' ],
                ['required' => true,  'message' => '请输入权限标识', 'trigger'=> 'blur'],
            ],
            'display_name'=> [
                [ 'min'=> 2, 'max'=> 32, 'message'=> '长度在 2 到 32 个字符', 'trigger'=> 'blur' ],
                ['required' => true,  'message' => '请输入权限名称', 'trigger'=> 'blur'],
            ],
            'description'=> [
              [ 'min'=> 2, 'max'=> 180, 'message'=> '长度在 2 到 180 个字符', 'trigger'=> 'blur' ],
              ['required' => true,  'message' => '请输入权限描述', 'trigger'=> 'blur'],
            ],
        ];
    }
}
