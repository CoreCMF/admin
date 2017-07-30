<?php

namespace CoreCMF\admin\Validator;

use CoreCMF\core\Support\Validator\Rules as coreRules;
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
    public function editUser(){
        $name = "
            (rule, value, callback) => {
                if (value == undefined) {
                  callback('请输入用户名');
                } else {
                  ".$this->asyncField(route('api.admin.system.user.check'),'{
                    name:this.fromData.name,
                    id:this.fromData.id
                  }')."
                }
            }
        ";
        $email = "
            (rule, value, callback) => {
                if (value == undefined) {
                  callback('请输入邮箱地址');
                } else {
                  ".$this->asyncField(route('api.admin.system.user.check'),'{
                    email:this.fromData.email,
                    id:this.fromData.id
                  }')."
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
                      ".$this->asyncField(route('api.admin.system.user.check'),'{
                        mobile:this.fromData.mobile,
                        id:this.fromData.id
                      }')."
                    }
                }
            }
        ";
        $password = "
            (rule, value, callback) => {
                if (value == undefined) {
                  callback();
                } else {
                    if (!/^.{6,16}$/.test(value)) {
                        callback('密码长度请控制在 6 到 16 个字符');
                    }
                    if (this.fromData.checkPassword !== '') {
                        this.\$refs.bvefrom.validateField('checkPassword');
                    }
                  callback();
                }
            }";
        $checkPassword = "
                (rule, value, callback) => {
                    if (value !== this.fromData.password) {
                      callback('两次输入密码不一致!');
                    } else {
                      callback();
                    }
                }";
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
                [ 'validator'=> $password , 'trigger'=> 'blur']
            ],
            'checkPassword'=> [
                [ 'validator'=> $checkPassword, 'trigger'=> 'blur' ]
            ],
            'roles'=> [
              [ 'required' => true, 'type' => 'array', 'message' => '请至少选择一个用户角色', 'trigger' => 'change' ]
            ]
        ];
    }

}