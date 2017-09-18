<?php

namespace CoreCMF\Admin\Http\Validator;

use CoreCMF\Core\Support\Validator\Rules as coreRules;
class ModelRules extends coreRules
{
    public function add(){
        return [
            'namespace'=> [
                ['required' => true,  'message' => '必须输入模块插件命名空间', 'trigger'=> 'blur'],
            ],
        ];
    }

}
