<?php
/*
	Copyright � Eleanor CMS
	URL: http://eleanor-cms.ru, http://eleanor-cms.com
	E-mail: support@eleanor-cms.ru
	Developing: Alexander Sunvas*
	Interface: Rumin Sergey
	=====
	*Pseudonym
*/
if(!defined('CMS'))die;

$event=isset($_POST['event']) ? (string)$_POST['event'] : '';
switch($event)
{
		$log=isset($_POST['log']) ? (string)$_POST['log'] : '';
		if(in_array($log,array('errors','db_errors','request_errors')))
		{
			$hlog=$log.'.inc';

			if(is_file($log) and is_file($hlog))
			{
				if(isset($info[$id]))
				{
					{
						Files::Delete($log);
						Files::Delete($hlog);
					}
					else
					{
						if(flock($fh,LOCK_EX))
						{
							$diff=Files::FReplace($fh,'',$info[$id]['o'],$info[$id]['l']+strlen(PHP_EOL)*2);
							foreach($info as &$v)
								if($v['o']>$info[$id]['o'])
									$v['o']+=$diff;
							flock($fh,LOCK_UN);
							fclose($fh);
							unset($info[$id]);
							file_put_contents($hlog,serialize($info));
						}
						else
						{
							Error();
							break;
					}

			}

			break;
		}
	default:
		Error(Eleanor::$Language['main']['unknown_event']);
}