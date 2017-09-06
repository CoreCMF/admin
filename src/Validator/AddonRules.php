<?php

namespace CoreCMF\Admin\Validator;

use CoreCMF\Core\Support\Validator\Rules as coreRules;
class AddonRules extends coreRules
{
    public function add(){
        return [
            'namespace'=> [
                ['required' => true,  'message' => '必须输入插件命名空间', 'trigger'=> 'blur'],
            ],
        ];
    }

}
