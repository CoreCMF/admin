<?php

namespace CoreCMF\admin\Validator;

use CoreCMF\core\Support\Validator\Rules as coreRules;
class ConfigRules extends coreRules
{
    public function index(){
        return [
            'name'=> [
                ['required' => true,  'message' => '请输入配置名称', 'trigger'=> 'blur']
            ],
            'title'=> [
                [ 'required'=> true, 'message'=> '请输入配置标题', 'trigger'=> 'blur' ]
            ],
        ];
    }
}
