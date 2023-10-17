<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 * @property Post $Post
 * @property PaginatorComponent $Paginator
 */
class PostsController extends AmpAppController {

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator');
	public $limit = 20;


	public function index($slug=null) {
		if($slug==null || empty($this->params['lang'])) throw new NotFoundException(__('Trang này không tồn tại',true));

		if(empty($this->params['ext'])){			//Điều hướng xem danh sách bài viết

			$this->_list($slug);
			$this->render('list');
		}else{ 										//Điều hướng xem chi tiết bài viết

			$this->_view($slug);
			$this->render('view');
		}
	}

	/**
	 * @Description : Danh sách bài viết
	 *
	 * @throws 	: NotFoundException
	 * @param 	: str $slug
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _list($slug){
		$this->set('class','list_post');
		$oneweb_post = Configure::read('Post');

		$a_params = $this->params;
		$lang = $a_params['lang'];

		//Đọc thông tin danh mục
		$a_category = Cache::read("post_category_$slug",'oneweb');
		if(!$a_category){
			$a_category = $this->Post->PostCategory->find('first',array(
				'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang,'slug'=>$slug,'or'=>array(array('link'=>null),array('link'=>''))),
				'fields'=>array('id','name','lang','banner','banner_link','slug','path','description','position','meta_title','meta_keyword','meta_description','meta_robots'),
				'recursive'=>-1
			));
			Cache::write("post_category_$slug",$a_category,'oneweb');
		}
		if(empty($a_category)) throw new NotFoundException(__('Trang này không tồn tại',true));
		$a_category = $a_category['PostCategory'];
		$a_ids = array($a_category['id']);		//Id của mục này và các mục con của nó


		//Tìm các danh mục con trực tiếp
		$a_child_direct_categories = $this->Post->PostCategory->find('all',array(
			'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang,'parent_id'=>$a_category['id']),
			'fields'=>array('id','name','slug','lang','path','meta_title','rel','target','link','image','status','position'),
			'order'=>array('lft'=>'asc','name'=>'asc'),
			'recursive'=>-1
		));

		//Tìm tất cả id danh mục con, bao gồm cả danh mục ko trực tiếp
		if(!empty($oneweb_post['post_child']) && !empty($a_child_direct_categories)){		//Tồn tại danh mục con

			//Tìm id của các danh mục con (bao gồm cả trực tiếp và ko trực tiếp)
			$a_child_categories = $this->Post->PostCategory->children($a_category['id'],false,array('id','status','trash'));
			foreach ($a_child_categories as $val){
				$item_cate = $val['PostCategory'];
				if($item_cate['status'] && !$item_cate['trash']) $a_ids[] = $item_cate['id'];
			}
		}

		$a_conditions2 = array('post_category_id'=>$a_ids);
		foreach($a_ids as $val){
			$a_conditions2[] = array('category_other like'=>'%-'.$val.'-%');
		}

		//Ngay hien tai
		$date_current = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
		//Danh sách bài viết
		$this->paginate = array(
			'conditions'=>array('Post.lang'=>$lang,'Post.status'=>1,'Post.trash'=>0,'or'=>$a_conditions2,'Post.public <='=>$date_current),
			'contain'=>array('PostCategory'),
			'fields'=>array('Post.id','Post.name','Post.lang','Post.slug','Post.meta_title','Post.rel','Post.target','Post.public','Post.image','Post.summary','Post.created',
							'PostCategory.slug','PostCategory.path','PostCategory.status','PostCategory.position'
						),
			'order'=>array('Post.sort'=>'asc','Post.created'=>'desc','Post.name'=>'asc'),
			'page'=>(!empty($a_params['page'])?$a_params['page']:'1'),
			'limit'=>$this->limit
		);
		$a_posts = $this->paginate();
		$this->set('a_posts_c',$a_posts);
		$this->set('a_category_c',$a_category);
		$this->set('a_child_direct_categories',$a_child_direct_categories);

		//Breadcrumb
		$this->set('a_breadcrumb_c',$this->_getBreadcrumbCategory($a_category['id']));

		//SEO
		$this->set('title_for_layout',$a_category['meta_title']);
		$this->set('meta_keyword_for_layout',$a_category['meta_keyword']);
		$this->set('meta_description_for_layout',$a_category['meta_description']);
		$this->set('meta_robots_for_layout',$a_category['meta_robots']);

	//Canonical
		$a_canonical = array('controller'=>'posts','action' => 'index','lang'=>$lang,'position'=>$a_category['position']);
				$tmp = explode(',', $a_category['path']);
				for($i=0;$i<count($tmp);$i++){
					$a_canonical = array_merge($a_canonical,array('slug'.$i=>$tmp[$i]));
				}
			 if(!empty($a_params['page']) && $a_params['page']>1) $a_canonical = array_merge($a_canonical,array('page'=>$a_params['page']));
		 if(!empty($a_params['sort']) && !empty($a_params['direction'])) $a_canonical = array_merge($a_canonical,array('sort'=>$a_params['sort'],'direction'=>$a_params['direction']));
		$this->set('a_canonical',$a_canonical);

		$origin_url = array('plugin'=>false, 'controller'=>'posts','action' => 'index','lang'=>$lang,'position'=>$a_category['position']);
				$tmp = explode(',', $a_category['path']);
				for($i=0;$i<count($tmp);$i++){
					$origin_url = array_merge($origin_url,array('slug'.$i=>$tmp[$i]));
				}
			$this->set('origin_url',$origin_url);
	}


	/**
	 * @Description : Chi tiết bài viết
	 *
	 * @throws 	: NotFoundException
	 * @param 	: str $slug
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _view($slug){
// 		$this->set('column_right',false);		//Thiết lập loại bỏ cột phải
		$this->set('class','detail_post');
		$this->set('active_slideshow',false);

		$lang = $this->params['lang'];

		//Đọc nội dung bài viết
		$a_post = Cache::read("post_view_$slug",'oneweb');
		//Ngay hien tai
		$date_current = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

		if(!$a_post){
			$this->Post->unbindModel(array(
				'belongsTo'=>array('User')
			));

			$a_post = $this->Post->find('first',array(
			'conditions'=>array('PostCategory.status'=>1,'PostCategory.trash'=>0,'Post.status'=>1,'Post.trash'=>0,'Post.slug'=>$slug,'Post.lang'=>$lang),
			'fields'=>array('Post.id','Post.name','Post.slug','Post.meta_title','Post.meta_keyword','Post.meta_description','Post.meta_robots','Post.description','Post.image','Post.tag','Post.star_rate','Post.star_rate_count','Post.created',
							'PostCategory.id','PostCategory.name','PostCategory.path','PostCategory.position'),
			'recursive'=>0
			));

			$a_post['Post']['tag'] = $this->_getSlugForTag($a_post['Post']['tag']);

			Cache::write("post_view_$slug",$a_post,'oneweb');
		}
		$a_ids = array($a_post['PostCategory']['id']);		//Id của danh mục này và các danh mục con của nó
		if(empty($a_post)) throw new NotFoundException(__('Trang này không tồn tại',true));
		//Tìm bài viết trước và sau bài viết hiện tại

		$a_nextprev_post['prev'] = $this->Post->find('first',array(
			'conditions'=>array('Post.post_category_id'=>$a_ids,'Post.id <'=>$a_post['Post']['id'],'PostCategory.status'=>1,'PostCategory.trash'=>0,'Post.status'=>1,'Post.trash'=>0,'Post.slug NOT'=>$slug,'Post.lang'=>$lang),
			'fields'=>array('Post.id','Post.name','Post.slug','Post.meta_title','Post.meta_keyword','Post.meta_description','Post.meta_robots','Post.description','Post.image','Post.tag','Post.star_rate','Post.lang','Post.created',
							'PostCategory.id','PostCategory.name','PostCategory.path','PostCategory.position'),
			'recursive'=>0
			));
		$a_nextprev_post['next'] = $this->Post->find('first',array(
				'conditions'=>array('Post.post_category_id'=>$a_ids,'Post.id >'=>$a_post['Post']['id'],'PostCategory.status'=>1,'PostCategory.trash'=>0,'Post.status'=>1,'Post.trash'=>0,'Post.slug NOT'=>$slug,'Post.lang'=>$lang),
				'fields'=>array('Post.id','Post.name','Post.slug','Post.meta_title','Post.meta_keyword','Post.meta_description','Post.meta_robots','Post.description','Post.image','Post.tag','Post.star_rate','Post.lang','Post.created',
						'PostCategory.id','PostCategory.name','PostCategory.path','PostCategory.position'),
				'recursive'=>0
		));
		$this->set('a_nextprev_post',$a_nextprev_post);
		//Tìm bài viết liên quan (Liên quan theo danh mục)
		$a_related_posts = Cache::read("post_view_relate_$slug",'oneweb');
		if(!$a_related_posts){

			$a_child_categories = $this->Post->PostCategory->children($a_post['PostCategory']['id'],false,array('id','status','trash'));
			foreach ($a_child_categories as $val){
				$item_cate = $val['PostCategory'];
				if($item_cate['status'] && !$item_cate['trash']) $a_ids[] = $item_cate['id'];
			}


			$a_related_posts = $this->Post->find('all',array(
				'conditions'=>array('post_category_id'=>$a_ids,'Post.status'=>1,'Post.trash'=>0,'PostCategory.status'=>1,'PostCategory.trash'=>0,'Post.lang'=>$lang,'Post.slug !='=>$slug,'Post.public <='=>$date_current),
				'fields'=>array('Post.id','Post.lang','Post.name','Post.slug','Post.meta_title','Post.rel','Post.image','Post.summary','Post.target','Post.created',
								'PostCategory.id','PostCategory.name','PostCategory.slug','PostCategory.meta_title','PostCategory.path,PostCategory.position',
								),
				'order'=>'Post.created desc',
				'limit'=>10,
				'recursive'=>0
			));
			Cache::write("post_view_relate_$slug",$a_related_posts,'oneweb');
		}

		$this->set('a_post_c',$a_post);
		$this->set('a_related_posts_c',$a_related_posts);

		//Tăng lượt xem
		$this->_increaseView($a_post['Post']['id']);

		//Breadcrumb
		$a_breadcrumb = Cache::read("post_view_breadcrumb_$slug",'oneweb');
		if(!$a_breadcrumb){
			$a_breadcrumb = $this->_getBreadcrumbCategory($a_post['PostCategory']['id']);
			$a_breadcrumb[] = array(
				'name'=>$a_post['Post']['name'],
				'meta_title'=>$a_post['Post']['meta_title'],
				'url'=>''
			);
			Cache::write("post_view_breadcrumb_$slug",$a_breadcrumb,'oneweb');
		}
		$this->set('a_breadcrumb_c',$a_breadcrumb);

		//SEO
		$this->set('title_for_layout',$a_post['Post']['meta_title']);
		$this->set('meta_keyword_for_layout',$a_post['Post']['meta_keyword']);
		$this->set('meta_description_for_layout',$a_post['Post']['meta_description']);
		$this->set('meta_robots_for_layout',$a_post['Post']['meta_robots']);

		//Canonical
		$a_canonical = array('controller'=>'posts','action' => 'index','lang'=>$lang,'position'=>$a_post['PostCategory']['position']);
				$tmp = explode(',', $a_post['PostCategory']['path']);
				for($i=0;$i<count($tmp);$i++){
					$a_canonical = array_merge($a_canonical,array('slug'.$i=>$tmp[$i]));
				}

				$a_canonical = array_merge($a_canonical,array('slug'.count($tmp)=>$a_post['Post']['slug'],'ext'=>'html'));

			$origin_url = array('plugin'=>false,'controller'=>'posts','action' => 'index','lang'=>$lang,'position'=>$a_post['PostCategory']['position']);
				$tmp = explode(',', $a_post['PostCategory']['path']);
				for($i=0;$i<count($tmp);$i++){
					$origin_url = array_merge($origin_url,array('slug'.$i=>$tmp[$i]));
				}

				$origin_url = array_merge($origin_url,array('slug'.count($tmp)=>$a_post['Post']['slug'],'ext'=>'html'));
		$this->set('a_canonical',$a_canonical);
		$this->set('origin_url',$origin_url);
	}

	private function _getBreadcrumbCategory($id){
		$lang = $this->params['lang'];
		$a_path = $this->Post->PostCategory->getPath($id,'id,name,slug,meta_title,path,parent_id,link,slug,status,position');

		$a_breadcrumb = array();
		foreach($a_path as $val){
			$item = $val['PostCategory'];

			if(empty($item['link'])){
				$url = array('controller'=>'posts','action' => 'index','lang'=>$lang, 'position' => $item['position']);
						$tmp = explode(',', $item['path']);
						for($i=0;$i<count($tmp);$i++){
							$url = array_merge($url,array('slug'.$i=>$tmp[$i]));
						}
			} else $url = $item['link'];

					$children = array();
			if($item['parent_id']!=null){
						$a_children = $this->Post->PostCategory->find('all',array(
					'conditions'=>array('parent_id'=>$item['parent_id'],'id !='=>$item['id'],'status'=>1,'trash'=>0,'lang'=>$lang),
					'fields'=>array('id','name','meta_title','slug','path','parent_id','link','position'),
					'order'=>'lft asc',
					'recursive'=>-1
				));

				foreach($a_children as $val2){
					$item2 = $val2['PostCategory'];

					if(empty($item2['link'])){
						$url2 = array('controller'=>'posts','action' => 'index','lang'=>$lang);
								$tmp2 = explode(',', $item2['path']);
								for($i=0;$i<count($tmp2);$i++){
									$url2 = array_merge($url2,array('slug'.$i=>$tmp2[$i]));
								}
					}else $url2 = $item2['link'];

							$children[] = array(
											'name'=>$item2['name'],
									'meta_title'=>$item2['meta_title'],
									'url'=>$url2
										 );
				}
			}

			$a_breadcrumb[] = array(
							'name'=>$item['name'],
							'meta_title'=>$item['meta_title'],
							'url'=>$url,
							'child'=>$children
						);
		}

		return $a_breadcrumb;
	}

}
