<?php
namespace Application\Constants;

class ImageConstants
{
	const EXTENSION = '.jpeg';
	const NO_IMAGE_FILENAME	= '/public/images/no-image/no-image';
	const BACKGROUND_COLOR = '#ffffff';
	const COMPRESSION_QUALITY_ORIGINAL = 80;
	const COMPRESSION_QUALITY_THUMBS = 80;
	const THUMB_SEPARATOR = '_';

	static public $THUMBS = array(
		'small' => array(
			'width'		=> 40,
			'height'	=> 40),
		'medium' => array(
			'width'		=> 100,
			'height'	=> 100),
		'large'		=> array(
			'width'		=> 150,
			'height'	=> 150),
		'giant'		=> array(
			'width'		=> 210,
			'height'	=> 210));
}