<?php
/*
	Copyright © Eleanor CMS
	URL: http://eleanor-cms.ru, http://eleanor-cms.com
	E-mail: support@eleanor-cms.ru
	Developing: Alexander Sunvas*
	Interface: Rumin Sergey
	=====
	*Pseudonym
*/

function ArchiveDays($y,$m,$conf,$mname,$dates=false)
{global$Eleanor;
	$t=time();
	if(!checkdate($m,1,$y) or $t<=$rd=mktime(0,0,0,$m,1,$y))
	{
		list($y,$m)=explode('-',date('Y-n'));
		$rd=$t;#Received date
	}
	$data=Eleanor::$Cache->Get($conf['n'].'_archivedates_'.$y.$m);
	if($data===false)
	{
		$data=array('dates'=>array());
		if($dates)
			$data+=$dates;
		else
		{
			$R=Eleanor::$Db->Query('SELECT MIN(`date`) `min`,MAX(`date`) `max` FROM `'.$conf['t'].'` WHERE `status`=1 AND `pinned`=\'0000-00-00 00:00:00\'');
			$a=$R->fetch_assoc();

			$R=Eleanor::$Db->Query('SELECT MIN(`pinned`) `min`,MAX(`pinned`) `max` FROM `'.$conf['t'].'` WHERE `status`=1 AND `pinned`>\'0000-00-00 00:00:00\'');
			$b=$R->fetch_assoc();
			if(isset($b['min'],$b['max']))
			{
				$R=Eleanor::$Db->Query('SELECT LEAST(\''.$a['min'].'\',\''.$b['min'].'\') `min`,GREATEST(\''.$a['max'].'\',\''.$b['max'].'\') `max`');
				$data+=$R->fetch_assoc();
			}
			else
				$data+=$a;
		}
		if($data['min'])
		{
			#Поскольку мы можем запросить август 2012го, но минимальная новость датирована лишь 20м августа, сбрасываем "минимальный" день с 20го на 1й
			if(!is_int($data['min']))
				$data['min']=strtotime(substr($data['min'],0,8).'01');
			#Аналогично и с максимальной датой, только сбос идет "в конец" месяца
			if(!is_int($data['max']))
				$data['max']=strtotime(substr($data['max'],0,8).idate('t',strtotime($data['max'])).' 23:59:59');

			if($data['min']>$rd)
				return ArchiveDays(idate('Y',$data['min']),idate('m',$data['min']),$conf,$mname,array('min'=>$data['min'],'max'=>$data['max']));
			if($data['max']<$rd)
				return ArchiveDays(idate('Y',$data['max']),idate('m',$data['max']),$conf,$mname,array('min'=>$data['min'],'max'=>$data['max']));

			$R=Eleanor::$Db->Query('SELECT IF(`pinned`=\'0000-00-00 00:00:00\',`date`,`pinned`) `date`, COUNT(`id`) `cnt` FROM `'.$conf['t'].'` WHERE IF(`pinned`=\'0000-00-00 00:00:00\',`date`,`pinned`) LIKE \''.$y.'-'.sprintf('%02d',$m).'%\' AND `status`=1 GROUP BY DAY(IF(`pinned`=\'0000-00-00 00:00:00\',`date`,`pinned`)) LIMIT 31');
			while($a=$R->fetch_row())
				$data['dates'][str_replace('-','',substr($a[0],0,10))]=$a[1];
		}
		Eleanor::$Cache->Put($conf['n'].'_archivedates_'.$y.$m,$data,3600);
	}

	$m2=sprintf('%02d',$m);
	$calendar=Dates::BuildCalendar($y,$m,false);
	foreach($calendar as &$week)
		foreach($week as $k=>&$day)
			if($day)
			{
				$day=sprintf('%02d',$day);
				if(isset($data['dates'][$y.$m2.$day]))
					$day=array(
						'day'=>$day,
						'cnt'=>$data['dates'][$y.$m2.$day],
						'a'=>$Eleanor->Url->special.$Eleanor->Url->Construct(array('module'=>$mname,'do'=>$y.'-'.$m2.'-'.$day),false,''),
					);
			}

	$pm=$nm=$m;
	$py=$y-1;
	$ny=$y+1;
	if($nm==12)
	{
		$ny++;
		$nm=1;
	}
	else
		$nm++;

	if($pm==1)
	{
		$py--;
		$pm=12;
	}
	else
		$pm--;

	if(mktime(0,0,0,$pm,1,$pm==12 ? $py : $y)<$data['min'])
		$pm=$py=false;
	elseif(mktime(0,0,0,idate('m'),$pm,$py)<$data['min'])
		$py=false;

	if(mktime(0,0,0,$nm,1,$nm==1 ? $ny : $y)>$data['max'])
		$nm=$ny=false;
	elseif(mktime(0,0,0,idate('m'),$nm,$ny)>$data['max'])
		$ny=false;

	return array(
		'calendar'=>$calendar,
		'y'=>$y,
		'm'=>$m,
		'pm'=>$pm,
		'nm'=>$nm,
		'py'=>$py,
		'ny'=>$ny,
		'a'=>$Eleanor->Url->special.$Eleanor->Url->Construct(array('module'=>$mname,'do'=>$Eleanor->Url->Filter($y.'-'.$m2)),false,''),
	);
}