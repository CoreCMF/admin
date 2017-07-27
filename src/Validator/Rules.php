<?php

namespace CoreCMF\admin\Validator;

use CoreCMF\core\Support\Validator\Rules as coreRules;
class Rules extends coreRules
{
    public function getRules(){
        $mobilePassword = "
            (rule, value, callback) => {
                if (value === '') {
                  callback(new Error('请输入手机号码'));
                } else {
                    if (!/^1[3578]\d{9}$/.test(value)) {
                        callback(new Error('请输入正确的手机号码'));
                    }
                    callback();
                }
            }
        ";
        $validatePassword = "
            (rule, value, callback) => {
                if (value === '') {
                  callback();
                } else {
                    if (!/^.{6,16}$/.test(value)) {
                        callback(new Error('密码长度在 6 到 16 个字符'));
                    }
                    if (this.fromDatas.checkPassword !== '') {
                        this.\$refs.fromDatas.validateField('checkPassword');
                    }
                  callback();
                }
            }";
        $validateCheckPassword = "
            (rule, value, callback) => {
                if (value !== this.fromDatas.password) {
                  callback(new Error('两次输入密码不一致!'));
                } else {
                  callback();
                }
            }";
        return [
            'name'=> [
                ['required' => true,  'message' => '请输入用户名', 'trigger'=> 'blur'],
                [ 'min'=> 4, 'max'=> 10, 'message'=> '长度在 4 到 10 个字符', 'trigger'=> 'blur' ]
            ],
            'email'=> [
                [ 'required'=> true, 'message'=> '请输入邮箱地址', 'trigger'=> 'blur' ],
                [ 'type'=> 'email', 'message'=> '请输入正确的邮箱地址', 'trigger'=> 'blur,change' ]
            ],
            'mobile'=> [
                [ 'required'=> true, 'validator'=> $mobilePassword,'trigger'=> 'blur' ],
                // [ 'min'=> 11, 'max'=> 11, 'type'=> 'number', 'message'=> '请输入正确的手机号码', 'trigger'=> 'blur,change' ]
            ],
            'password'=> [
                [ 'validator'=> $validatePassword, 'trigger'=> 'blur']
            ],
            'checkPassword'=> [
                [ 'validator'=> $validateCheckPassword, 'trigger'=> 'blur', 'checkMessage'=>'两次输入密码不一致!' ]
            ],
            'avatar'=> [
                [ 'required'=> true, 'message'=> '请上传头像' ],
            ],
        ];
    }
}
