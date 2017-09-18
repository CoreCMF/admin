<?php

namespace CoreCMF\Admin\Http\Validator;

use CoreCMF\Core\Support\Validator\Rules as coreRules;
class RoleRules extends coreRules
{
    public function addRole(){
        return [
            'name'=> [
                [ 'min'=> 2, 'max'=> 16, 'message'=> '长度在 2 到 16 个字符', 'trigger'=> 'blur' ],
                ['required' => true,  'message' => '请输入角色标识', 'trigger'=> 'blur'],
            ],
            'display_name'=> [
                [ 'min'=> 2, 'max'=> 16, 'message'=> '长度在 2 到 16 个字符', 'trigger'=> 'blur' ],
                ['required' => true,  'message' => '请输入角色名称', 'trigger'=> 'blur'],
            ],
            'description'=> [
              [ 'min'=> 2, 'max'=> 180, 'message'=> '长度在 2 到 180 个字符', 'trigger'=> 'blur' ],
              ['required' => true,  'message' => '请输入角色描述', 'trigger'=> 'blur'],
            ],
        ];
    }
}
