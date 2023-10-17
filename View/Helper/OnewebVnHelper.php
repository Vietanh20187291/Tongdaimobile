<?php
class OnewebVnHelper extends AppHelper {
	var $helpers = array('Html','Text');
	var $tab=" ";

	/**
	 * @Description : Danh muc sản phẩm sidebar
	 *
	 *
	 * @param 	: array $data
	 * @param	: int $level
	 * @return 	: string
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function productCategory($data,$level){
		$tabs = "\n" . str_repeat($this->tab, $level * 2);
		$li_tabs = $tabs . $this->tab;
		$output = $tabs . "<ul class='nav'>";

		foreach ($data as $key => $val) {
			$item_cate = $val['ProductCategory'];

			if (empty($item_cate['link'])){
				$url = array('controller'=>'products','action' => 'index','lang'=>$item_cate['lang']);
				$tmp = explode(',', $item_cate['path']);
				$url = array_merge($url,array('slug0'=>$tmp[count($tmp)-1]));
			} else $url = $item_cate['link'];

			$link_attr = array('title'=>$item_cate['meta_title'],'target'=>$item_cate['target'],'escape'=>false);
			if ($item_cate['rel']!='dofollow') $link_attr['rel'] = $item_cate['rel'];

			$current = false;
			$controller = $this->params['controller'];
			$action = $this->params['action'];

			if($controller=='products' && $action=='index'){
				$get_url = explode('/', $this->request->url);
				if(in_array($item_cate['slug'], $get_url)) $current = true;
			}

			if (isset($val['children'][0])) {
				$sub_menu = 'submenu';
				$caret = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
			} else {
				$caret = '';
				$sub_menu = '';
			}

			$output .= $li_tabs
					.(($current)?'<li class="current '.$sub_menu.'">':'<li class="'.$sub_menu.'">')
					. $this->Html->link($this->Text->truncate($item_cate['name'], 40,array('exact'=>false)).$caret, $url,$link_attr);

			if (isset($val['children'][0])) {
				$output .= $this->productCategory($val['children'], $level + 1);
				$output .= $li_tabs . "</li>";
			} else {
				$output .= "</li>";
			}
		}
		$output .= $tabs . "</ul>";
		return $output;
	}

	public function productCategoryNav($data,$level){
		$tabs = "\n" . str_repeat($this->tab, $level * 2);
		$li_tabs = $tabs . $this->tab;
		$output = $tabs . "<ul class='dropdown-menu'>";

		foreach ($data as $key => $val) {
			$item_cate = $val['ProductCategory'];

			if(empty($item_cate['link'])){
				$url = array('controller'=>'products','action' => 'index','lang'=>$item_cate['lang']);
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
				$url = array('controller'=>'products','action' => 'index','lang'=>$item_cate['lang']);
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
				$sub_menu = 'dropdown auto_dropdown clearfix showing-more';
				$caret = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
				$btn_show = '<div class="submenu-caret-wrapper"><span class="caret"></span></div>';
			} else {
				$caret = '';
				$sub_menu = 'clearfix showing-more';
				$btn_show = '';

			}
			$icon = '';
			if(!empty($item_cate['icon'])) $icon = $this->thumb('product_categories/'.$item_cate['icon'], array('width' => 30, 'height' => 30, 'zc' => 2));

			$output .= $li_tabs
					.(($current)?'<li class="current '.$sub_menu.'">':'<li class="'.$sub_menu.'">')
					. $this->Html->link($icon.$item_cate['name'].$caret, $url,$link_attr).$btn_show;

			if ( ! empty($item_cate['banner']) && isset($val['children'][0])) {
				if ( ! empty($item_cate['banner_link'])) {
					$output .= $this->Html->link($this->thumb('product_categories/'.$item_cate['banner'], array('width' => 400, 'height' => 502, 'zc' => 2, 'class'=>'img_banner')), $item_cate['banner_link'], array('escape' => false, 'class' => 'link-banner'));
				} else {
					$output .= $this->thumb('product_categories/'.$item_cate['banner'], array('width' => 400, 'height' => 502, 'zc' => 2));
				}
			}
			if (isset($val['children'][0])) {
				$output .= '<div class="white-bg"></div>';
				$output .= '<div class="title-parent">'.$item_cate['name'].'</div>';
				if($level == 0) $output .= '<div class="submenu-caret-wrapper"><span class="caret"></span></div>';
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


	/**
	 * @Description : Danh mục bài viết trên menu
	 *
	 * @throws 	: NotFoundException
	 * @param 	: array $data, $position
	 * @param	: boolean $submenu
	 * @return 	: string
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function postCategoryMenu($data,$submenu=true){ //chưa sử dụng
		$output = '';
		foreach($data as $val){
			$item_cate = $val['PostCategory'];
			if(in_array($item_cate['position'], $position)) {
				if(empty($item_cate['link'])) {
					$url = array('controller'=>'posts','action' => 'index','lang'=>$item_cate['lang']);

					$tmp = explode(',', $item_cate['path']);
					for ($i=0;$i<count($tmp);$i++) {
						$url = array_merge($url,array('slug'.$i=>$tmp[$i]));
					}
				} else $url = $item_cate['link'];

				$link_attr = array('title'=>$item_cate['meta_title'],'target'=>$item_cate['target'],'escape'=>false);
				if ($item_cate['rel']!='dofollow') $link_attr['rel'] = $item_cate['rel'];

				$current = false;
				if ($this->params['controller']=='posts' && $this->params['position']==$item_cate['position']) $current = true;

				$output .=(($current)?'<li class="current">':'<li>')
								. $this->Html->link($this->Html->tag('span',$item_cate['name']), $url,$link_attr);

				//Sub menu
				if($submenu && $val['children']) $output.=$this->postCategory($val['children'], 1);
				else $output.='<ul></ul>';

				$output.='</li>';
			}
		}

		return $output;
	}


	/**
	 * @Description : Danh mục bài viết
	 *
	 * @param 	: array $data
	 * @param	: int $level
	 * @return 	: string
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function postCategory($data,$level){
		$tabs = "\n" . str_repeat($this->tab, $level * 2);
		$li_tabs = $tabs . $this->tab;
		$output = $tabs . "<ul class='nav'>";

		foreach ($data as $key => $val) {
			$item_cate = $val['PostCategory'];

			if (empty($item_cate['link'])) {
				$url = array('controller'=>'posts','action' => 'index','lang'=>$item_cate['lang']);
				$tmp = explode(',', $item_cate['path']);
				for($i=0;$i<count($tmp);$i++){
					$url = array_merge($url,array('slug'.$i=>$tmp[$i]));
				}
			} else $url = $item_cate['link'];

			$link_attr = array('title'=>$item_cate['meta_title'],'target'=>$item_cate['target']);
			if ($item_cate['rel']!='dofollow') $link_attr['rel'] = $item_cate['rel'];

			$current = false;
			if($this->params['controller']=='posts'){
				$get_url = explode('/', $this->request->url);
				if(in_array($item_cate['slug'], $get_url)) $current = true;
			}

			$output .= $li_tabs
					.(($current)?'<li class="current">':'<li>')
					. $this->Html->link($item_cate['name'], $url,$link_attr);

			if (isset($val['children'][0])) {
				$output .= $this->postCategory($val['children'], $level + 1);
				$output .= $li_tabs . "</li>";
			} else {
				$output .= "</li>";
			}
		}
		$output .= $tabs . "</ul>";
		return $output;
	}

	/**
	 * @Description : Lấy link page thông tin
	 *
	 * @throws 	: NotFoundException
	 * @param 	: array $data, $position
	 * @param   : boolean $sub, $span
	 * @return 	: string
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
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
			else $url = array('controller'=>'information','action'=>'view','lang'=>$item['lang'],'position'=>$item['position'],'slug'=>$item['slug']);

			$link_attr = array('title'=>$item['meta_title'],'target'=>$item['target'],'escape'=>false);
			if($item['rel']!='dofollow') $link_attr['rel'] = $item['rel'];
			$current = '';
			$controller = $this->params['controller'];
			if($controller == 'information' && $this->params['slug'] == $item['slug']) $current = 'current';

			if(!empty($val['ChildInformation'])) {
				$str.='<li class="dropdown">';
				$caret = true;
			}else {
				$str.='<li class="'.$current.'">';
				$caret = false;
			}
			$str.= $this->Html->link((($caret)?$item['name'].$this->Html->tag('span class="caret"'):$item['name']),$url,$link_attr);

			if($sub && $val['ChildInformation']){
				$str.='<ul class="dropdown-menu">';
				foreach($val['ChildInformation'] as $val2){
					if(!empty($val2['link'])) $url_child= $val2['link'];
					else $url_child = array('controller'=>'information','action'=>'view','lang'=>$val2['lang'],'position'=>$val2['position'],'slug'=>$val2['slug']);

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
			else $url = array('controller'=>'information','action'=>'view','lang'=>$item['lang'],'position'=>$item['position'],'slug'=>$item['slug']);

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
					else $url = array('controller'=>'information','action'=>'view','lang'=>$child['lang'],'position'=>$child['position'],'slug'=>$child['slug'],'ext' => 'html');

					$link_attr = array('class'=>'dropdown-item','escape'=>false);
					if ( ! empty($child['meta_title'])) $link_attr = array_merge($link_attr,array('title'=>$child['meta_title']));
					if ( ! empty($child['target'])) $link_attr = array_merge($link_attr,array('target'=>$child['target']));
					if ( ! empty($child['rel']) && $child['rel']!='dofollow') $link_attr = array_merge($link_attr,array('rel'=>$child['rel']));
					if ( ! empty($child['name'])) $str.= '<li>'.$this->Html->link($child['name'],$url,$link_attr).'</li>';
				}

				$str .= '</ul>';

			} else {
				$url = array('controller'=>'information','action'=>'view','lang'=>$item['lang'],'position'=>$item['position'],'slug'=>$item['slug']);
				$link_attr = array('class'=>'nav-link','escape'=>false);
				if ( ! empty($item['meta_title'])) $link_attr = array_merge($link_attr,array('title'=>$item['meta_title']));
				if ( ! empty($item['target'])) $link_attr = array_merge($link_attr,array('target'=>$item['target']));
				if ( ! empty($item['rel']) && $item['rel']!='dofollow') $link_attr = array_merge($link_attr,array('rel'=>$item['rel']));
				$str.= $this->Html->link($item['name'],$url,$link_attr);
			}
		}
		return $str;
	}

	/**
	 * @Description : Lấy slug
	 *
	 * @param 	: string $str
	 * @return 	: string
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function getSlug($str){
		$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
		$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
		$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
		$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
		$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
		$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
		$str = preg_replace("/(đ)/", 'd', $str);

		$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
		$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
		$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
		$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
		$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
		$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
		$str = preg_replace("/(Đ)/", 'D', $str);
		//$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
		$str = strtolower($str);	//chuyen doi ve in thuong

		return Inflector::slug($str,'-');
	}


	/**
	 * @Description : Tạo ảnh Thumbnail:
	 * 				  Chú ý: Chỉ tạo được ảnh thumbnail trong các thư mục con trực tiếp của: img/images/
	 *
	 * @param 	: string $path   (Tính từ thư mục img/images làm gốc: vd ta truyền dạng như sau: products/image1.jpg)
	 * @param	: array $options
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function thumb($path,$options = array()){
		if(empty($options['zc'])) $options['zc'] = 2;

		$link = $this->linkImage($path,$options);
		$tmp = array();
		foreach($options as $key=>$val){
			if($key!='zc' && $key!='noimage') $tmp[$key] = $val;
		}
		$options = $tmp;
		return $this->Html->image($path,$options);
	}


	/**
	 * @Description : Lấy đường dẫn ảnh - Chú ý: Đường dẫn này phải phù hợp với quy tắc rewrite trong file htaccess
	 *
	 * @throws 	: NotFoundException
	 * @param 	: string $path
	 * @param	: array $options
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function linkImage($path,$options=array()){
	
		$path = explode('/', $path);

		$str = '';
		if($path[1]!=null){
			$len = strlen($path[1]);
			$ext = explode('.', $path[1]);
			$ext = $ext[count($ext)-1];
			$name = substr($path[1], 0, $len-(strlen($ext)+1));
			$str = $path[0].'/'.$name.'-'.$options['width'].'x'.$options['height'].'-'.$options['zc'].'.'.$ext;
		}
		return $str;
	}

	/**
	 * @Description : Lấy URL hiện tại
	 *
	 * @return 	: string
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function curPageURL() {
		$pageURL = 'http';
		if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on"){
			$pageURL .= "s";
		}
		$pageURL .= "://";
		if($_SERVER["SERVER_PORT"] != "80"){
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}else{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}

	public function capitalFirstLetterVietnamese($string, $encoding='utf8')
	{
		$strlen = mb_strlen($string, $encoding);
		$firstChar = mb_substr($string, 0, 1, $encoding);
		$then = mb_substr($string, 1, $strlen - 1, $encoding);
		return mb_strtoupper($firstChar, 'utf8').mb_strtolower($then, 'utf8');
	}

	public function rawText($string = '') {
		return trim(strip_tags($string));
	}

	// Xoá các dấu cách và chấm trong số điện thoại
	public function rawPhone($string) {
		$string = str_replace(' ', '', $string);
		$string = str_replace('.', '', $string);
		return $string;
	}
}
?>
