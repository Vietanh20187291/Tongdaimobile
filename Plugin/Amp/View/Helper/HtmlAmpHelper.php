<?php
class HtmlAmpHelper extends HtmlHelper{
	var $helpers = array('Html','Text');

	protected $_tags = array(
			'amp_image'=>'<amp-img src="%s" %s></amp-img>',
			'javascriptlink' => '<script src="%s"%s></script>',
			);

	public function amp_image($path, $options = array()) {
		$path = $this->assetUrl($path, $options + array('pathPrefix' => Configure::read('App.imageBaseUrl')));
		$options = array_diff_key($options, array('fullBase' => null, 'pathPrefix' => null));

		if (!isset($options['alt'])) {
			$options['alt'] = '';
		}

		$url = false;
		if (!empty($options['url'])) {
			$url = $options['url'];
			unset($options['url']);
		}

		$image = sprintf($this->_tags['amp_image'], $path, $this->_parseAttributes($options, null, '', ' '));

		if ($url) {
			return sprintf($this->_tags['link'], $this->url($url), null, $image);
		}
		return $image;
	}

	public function thumbAmp($path, $option = array()){
		return $this->amp_image('images/'.$path,$option);
	}


	public function productCategoryNav($data,$level){
		$tabs = "\n" . str_repeat($this->tab, $level * 2);
		$li_tabs = $tabs . $this->tab;
		$output = $tabs . "<ul class='dropdown-menu'>";

		foreach ($data as $key => $val) {
			$item_cate = $val['ProductCategory'];

			if(empty($item_cate['link'])){
				$url = array('plugin'=>false,'controller'=>'products','action' => 'index','lang'=>$item_cate['lang']);
				$tmp = explode(',', $item_cate['path']);
				$url = array_merge($url,array('slug0'=>$tmp[count($tmp)-1]));
			}else $url = $item_cate['link'];

			$link_attr = array('title'=>$item_cate['meta_title'],'target'=>$item_cate['target'],'escape'=>false);
			if ($item_cate['rel']!='dofollow') $link_attr['rel'] = $item_cate['rel'];

			$current = false;
			$controller = $this->params['controller'];
			$action = $this->params['action'];

			if ($controller=='products' && $action=='index'){
				$get_url = explode('/', $this->request->url);
				if (in_array($item_cate['slug'], $get_url)) $current = true;
			}

			if (isset($val['children'][0])) {
				$sub_menu = 'dropdown auto_dropdown clearfix';
				$caret = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
			} else {
				$caret = '';
				$sub_menu = 'clearfix';
			}

			$output .= $li_tabs
					.(($current)?'<li class="current '.$sub_menu.'">':'<li class="'.$sub_menu.'">')
					. $this->Html->link($item_cate['name'].$caret, $url,$link_attr);

			if (isset($val['children'][0])) {
					$output .= $this->productCategoryNav($val['children'], $level + 1);
					$output .= $li_tabs . "</li>";
				} else {
					$output .= "</li>";
				}
		}

		if ($level == 0) $output .= $tabs . '<li class="show-more"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></li></ul>';
		else $output .= $tabs . '</ul>';
		return $output;
	}

	public function productCategoryNavBanner($data,$level){
		$tabs = "\n" . str_repeat($this->tab, $level * 2);
		$li_tabs = $tabs . $this->tab;
		$output = $tabs . "<ul class='dropdown-menu'>";

		foreach ($data as $key => $val) {
			$item_cate = $val['ProductCategory'];

			if (empty($item_cate['link'])){
				$url = array('plugin'=>false, 'controller'=>'products','action' => 'index','lang'=>$item_cate['lang']);
				$tmp = explode(',', $item_cate['path']);
				$url = array_merge($url,array('slug0'=>$tmp[count($tmp)-1]));
			} else $url = $item_cate['link'];

			$link_attr = array('title'=>$item_cate['meta_title'],'target'=>$item_cate['target'],'escape'=>false);
			if ($item_cate['rel']!='dofollow') $link_attr['rel'] = $item_cate['rel'];

			$current = false;
			$controller = $this->params['controller'];
			$action = $this->params['action'];

			if ($controller=='products' && $action=='index'){
				$get_url = explode('/', $this->request->url);
				if (in_array($item_cate['slug'], $get_url)) $current = true;
			}

			if (isset($val['children'][0])) {
				$sub_menu = 'dropdown auto_dropdown clearfix';
				$caret = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
			} else {
				$caret = '';
				$sub_menu = 'clearfix';
			}

			$output .= $li_tabs
					.(($current)?'<li class="current '.$sub_menu.'">':'<li class="'.$sub_menu.'">')
					. $this->Html->link($item_cate['name'].$caret, $url,$link_attr);

			if ( ! empty($item_cate['banner']) && isset($val['children'][0])) {
				if ( ! empty($item_cate['banner_link'])) {
					$output .= $this->Html->link($this->thumbAmp('product_categories/'.$item_cate['banner'], array('width' => 400, 'height' => 502,  'layout' => 'responsive')), $item_cate['banner_link'], array('escape' => false, 'class' => 'link-banner'));
				} else {
					$output .= $this->thumbAmp('product_categories/'.$item_cate['banner'], array('width' => 400, 'height' => 502, 'layout' => 'responsive'));
				}
			}
			if (isset($val['children'][0])) {
				$output .= '<div class="white-bg"></div>';
				$output .= '<div class="title-parent">'.$item_cate['name'].'</div>';
				$output .= $this->productCategoryNavBanner($val['children'], $level + 1);
				$output .= $li_tabs . "</li>";
			} else {
				$output .= "</li>";
			}
		}

		if ($level == 0) $output .= $tabs . '<li class="show-more"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></li></ul>';
		else $output .= $tabs . '</ul>';
		return $output;
	}

public function linkInformation($data,$position,$sub=false){
		$str = '';
		$tmp = array();
		$caret = false;
		foreach($data as $val){
			$item = $val['Information'];
			if(in_array($item['position'], $position)) $tmp[] = $val;
		}
		$data = $tmp;

		foreach ($data as $val){
			$item = $val['Information'];

			if(!empty($item['link'])) $url= $item['link'];
			else $url = array('plugin'=>false,'controller'=>'information','action'=>'view','lang'=>$item['lang'],'position'=>$item['position'],'slug'=>$item['slug']);

			$link_attr = array('title'=>$item['meta_title'],'target'=>$item['target'],'escape'=>false);
			if($item['rel']!='dofollow') $link_attr['rel'] = $item['rel'];

			if(!empty($val['ChildInformation'])) {
				$str.='<li class="dropdown">';
				$caret = true;
			}else {
				$str.='<li>';
				$caret = false;
			}
			$str.= $this->Html->link((($caret)?$item['name'].$this->Html->tag('span class="caret"'):$item['name']),$url,$link_attr);

			if($sub && $val['ChildInformation']){
				$str.='<ul class="dropdown-menu">';
				foreach($val['ChildInformation'] as $val2){
					if(!empty($val2['link'])) $url_child= $val2['link'];
					else $url_child = array('plugin'=>false,'controller'=>'information','action'=>'view','lang'=>$val2['lang'],'position'=>$val2['position'],'slug'=>$val2['slug'],'ext'=>'html');

					$link_child_attr = array('title'=>$val2['meta_title'],'target'=>$val2['target']);
					if($val2['rel']!='dofollow') $link_child_attr['rel'] = $val2['rel'];

					$str.='<li>'
						.$this->Html->link($val2['name'],$url_child,$link_child_attr)
						.'</li>';
				}
				$str.='</ul>';
			}else $str.='';
			$str.='</li>';
		}

		return $str;
	}
	public function linkInformationTop($data,$position,$sub=false){
		$str = '';
		$tmp = array();
		$caret = false;
		foreach($data as $val){
			$item = $val['Information'];
			if(in_array($item['position'], $position)) $tmp[] = $val;
		}
		$data = $tmp;

		foreach ($data as $val){
			$item = $val['Information'];

			if(!empty($item['link'])) $url= $item['link'];
			else $url = array('plugin'=>false, 'controller'=>'information','action'=>'view','lang'=>$item['lang'],'position'=>$item['position'],'slug'=>$item['slug']);

			$link_attr = array('title'=>$item['meta_title'],'target'=>$item['target'],'class'=>'','escape'=>false);
			if($item['rel']!='dofollow') $link_attr['rel'] = $item['rel'];
			$str.= $this->Html->link((($caret)?$item['name'].$this->Html->tag('span class="caret"'):$item['name']),$url,$link_attr);

		}

		return $str;
	}

	public function linkInformationTopDropdown($data,$position,$sub=false){
		$str = '';
		$tmp = array();
		foreach($data as $val){
			$item = $val['Information'];
			if(in_array($item['position'], $position)) $tmp[] = $val;
		}
		$data = $tmp;

		foreach ($data as $val){
			$item = $val['Information'];
			$children = $val['ChildInformation'];

			if ( ! empty($children)) {
				$str .= '<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu'.$item["position"].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					'.$item["name"].'<span class="fa fa-angle-down"></span></button>'
					.'<ul class="dropdown-menu" aria-labelledby="dropdownMenu'.$item["position"].'">';

				foreach ($children as $child) {
					$url = '#';
					if ( ! empty($child['link'])) $url = $child['link'];
					else $url = array('plugin'=>false,'controller'=>'information','action'=>'view','lang'=>$child['lang'],'position'=>$child['position'],'slug'=>$child['slug']);

					$link_attr = array('class'=>'dropdown-item','escape'=>false);
					if ( ! empty($child['meta_title'])) $link_attr = array_merge($link_attr,array('title'=>$child['meta_title']));
					if ( ! empty($child['target'])) $link_attr = array_merge($link_attr,array('target'=>$child['target']));
					if ( ! empty($child['rel']) && $child['rel']!='dofollow') $link_attr = array_merge($link_attr,array('rel'=>$child['rel']));
					if ( ! empty($child['name'])) $str.= '<li>'.$this->Html->link($child['name'],$url,$link_attr).'</li>';
				}

				$str .= '</ul>';

			} else {
				$str.= $item['name'];
			}
		}

		return $str;
	}
}
?>
