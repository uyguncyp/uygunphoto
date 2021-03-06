<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class PanelImage extends Eloquent 
{
	protected $table = 'panel_image';

	public function panel()
	{
		return $this->belongsTo('panel');
	}

	public function image()
	{
		return $this->belongsTo('image');
	}
}
