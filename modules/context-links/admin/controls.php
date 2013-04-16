<?php
/*
	Copyright © Eleanor CMS
	URL: http://eleanor-cms.ru, http://eleanor-cms.com
	E-mail: support@eleanor-cms.ru
	Developing: Alexander Sunvas*
	Interface: Rumin Sergey
	=====
	*Pseudonym. See paramss/copyrights/info.txt for more information.
*/
if(!defined('CMS'))die;

return array(
	'from'=>array(
		'title'=>$lang['from'],
		'descr'=>$lang['from_'],
		'type'=>'input',
		'bypost'=>&$Eleanor->sc_post,
		'multilang'=>Eleanor::$vars['multilang'],
		'options'=>array(
			'htmlsafe'=>false,
			'extra'=>array(
				'tabindex'=>1
			),
		),
	),
	'to'=>array(
		'title'=>$lang['to'],
		'descr'=>$lang['to_'],
		'type'=>'input',
		'bypost'=>&$Eleanor->sc_post,
		'multilang'=>Eleanor::$vars['multilang'],
		'options'=>array(
			'htmlsafe'=>false,
			'extra'=>array(
				'tabindex'=>3
			),
		),
	),
	'regexp'=>array(
		'title'=>$lang['reg'],
		'descr'=>$lang['reg_'],
		'type'=>'check',
		'bypost'=>&$Eleanor->sc_post,
		'multilang'=>Eleanor::$vars['multilang'],
		'save'=>function($a,$Obj,$controls) use ($lang)
		{
			if($a['multilang'])
			{
				foreach($a['value'] as $k=>&$v)
					if($v and $controls['from'][$k]!='')
					{
						Eleanor::$nolog=true;
						preg_replace($controls['from'][$k],'','text');
						Eleanor::$nolog=false;
						if(preg_last_error()!=PREG_NO_ERROR)
							$Obj->errors['REG_ERROR']=$lang['rege'];
					}
				return$a['value'];
			}
			if($a['value'] and $controls['from']!='')
			{
				Eleanor::$nolog=true;
				preg_replace($controls['from'],'','text');
				Eleanor::$nolog=false;
				if(preg_last_error()!=PREG_NO_ERROR)
					$Obj->errors['REG_ERROR']=$lang['rege'];
			}
			return$a['value'];
		},
		'options'=>array(
			'htmlsafe'=>false,
			'extra'=>array(
				'tabindex'=>2
			),
		),
	),
	'url'=>array(
		'title'=>$lang['url'],
		'descr'=>$lang['url_'],
		'type'=>'input',
		'bypost'=>&$Eleanor->sc_post,
		'multilang'=>Eleanor::$vars['multilang'],
		'options'=>array(
			'htmlsafe'=>false,
			'extra'=>array(
				'tabindex'=>4,
			),
		),
	),
	'eval_url'=>array(
		'title'=>$lang['eval_url'],
		'descr'=>$lang['eval_url_'],
		'type'=>'input',
		'bypost'=>&$Eleanor->sc_post,
		'multilang'=>Eleanor::$vars['multilang'],
		'options'=>array(
			'htmlsafe'=>false,
			'extra'=>array(
				'tabindex'=>5,
			),
		),
	),
	'params'=>array(
		'title'=>$lang['params'],
		'descr'=>$lang['params_'],
		'save'=>function($a)
		{
			if($a['multilang'])
			{
				foreach($a['value'] as &$v)
					$v=$v ? ' '.trim($v) : '';
				return$a['value'];
			}
			return$a['value'] ? ' '.trim($a['value']) : '';
		},
		'type'=>'input',
		'bypost'=>&$Eleanor->sc_post,
		'multilang'=>Eleanor::$vars['multilang'],
		'options'=>array(
			'htmlsafe'=>false,
			'extra'=>array(
				'tabindex'=>6,
			),
		),
	),
	'date_from'=>array(
		'title'=>$lang['date_from'],
		'descr'=>'',
		'type'=>'date',
		'load'=>'LoadDate',
		'bypost'=>&$Eleanor->sc_post,
		'options'=>array(
			'time'=>true,
			'extra'=>array(
				'tabindex'=>7,
			),
		),
	),
	'date_till'=>array(
		'title'=>$lang['date_till'],
		'descr'=>'',
		'type'=>'date',
		'load'=>'LoadDate',
		'bypost'=>&$Eleanor->sc_post,
		'options'=>array(
			'time'=>true,
			'extra'=>array(
				'tabindex'=>8,
			),
		),
	),
	'status'=>array(
		'title'=>$lang['activate'],
		'descr'=>'',
		'default'=>true,
		'type'=>'check',
		'bypost'=>&$Eleanor->sc_post,
		'options'=>array(
			'extra'=>array(
				'tabindex'=>9,
			),
		),
	),
);