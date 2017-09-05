<?php

namespace CoreCMF\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Container\Container;

use App\Http\Controllers\Controller;
use CoreCMF\Admin\Models\Addon;
use CoreCMF\Admin\Validator\AddonRules;

class AddonController extends Controller
{
	/** @var userRepo */
    private $addonModel;
		private $container;
		private $rules;

    public function __construct(
			Addon $addonRepo,
			Container $container,
			AddonRules $rules
		)
    {
        $this->addonModel = $addonRepo;
				$this->container = $container;
				$this->rules = $rules;
    }
    public function index(Request $request)
    {
				dd('aa');
    }
}
