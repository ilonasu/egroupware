<?php
  /**************************************************************************\
  * phpGroupWare                                                             *
  * http://www.phpgroupware.org                                              *
  * --------------------------------------------                             *
  *  This program is free software; you can redistribute it and/or modify it *
  *  under the terms of the GNU General Public License as published by the   *
  *  Free Software Foundation; either version 2 of the License, or (at your  *
  *  option) any later version.                                              *
  \**************************************************************************/

  /* $Id$ */

	function add_col(&$tpl,$str)
	{
		$tpl->set_var('str',$str);
		$tpl->parse('header_column','head_col',True);
	}

	function add_image_ahref($link,$image,$alt)
	{
		global $GLOBALS;
		return '<a href="'.$link.'"><img src="'.$GLOBALS['phpgw']->common->image('calendar',$image).'" alt="'.$alt.'" border="0"></a>';
	}

	$refer = explode('.',$HTTP_GET_VARS['menuaction']);
	$referrer = $refer[2];

	$templates = Array(
		'head_tpl'	=> 'head.tpl',
		'form_button_dropdown'	=> 'form_button_dropdown.tpl',
		'form_button_script'	=> 'form_button_script.tpl'
	);
	$tpl->set_file($templates);
	$tpl->set_block('head_tpl','head','head');
	$tpl->set_block('head_tpl','head_col','head_col');
	$tpl->set_block('form_button_script','form_button');
	$tpl->set_var('cols',$cols);

	$today = date('Ymd',time());

	add_col($tpl,'  <td width="2%">&nbsp;</td>');

	add_col($tpl,'  <td width="2%">'.add_image_ahref($this->page('day','&date='.$today),'today.gif',lang('Today')).'</td>');

	add_col($tpl,'  <td width="2%" align="left">'.add_image_ahref($this->page('week','&date='.$today),'week.gif',lang('This week')).'</td>');

	add_col($tpl,'  <td width="2%" align="left">'.add_image_ahref($this->page('month','&date='.$today),'month.gif',lang('This month')).'</td>');

	add_col($tpl,'  <td width="2%" align="left">'.add_image_ahref($this->page('year','&date='.$today),'year.gif',lang('This Year')).'</td>');

	add_col($tpl,'  <td width="2%" align="left">'.add_image_ahref($this->page('planner','&date='.$today),'planner.gif',lang('Planner')).'</td>');

	add_col($tpl,'  <td width="2%" align="left">'.add_image_ahref($this->page('matrixselect'),'view.gif',lang('Daily Matrix View')).'</td>');

	$remainder = 63;
	if($this->bo->check_perms(PHPGW_ACL_PRIVATE))
	{
		$remainder -= 28;
		$hidden_vars = '<input type="hidden" name="from" value="'.$HTTP_GET_VARS['menuaction'].'">'."\n";
		if(isset($HTTP_GET_VARS['date']) && $HTTP_GET_VARS['date'])
		{
			$hidden_vars .= '    <input type="hidden" name="date" value="'.$HTP_GET_VARS['date'].'">'."\n";
		}
		$hidden_vars .= '    <input type="hidden" name="month" value="'.$this->bo->month.'">'."\n";
		$hidden_vars .= '    <input type="hidden" name="day" value="'.$this->bo->day.'">'."\n";
		$hidden_vars .= '    <input type="hidden" name="year" value="'.$this->bo->year.'">'."\n";
		if(isset($HTTP_POST_VARS['keywords']) && $HTTP_POST_VARS['keywords'])
		{
			$hidden_vars .= '    <input type="hidden" name="keywords" value="'.$HTTP_POST_VARS['keywords'].'">'."\n";
		}
		if(isset($HTTP_POST_VARS['matrixtype']) && $HTTP_POST_VARS['matrixtype'])
		{
			$hidden_vars .= '    <input type="hidden" name="matrixtype" value="'.$HTTP_POST_VARS['matrixtype'].'">'."\n";
		}
		if(isset($HTTP_POST_VARS['participants']) && $HTTP_POST_VARS['participants'])
		{
			for ($i=0;$i<count($HTTP_POST_VARS['participants']);$i++)
			{
				$hidden_vars .= '    <input type="hidden" name="participants[]" value="'.$HTTP_POST_VARS['participants'][$i].'">'."\n";
			}
		}
		if($this->debug) { echo 'Filter = ('.$this->bo->filter.")<br>\n"; }
		$form_options = '<option value=" all "'.($this->bo->filter==' all '?' selected':'').'>'.lang('All').'</option>'."\n";
		$form_options .= '     <option value=" private "'.((!isset($this->bo->filter) || !$this->bo->filter) || $this->bo->filter==' private '?' selected':'').'>'.lang('Private Only').'</option>'."\n";
		
		$var = Array(
			'form_width' => '28',
			'form_link'	=> $this->page($referrer),
			'form_name'	=> 'filter',
			'title'	=> lang('Filter'),
			'hidden_vars'	=> $hidden_vars,
			'form_options'	=> $form_options,
			'button_value'	=> lang('Go!')
		);
		$tpl->set_var($var);
		$tpl->set_var('str',$tpl->fp('out','form_button_dropdown'));
		$tpl->parse('header_column','head_col',True);
	}

	if(count($this->bo->grants) > 0)
	{
		$hidden_vars = '    <input type="hidden" name="from" value="'.$HTTP_GET_VARS['menuaction'].'">'."\n";
		if(isset($HTTP_GET_VARS['date']) && $HTTP_GET_VARS['date'])
		{
			$hidden_vars .= '    <input type="hidden" name="date" value="'.$HTTP_GET_VARS['date'].'">'."\n";
		}
		$hidden_vars .= '    <input type="hidden" name="month" value="'.$this->bo->month.'">'."\n";
		$hidden_vars .= '    <input type="hidden" name="day" value="'.$this->bo->day.'">'."\n";
		$hidden_vars .= '    <input type="hidden" name="year" value="'.$this->bo->year.'">'."\n";
		if(isset($HTTP_POST_VARS['keywords']) && $HTTP_POST_VARS['keywords'])
		{
			$hidden_vars .= '    <input type="hidden" name="keywords" value="'.$HTTP_POST_VARS['keywords'].'">'."\n";
		}
		if(isset($HTTP_GET_VARS['cal_id']) && $HTTP_GET_VARS['cal_id'] != 0)
		{
			$hidden_vars .= '    <input type="hidden" name="cal_id" value="'.$HTTP_GET_VARS['cal_id'].'">'."\n";
		}
		$form_options = '';
		reset($this->bo->grants);
		while(list($grantor,$temp_rights) = each($this->bo->grants))
		{
			$form_options .= '    <option value="'.$grantor.'"'.($grantor==$this->bo->owner?' selected':'').'>'.$GLOBALS['phpgw']->common->grab_owner_name($grantor).'</option>'."\n";
      }
		reset($this->bo->grants);
		
		$var = Array(
			'form_width' => $remainder,
			'form_link'	=> $this->page($referrer),
			'form_name'	=> 'owner',
			'title'	=> lang('User'),
			'hidden_vars'	=> $hidden_vars,
			'form_options'	=> $form_options,
			'button_value'	=> lang('Go!')
		);
		$tpl->set_var($var);
		$tpl->set_var('str',$tpl->fp('out','form_button_dropdown'));
		$tpl->parse('header_column','head_col',True);
	}

	$hidden_vars = '    <input type="hidden" name="from" value="'.$HTTP_GET_VARS['menuaction'].'">'."\n";
	if(isset($HTTP_GET_VARS['date']) && $HTTP_GET_VARS['date'])
	{
		$hidden_vars .= '    <input type="hidden" name="date" value="'.$HTTP_GET_VARS['date'].'">'."\n";
	}
	$hidden_vars .= '    <input type="hidden" name="month" value="'.$this->so->month.'">'."\n";
	$hidden_vars .= '    <input type="hidden" name="day" value="'.$this->so->day.'">'."\n";
	$hidden_vars .= '    <input type="hidden" name="year" value="'.$this->so->year.'">'."\n";
	if(isset($this->bo->filter) && $this->bo->filter)
	{
		$hidden_vars .= '    <input type="hidden" name="filter" value="'.$this->bo->filter.'">'."\n";
	}
	$hidden_vars .= '    <input name="keywords"'.($HTTP_POST_VARS['keywords']?' value="'.$HTTP_POST_VARS['keywords'].'"':'').'>';

	$var = Array(
		'action_url_button'	=> $this->page('search'),
		'action_text_button'	=> lang('Search'),
		'action_confirm_button'	=> '',
		'action_extra_field'	=> $hidden_vars
	);
	$tpl->set_var($var);
	$button = $tpl->fp('out','form_button');
	$tpl->set_var('str','<td align="right" valign="bottom">'.$button.'</td>');
	$tpl->parse('header_column','head_col',True);
?>
