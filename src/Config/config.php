<?php
return [
	'name' => 'admin',
	'description' => 'corecmf admin package',
	'author' => 'bigRocs',

	'topNav' => [
		'name'	=> 'admin',
		'title' => '系统',
		'icon' => 'fa fa-cog',
		'sort' => 0,
	],
	'skipCheck' => [
		'api.admin.nav.top',
		'api.admin.nav.sidebar'
	]
];
