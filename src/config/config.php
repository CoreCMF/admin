<?php
return [
	'name' => 'admin',
	'description' => 'corecmf admin package',
	'author' => 'bigRocs',

	'top' => [
		'title' => '系统',
		'icon' => 'fa fa-adjust',
	],
	'sidebar' => route('api.admin.nav.sidebar'),
];