<?php

class PanelImage extends Eloquent 
{
	protected $table = 'panel_image';

	public function panel()
	{
		return $this->belongsTo('panel');
	}
}
