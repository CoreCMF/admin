<?php

namespace CoreCMF\admin\Validator;

use CoreCMF\core\Support\Validator\Rules as coreRules;
class UserRules extends coreRules
{
    public function addUser(){
        $name = "
            (rule, value, callback) => {
                if (value == undefined) {
                  callback('请输入用户名');
                } else {
                  ".$this->asyncField(route('api.admin.system.user.check'),'name')."
                }
            }
        ";
        $email = "
            (rule, value, callback) => {
                if (value == undefined) {
                  callback('请输入邮箱地址');
                } else {
                  ".$this->asyncField(route('api.admin.system.user.check'),'email')."
                }
            }
        ";
        $mobile = "
            (rule, value, callback) => {
                if (value == undefined) {
                  callback('请输入手机号码');
                } else {
                    if (!/^1[3578]\d{9}$/.test(value)) {
                      callback('请输入正确的手机号码');
                    }else{
                      ".$this->asyncField(route('api.admin.system.user.check'),'mobile')."
                    }
                }
            }
        ";
        return [
            'name'=> [
                [ 'min'=> 4, 'max'=> 10, 'message'=> '长度在 4 到 10 个字符', 'trigger'=> 'blur' ],
                ['required' => true,  'validator' => $name, 'trigger'=> 'blur'],
            ],
            'email'=> [
                [ 'type'=> 'email', 'message'=> '请输入正确的邮箱地址', 'trigger'=> 'blur,change' ],
                [ 'required'=> true, 'validator'=> $email, 'trigger'=> 'blur' ]
            ],
            'mobile'=> [
                [ 'required'=> true, 'validator'=> $mobile,'trigger'=> 'blur' ]
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
