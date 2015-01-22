<?php
namespace AppBundle\Twig;

class SurveyExtension extends \Twig_Extension
{
	
	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('input_radio', array($this, 'radio'), array("is_safe" => array("html")))
		);
	}
	
	
	public function radio($name, $value, $caption)
	{
		return "<input type='radio' id='$name' value='$value' />" .
		       "<label ref='%name'>$caption</label>";		
	}
	
	
	public function getName()
	{
		return 'survey_extension';
	}
}
