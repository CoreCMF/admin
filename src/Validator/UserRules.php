<?php

namespace CoreCMF\admin\Validator;

use CoreCMF\core\Support\Validator\Rules as coreRules;
class UserRules extends coreRules
{
    public function addUser(){
        $name = "
            (rule, value, callback) => {
                if (value == undefined) {
                  callback(new Error('请输入用户名'));
                } else {
                    if (!/^[\w\W]{4,10}$/.test(value)) {
                        callback(new Error('长度在 4 到 10 个字符'));
                    }else{
                      ".$this->asyncField(route('api.admin.system.user.check'),'name')."
                      callback();
                    }
                }
            }
        ";
        return [
            'name'=> [
                ['required' => true,  'validator' => $name, 'trigger'=> 'blur'],
            ],
            'email'=> [
                [ 'required'=> true, 'message'=> '请输入邮箱地址', 'trigger'=> 'blur' ],
                [ 'type'=> 'email', 'message'=> '请输入正确的邮箱地址', 'trigger'=> 'blur,change' ]
            ],
            'mobile'=> [
                [ 'required'=> true, 'validator'=> $this->mobile,'trigger'=> 'blur' ]
            ],
            'password'=> [
                [ 'required'=> true,  'validator'=> $this->password, 'trigger'=> 'blur']
            ],
            'checkPassword'=> [
                [ 'required'=> true,  'validator'=> $this->checkPassword, 'trigger'=> 'blur' ]
            ],
            'roles'=> [
              [ 'required' => true, 'type' => 'array', 'message' => '请至少选择一个用户角色', 'trigger' => 'change' ]
            ],
            'avatar'=> [
                [ 'required'=> true, 'message'=> '请上传头像' ],
            ],
        ];
    }
}
