<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends CI_Model 
{
	public function get_datas($table)
	{
	    $estore_avision = $this->load->database('estore_avision', TRUE);
		$query=$estore_avision->get($table);
		
		return $query->result();
	}
  public function get_passVal()
  {
	$estore_avision = $this->load->database('estore_avision', TRUE);
  	$course=$estore_avision->query('select * from courses')->result_array();
  	
  	$subCourseDetails=array();

  	foreach($course as $courseDetails)
  	{
  		if($courseDetails['courses_id']=='1')
  		{
  			$subCourseDetails[]=$estore_avision->query("select * from `courses` inner join sub_courses on courses.courses_id=sub_courses.courses_id inner join sub_category on sub_courses.sub_courses_id=sub_category.sub_courses_id where courses.courses_id =".$courseDetails['courses_id'])->result_array();
  			
  		}
  	}
   // echo "<pre>" ;
  	//print_r($subCourseDetails);

  	//die();
  	$result=$estore_avision->query('select * from `courses` inner join sub_courses on courses.courses_id=sub_courses.courses_id inner join sub_category on sub_courses.sub_courses_id=sub_category.sub_courses_id  where courses.courses_id IN (1,2,3,4,5,6,7,8)')->result();

  	echo "<pre>";
  	print_r($result);
  	
  }
	

   public function getTestSeriesByCategories($courseId)
   {
	$estore_avision = $this->load->database('estore_avision', TRUE);	
   	  $result_product=$estore_avision->query("select * from `courses` inner join sub_courses on courses.courses_id=sub_courses.courses_id inner join sub_category on sub_courses.sub_courses_id=sub_category.sub_courses_id inner join product on product.sub_cat_id=sub_category.sub_category_id inner join course_type_section on course_type_section.section_id = product.section_id  where courses.courses_id='$courseId'
   	       and product.product_type='0'")->result_array();
	    
		foreach ($result_product as $productDetails) {

			$estore_avision->select('*');
			$estore_avision->from('test_assign ta');
			$estore_avision->join('quiz_name q','q.quiz_id = ta.quiz_id');
			$estore_avision->where('ta.product_id',$productDetails['product_id']);
			$estore_avision->where('q.question_year_stat',1);
			$result_full_test = $estore_avision->get()->result_array();

			$estore_avision->select('*');
			$estore_avision->from('test_assign ta');
			$estore_avision->join('quiz_name q','q.quiz_id = ta.quiz_id');
			$estore_avision->where('ta.product_id',$productDetails['product_id']);
			$estore_avision->where('q.question_year_stat',2);
			$result_prev_year = $estore_avision->get()->result_array();

			$estore_avision->select('*');
			$estore_avision->from('test_assign ta');
			$estore_avision->join('quiz_name q','q.quiz_id = ta.quiz_id');
			$estore_avision->where('ta.product_id',$productDetails['product_id']);
			$estore_avision->where('q.question_year_stat',3);
			$result_section_test = $estore_avision->get()->result_array();

			$countingDetails[]=array(
			    "product_id" => $productDetails['product_id'],
				"product_slug"	=> $productDetails['product_slug'],
				"full_test_count"=>count($result_full_test),
				"prev_year_count"=>count($result_prev_year),
				"section_test_count"=>count($result_section_test),
				"product_name" => $productDetails['product_name'],
				"section_name"	=> $productDetails['section_name'],
				"sub_category_image" =>  $productDetails['sub_category_image'],
				"total_count"=>(count($result_full_test)+count($result_prev_year)+count($result_section_test))
			);

		}
    
		return $countingDetails;
   }

	public function get_TestSeriesdatas()
	{
	    $estore_avision = $this->load->database('estore_avision', TRUE);
	    
		$estore_avision->select('*');
		$estore_avision->from('product p');
		$estore_avision->join('sub_category scat','p.sub_cat_id = scat.sub_category_id');
		$estore_avision->join('course_type_section cts','cts.section_id = p.section_id');
		$estore_avision->where('p.product_type',0);
		//$estore_avision->group_by('p.sub_cat_id');
		$estore_avision->where_in('scat.trending_order',array(1,2,3,4));
		$estore_avision->limit(4);
		$result_product = $estore_avision->get()->result_array();


		foreach ($result_product as $productDetails) {

			$estore_avision->select('*');
			$estore_avision->from('test_assign ta');
			$estore_avision->join('quiz_name q','q.quiz_id = ta.quiz_id');
			$estore_avision->where('ta.product_id',$productDetails['product_id']);
			$estore_avision->where('q.question_year_stat',1);
			$result_full_test = $estore_avision->get()->result_array();

			$estore_avision->select('*');
			$estore_avision->from('test_assign ta');
			$estore_avision->join('quiz_name q','q.quiz_id = ta.quiz_id');
			$estore_avision->where('ta.product_id',$productDetails['product_id']);
			$estore_avision->where('q.question_year_stat',2);
			$result_prev_year = $estore_avision->get()->result_array();

			$estore_avision->select('*');
			$estore_avision->from('test_assign ta');
			$estore_avision->join('quiz_name q','q.quiz_id = ta.quiz_id');
			$estore_avision->where('ta.product_id',$productDetails['product_id']);
			$estore_avision->where('q.question_year_stat',3);
			$result_section_test = $estore_avision->get()->result_array();

			$countingDetails[]=array(
			    "product_id" => $productDetails['product_id'],
				"product_slug"	=> $productDetails['product_slug'],
				"full_test_count"=>count($result_full_test),
				"prev_year_count"=>count($result_prev_year),
				"section_test_count"=>count($result_section_test),
				"product_name" => $productDetails['product_name'],
				"section_id" => $productDetails['section_id'],
				"section_name"	=> $productDetails['section_name'],
				"sub_category_image" =>  $productDetails['sub_category_image'],
				"total_count"=>(count($result_full_test)+count($result_prev_year)+count($result_section_test))
			);

		}

		return $countingDetails;
	}

	

	public function get_blog($id)
	{
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select('blog.*, cat.category_name, u.first_name, u.last_name');
		$estore_avision->from('blogs blog');
		$estore_avision->join('users u', 'u.id=blog.user_id');
		$estore_avision->join('categories cat', 'cat.id=blog.category_id', 'left');
		$estore_avision->where('blog.is_active', 1);
		$estore_avision->where('blog.id', $id);
		$query = $estore_avision->get();
		return $query->row();
	}

	public function get_data($id)
	{
	    $estore_avision = $this->load->database('estore_avision', TRUE);
		return $estore_avision->query("select * from `courses` inner join sub_courses on courses.courses_id=sub_courses.courses_id inner join sub_category on sub_courses.sub_courses_id=sub_category.sub_courses_id where courses.courses_id='$id'")->result();
	}

	public function get_categories()
	{
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$query = $estore_avision->get('categories');
		return $query->result();
	}

	public function get_page($slug)
	{
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->where('slug', $slug);
		$query = $estore_avision->get('pages');
		return $query->row();
	}

	public function insert_contact($contactData)
	{
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->insert('contacts', $contactData);
		return $estore_avision->insert_id();
	}

	public function login($username, $password) 
	{
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->where('username', $username);
		$estore_avision->where('password', md5($password));
		$query = $estore_avision->get('users');

		if($query->num_rows() == 1) {
			return $query->row();
		}
	}

	public function get_admin_blogs()
	{
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select('blog.*, u.first_name, u.last_name');
		$estore_avision->from('blogs blog');
		$estore_avision->join('users u', 'u.id=blog.user_id');
		$estore_avision->order_by('blog.created_at', 'desc');
		$query = $estore_avision->get();
		return $query->result();
	}

	public function get_admin_blog($id)
	{
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select('blog.*, u.first_name, u.last_name');
		$estore_avision->from('blogs blog');
		$estore_avision->join('users u', 'u.id=blog.user_id');
		$estore_avision->where('blog.id', $id);
		$query = $estore_avision->get();
		return $query->row();
	}

	public function checkToken($token)
	{
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->where('token', $token);
		$query = $estore_avision->get('users');

		if($query->num_rows() == 1) {
			return true;
		}
		return false;
	}

	public function insertBlog($blogData)
	{
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->insert('blogs', $blogData);
		return $estore_avision->insert_id();
	}

	public function updateBlog($id, $blogData)
	{
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->where('id', $id);
		$estore_avision->update('blogs', $blogData);
	}

	public function deleteBlog($id)
	{
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->where('id', $id);
		$estore_avision->delete('blogs');
	}
	
	public function fetch_menu_data($id){
	    
	    $estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select('sub_category_id,sub_category_name,sub_cat_slug');
		$estore_avision->from('sub_category');
		$estore_avision->where('parent_cat_id',$id);
		$result = $estore_avision->get()->result_array();
		/*echo $estore_avision->last_query();*/
		return $result;
	}
	public function data_footer_menu(){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select('sub_category_id,sub_category_name,sub_cat_slug');
		$estore_avision->from('sub_category');
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function video_courseById($sub_cat_id){
	    
	    $estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select('p.product_id,p.product_name,p.product_img');
		$estore_avision->from('sub_category scat');
		$estore_avision->join('sub_courses sc','sc.sub_courses_id = scat.sub_courses_id');
		$estore_avision->join('courses c','c.courses_id = sc.courses_id');
		$estore_avision->join('product p','p.video_course = c.courses_id');
		$estore_avision->where('scat.sub_category_id',$sub_cat_id);
		$estore_avision->limit(4);
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function testSeriesById($sub_cat_id){
	    
	    $estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select('p.product_id,p.product_name,scat.sub_category_image');
		$estore_avision->from('product p');
		$estore_avision->join('sub_category scat','p.sub_cat_id = scat.sub_category_id');
		$estore_avision->where('scat.sub_category_id',$sub_cat_id);
		$estore_avision->where('p.product_type',0);
		$estore_avision->limit(4);
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function get_full_test($product_id){
		$estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select('*');
		$estore_avision->from('test_assign ta');
		$estore_avision->join('quiz_name q','q.quiz_id = ta.quiz_id');
		$estore_avision->where('ta.product_id',$product_id);
		$estore_avision->where('q.question_year_stat',1);
		$result_full_test = $estore_avision->get()->result_array();
		
		return $result_full_test;
	}
	
	public function get_sectional_test($product_id){
		$estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select('*');
		$estore_avision->from('test_assign ta');
		$estore_avision->join('quiz_name q','q.quiz_id = ta.quiz_id');
		$estore_avision->where('ta.product_id',$product_id);
		$estore_avision->where('q.question_year_stat',3);
		$result_sec_test = $estore_avision->get()->result_array();
		return $result_sec_test;
	}
	
	public function get_prev_yr_test($product_id){
		$estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select('*');
		$estore_avision->from('test_assign ta');
		$estore_avision->join('quiz_name q','q.quiz_id = ta.quiz_id');
		$estore_avision->where('ta.product_id',$product_id);
		$estore_avision->where('q.question_year_stat',2);
		$result_prev_test = $estore_avision->get()->result_array();
		return $result_prev_test;
	}
	
	public function get_product_details($product_id){
		$estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select('p.*,scat.sub_category_image');
		$estore_avision->from('product p');
		$estore_avision->join('sub_category scat','p.sub_cat_id = scat.sub_category_id');
		$estore_avision->where('product_type',0);
		$estore_avision->where('product_id',$product_id);
		$query=$estore_avision->get()->result_array();
		return $query;
	}
	
	public function get_test_count($product_id){
		$estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select('*');
		$estore_avision->from('test_assign ta');
		$estore_avision->join('quiz_name q','q.quiz_id = ta.quiz_id');
		$estore_avision->where('ta.product_id',$product_id);
		$estore_avision->where('q.question_year_stat',1);
		$result_full_test = $estore_avision->get()->result_array();
		
		$estore_avision->select('*');
		$estore_avision->from('test_assign ta');
		$estore_avision->join('quiz_name q','q.quiz_id = ta.quiz_id');
		$estore_avision->where('ta.product_id',$product_id);
		$estore_avision->where('q.question_year_stat',3);
		$result_sec_test = $estore_avision->get()->result_array();
		
		$estore_avision->select('*');
		$estore_avision->from('test_assign ta');
		$estore_avision->join('quiz_name q','q.quiz_id = ta.quiz_id');
		$estore_avision->where('ta.product_id',$product_id);
		$estore_avision->where('q.question_year_stat',2);
		$result_prev_test = $estore_avision->get()->result_array();
		
		return count($result_full_test) + count($result_sec_test) + count($result_prev_test);
	}
	
	public function fetch_VideoCourse($id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("product p");
		$estore_avision->join("courses c","p.video_course = c.courses_id");
		$estore_avision->where('c.courses_id',$id);
		$estore_avision->where('p.product_type',1);
		$estore_avision->where('p.activity',1);
		$estore_avision->order_by('p.product_id','desc');
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function fetchall_VideoCourse(){
	
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("product p");
		$estore_avision->join("live_class_meta lm","lm.product_id = p.product_id");
		$estore_avision->where('p.product_type',4);
		$estore_avision->where_in('p.product_id',array(118,121,122,123));
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function live_class_fetch(){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("product p");
		$estore_avision->join("live_class_meta lcm","lcm.product_id = p.product_id");
		$estore_avision->where('p.product_type',4);
		$estore_avision->order_by('p.product_id','desc');
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function live_class_details($product_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select("*");
		$estore_avision->from("product p");
		$estore_avision->join("live_class_meta lcm","lcm.product_id = p.product_id");
		$estore_avision->where("p.product_type",4);
		$estore_avision->where("p.product_id",$product_id);
		$result = $estore_avision->get()->result_array();
		
		return $result;
	}
	
	public function live_class_details_chap($prod_id,$live_class_meta_id,$sub_id){
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("live_class_video");
		$estore_avision->where("sub_id",$sub_id);
		$estore_avision->where("prod_id",$prod_id);
		$estore_avision->where("live_class_meta_id",$live_class_meta_id);
		$result_chap = $estore_avision->get()->result_array();
		return $result_chap;
	}
	
	public function live_cls_chapter($sub_id,$prod_id){
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->distinct("lcv.chap_id");
		$estore_avision->select("lcv.chap_id,ch.*");
		$estore_avision->from("live_class_video lcv");
		$estore_avision->join("chapter ch","ch.chapter_id = lcv.chap_id");
		$estore_avision->where("lcv.sub_id",$sub_id);
		$estore_avision->where("lcv.prod_id",$prod_id);
		$result_chapter = $estore_avision->get()->result_array();
		return $result_chapter;	
	}
	
	public function live_cls_subject($product_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->distinct("lcv.sub_id");
		$estore_avision->select("lcv.sub_id,aqt.type_name");
		$estore_avision->from("live_class_video lcv");
		$estore_avision->join("add_question_type aqt","aqt.type_id = lcv.sub_id");
		$estore_avision->where("lcv.prod_id",$product_id);
		$estore_avision->limit(1);
		$result = $estore_avision->get()->result_array();
		return $result;
		
		
	}
	
	public function live_cls_videos($sub_id,$chap_id,$prod_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("live_class_video lcv");
		$estore_avision->where("lcv.sub_id",$sub_id);
		$estore_avision->where("lcv.chap_id",$chap_id);
		$estore_avision->where("lcv.prod_id",$prod_id);
		
		$result = $estore_avision->get()->result_array();
		return $result;
		
	}
	
	public function fetch_live_class_video($product_id,$day_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("live_class_video lcv");
		$estore_avision->join("add_question_type aqt","aqt.type_id = lcv.sub_id");
		$estore_avision->join("chapter ch","ch.chapter_id = lcv.chap_id");
		$estore_avision->where("lcv.day_id",$day_id);
		$estore_avision->where("lcv.prod_id",$product_id);
		$estore_avision->order_by("lcv.vdo_order",'asc');
		$estore_avision->order_by("lcv.video_id",'asc');
		
		$result = $estore_avision->get()->result_array();
		return $result;
		
	}
	
	public function live_class_dashboard_test($prod_id){
		
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("live_class_prod_cat lcpt");
		$estore_avision->join("quiz_name qn","qn.sub_category_id = lcpt.sub_cat_id");
		$estore_avision->join("sub_category sc","sc.sub_category_id = lcpt.sub_cat_id");
		$estore_avision->where("lcpt.product_id",$prod_id);
		
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function live_class_check_buystat($product_id,$user_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("buy_liveclass");
		$estore_avision->where("prod_id",$product_id);
		$estore_avision->where("user_id",$user_id);
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function insert_doubt_details($registerData){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		date_default_timezone_set("Asia/Kolkata");
		
		$data = array(
		
			'doubt_type' => $registerData['type'],
			'subject_id' => $registerData['sub_id'],
			'chapter_id' => $registerData['chap_id'],
			'doubt_title' => $registerData['title'],
			'doubt_desc'	=> $registerData['desc'],
			'user_id'	=> $registerData['user_id'],
			'product_id'	=> $registerData['product_id'],
			'time'	=> date("H:i:s"),
			'created_date'	=> date("Y-m-d")
		);
		if($registerData['user_id'] !=0){
			$estore_avision->insert('doubt_details',$data);
			$last_id = $estore_avision->insert_id();
			if($estore_avision->affected_rows() > 0){
				return $last_id;
			}else{
				return 0;
			}
		}
		
	}
	
	public function insert_comment_details($comentData){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		date_default_timezone_set("Asia/Kolkata");
		
		$data = array(
		
			'comment' => $comentData['comment'],
			'doubt_id' => $comentData['doubt_id'],
			'user_id' => $comentData['user_id'],
			'created_date'	=> date("Y-m-d")
		);
		if($comentData['user_id'] !=0){
			$estore_avision->insert('doubt_comment',$data);
			$last_id = $estore_avision->insert_id();
			if($estore_avision->affected_rows() > 0){
				return $last_id;
			}else{
				return 0;
			}
		}
		
	}
	
	public function get_doubt_by_id(){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("dd.*,u.user_id,u.user_name");
		$estore_avision->from("doubt_details dd");
		$estore_avision->join("users u","u.user_id=dd.user_id");
		$estore_avision->order_by("dd.doubt_id","desc");
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	public function get_doubt_all($prod_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("dd.*,u.user_id,u.user_name");
		$estore_avision->from("doubt_details dd");
		$estore_avision->join("users u","u.user_id=dd.user_id");
		$estore_avision->where("dd.product_id",$prod_id);
		$estore_avision->order_by("dd.doubt_id","desc");		
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function get_filter_doubts($sub_id,$chap_id,$prod_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("dd.*,u.user_id,u.user_name");
		$estore_avision->from("doubt_details dd");
		$estore_avision->join("users u","u.user_id=dd.user_id");
		$estore_avision->where("dd.subject_id",$sub_id);
		$estore_avision->where("dd.chapter_id",$chap_id);
		$estore_avision->where("dd.product_id",$prod_id);
		$estore_avision->order_by("dd.doubt_id","desc");
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function comment_doubt_fetch($id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("dd.*,u.user_id,u.user_name");
		$estore_avision->from("doubt_details dd");
		$estore_avision->join("users u","u.user_id=dd.user_id");
		$estore_avision->where("dd.doubt_id",$id);
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	
	
	public function liveclass_videos_subject($prod_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->distinct("lcv.sub_id");
		$estore_avision->select("lcv.sub_id,aqt.type_name");
		$estore_avision->from("live_class_video lcv");
		$estore_avision->join("add_question_type aqt","aqt.type_id = lcv.sub_id");
		$estore_avision->where("lcv.prod_id",$prod_id);
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function liveclass_vdo_count($sub_id,$prod_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select("*");
		$estore_avision->from("live_class_video");
		$estore_avision->where("sub_id",$sub_id);
		$estore_avision->where("prod_id",$prod_id);
		$result = $estore_avision->get()->result_array();
		return count($result);	
	}
	
	public function count_comment($doubt_id){
	    
	    $estore_avision = $this->load->database('estore_avision', TRUE);
	    $estore_avision->select("*");
		$estore_avision->from("doubt_comment");
		$estore_avision->where("doubt_id",$doubt_id);
		$result = $estore_avision->get()->result_array();
		return count($result);	
	}
	
	public function fetch_user_name($user_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
	    $estore_avision->select("*");
		$estore_avision->from("users");
		$estore_avision->where("user_id",$user_id);
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function fetch_commentsById($doubt_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
	    $estore_avision->select("*");
		$estore_avision->from("doubt_comment dc");
		$estore_avision->join("users u","u.user_id = dc.user_id");
		$estore_avision->where("dc.doubt_id",$doubt_id);
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function fetch_all_ori_vdo($product_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("live_class_video lcv");
		$estore_avision->join("add_question_type aqt","aqt.type_id = lcv.sub_id");
		$estore_avision->join("chapter ch","ch.chapter_id = lcv.chap_id");
		$estore_avision->where("lcv.prod_id",$product_id);
		$estore_avision->where("lcv.vdo_orientation",1);
		$estore_avision->order_by("lcv.day_id","asc");
		$estore_avision->order_by("lcv.video_id","desc");
		
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function fetch_course_teacher($course_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("teacher_login tl");
		$estore_avision->join("teacher_course_assign tca","tl.teacher_id = tca.teacher_id");
		$estore_avision->where("tca.live_course_id",$course_id);
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function fetch_Video_Course_teacher($prodcut_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("teacher_login tl");
		$estore_avision->join("product p","tl.teacher_id = p.teacher_id");
		$estore_avision->where("p.product_id",$prodcut_id);
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function fetch_cur_day($date,$product_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("live_class_video");
		$estore_avision->where("prod_id",$product_id);
		$estore_avision->where("vdo_date",$date);
		$estore_avision->limit(1);
		
		$result = $estore_avision->get()->result_array();
		if(!empty($result)){
		return $result[0]['day_id'];
		}
		return 0;
	}
	
	public function fetch_all_studyplan_vdo($product_id){
		date_default_timezone_set("Asia/Kolkata");
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("live_class_video lcv");
		$estore_avision->join("add_question_type aqt","aqt.type_id = lcv.sub_id");
		$estore_avision->join("chapter ch","ch.chapter_id = lcv.chap_id");
		$estore_avision->where("lcv.prod_id",$product_id);
		$estore_avision->where("lcv.vdo_orientation",0);
		$estore_avision->where("lcv.vdo_date",date('Y-m-d'));
		$estore_avision->order_by("lcv.day_id","asc");
		$estore_avision->order_by("lcv.video_id","desc");
		
		$result = $estore_avision->get()->result_array();
		return $result;
	}

		public function check_cupon_code($data){
			date_default_timezone_set("Asia/Kolkata");
			$estore_avision = $this->load->database('estore_avision', TRUE);

			$estore_avision->select("cc.cupon_discount, cc.cupon_type as cupon_status, p.product_offer_price");
			$estore_avision->from("product as p");
			$estore_avision->join("coupon_check as cc","cc.product_id = p.product_id");
			$estore_avision->where("p.product_id",$data->id);
			$estore_avision->where("cc.coupon_code",$data->code);
			$estore_avision->where("cc.cupon_status",1);
			$result = $estore_avision->get()->result_array();

			if(count($result) > 0){
			if($result[0]['cupon_discount'] == 0){

			$estore_avision->select("*");
			$estore_avision->from("buy_liveclass");
			$estore_avision->where("prod_id",$data->id);
			$estore_avision->where("user_id",$data->user_id);
			$result_check =$estore_avision->get()->result_array();

			if(count($result_check) > 0){

			$estore_avision->where("prod_id",$data->id);
			$estore_avision->where("user_id",$data->user_id);
			$estore_avision->update("buy_liveclass",array("buy_status" => 1));
			}else{

			$data_buy_stat = array(

			"prod_id" => $data->id,
			"user_id" => $data->user_id,
			"validity" => 0,
			"buy_status" => 1,
			"created_date" => date("Y-m-d")
			);

			$estore_avision->insert("buy_liveclass",$data_buy_stat);
			}


			$response = array(

			'cupon_status' => $result[0]['cupon_status'],
			'cupon_discount' => $result[0]['cupon_discount'],
			'product_price' => 0,
			'coupon_code' => '',
			'status' => 200,
			'message' => 'cupon matched'
			);
			}else{
			if($result[0]['cupon_status'] == 1){
			$product_cal_price  = (int)($result[0]['product_offer_price'] * $result[0]['cupon_discount']/100);
			$product_price = $result[0]['product_offer_price'] - $product_cal_price;
			}else{
			$product_price = $result[0]['product_offer_price'] - $result[0]['cupon_discount'];
			}
			if($product_price == 0){
				$estore_avision->where("prod_id",$data->id);
				$estore_avision->where("user_id",$data->user_id);
				$estore_avision->update("buy_liveclass",array("buy_status" => 1));
				$response = array(

				'cupon_status' => $result[0]['cupon_status'],
				'cupon_discount' => $result[0]['cupon_discount'],
				'product_price' => $product_price,
				'coupon_code' => $data->code,
				'status' => 200,
				'message' => 'cupon matched'
				);
			}else{
				$response = array(

					'cupon_status' => $result[0]['cupon_status'],
					'cupon_discount' => $result[0]['cupon_discount'],
					'product_price' => $product_price,
					'coupon_code' => $data->code,
					'status' => 202,
					'message' => 'cupon matched'
				);	
			}
			
			}
			}else{
			$response = array(

			'cupon_status' => 0,
			'cupon_discount' => 0,
			'product_price' => 0,
			'coupon_code' => '',
			'status' => 203,
			'message' => 'cupon not matched'
			);
			}

			return $response;
		}


	public function register($registerData){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		
		$data = array(
		
			'user_name' => $registerData['user_name'],
			'user_email' => $registerData['user_email'],
			'user_phone' => $registerData['user_phone'],
			'user_password' => md5($registerData['user_password']),
			'user_access'	=> 1,
			'user_role'	=> 'S',
			'user_group' => 2,
			'student_area'	=> 2,
			'created_date'	=> date("Y-m-d")
		);
		
		$estore_avision->insert('users',$data);
		$user_id = $estore_avision->insert_id();
		if($estore_avision->affected_rows() > 0){
			return $user_id;
		}else{
			return 0;
		}
	}

	
	public function fetch_demo_video($product_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("live_class_video lcv");
		$estore_avision->join("add_question_type aqt","aqt.type_id = lcv.sub_id");
		$estore_avision->join("chapter ch","ch.chapter_id = lcv.chap_id");
		$estore_avision->where("lcv.prod_id",$product_id);
		$estore_avision->where("demo_status",1);
		$estore_avision->where("vdo_orientation",1);
		
		$result = $estore_avision->get()->result_array();
		
		return $result;
	}

	
	public function fetch_live_class_current_vdo($product_id){
		
		date_default_timezone_set("Asia/Kolkata");
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("live_class_video lcv");
		$estore_avision->join("add_question_type aqt","aqt.type_id = lcv.sub_id");
		$estore_avision->join("chapter ch","ch.chapter_id = lcv.chap_id");
		$estore_avision->where("lcv.prod_id",$product_id);
		$estore_avision->where("demo_status",0);
		$estore_avision->where("vdo_orientation",1);
		/*$estore_avision->where("lcv.vdo_date",date("Y-m-d"));
		$estore_avision->where("lcv.vdo_orientation",0);*/
		
		$result = $estore_avision->get()->result_array();
		
		return $result;
	}


	public function check_user_reg_date($user_id){
		
		date_default_timezone_set("Asia/Kolkata");
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("users");
		$estore_avision->where("user_id",$user_id);
		
		$result = $estore_avision->get()->result_array();
		
		return $result[0]['created_date'];
	}

	public function product_start_date($product_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("product");
		$estore_avision->where("product_id",$product_id);
		
		$result = $estore_avision->get()->result_array();
		
		return $result[0]['from_date'];
	}


	public function checkdemo($video_id){
		
		date_default_timezone_set("Asia/Kolkata");
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("live_class_video lcv");
		$estore_avision->where("lcv.video_id",$video_id);
		$estore_avision->where("lcv.demo_status",1);
		
		$result = $estore_avision->get()->result_array();
		
		if(count($result) > 0){
			return 1;
		}else{
			return 0;
		}
	}

	public function fetch_meta_data($page_slug){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("page_meta");
		$estore_avision->where("page_slug",$page_slug);
		$result = $estore_avision->get()->result_array();
		return $result;
		
		
	}

	public function fetch_meta_inner_data($page_id,$id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("page_inner_meta");
		$estore_avision->where("page_id",$page_id);
		$estore_avision->where("page_inner_id",$id);
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function fetch_subcat_footer(){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("sub_category_id,sub_category_name");
		$estore_avision->from("sub_category");
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function fetch_course_detals($id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("product");
		$estore_avision->where("product_id",$id);
		$result = $estore_avision->get()->result_array();
		return $result;
		
	}
	
	public function count_noof_videos($id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("video_product_details");
		$estore_avision->where("product_id",$id);
		$result = $estore_avision->get()->result_array();
		return count($result);
	}
	
	public function fetch_video_course_subject($id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->distinct("vpd.subject_id");
		$estore_avision->select("vpd.subject_id,vs.type_name,vs.type_img");
		$estore_avision->from("video_product_details vpd");
		$estore_avision->join("video_subject vs","vs.type_id = vpd.subject_id");
		$estore_avision->where("vpd.product_id",$id);
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function fetch_video_count_subjectwise($subject_id,$id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("video_product_details");
		$estore_avision->where("product_id",$id);
		$estore_avision->where("subject_id",$subject_id);
		$result = $estore_avision->get()->result_array();
		return count($result);
	}
	
	public function fetch_video_course_chapter($sub_id,$prod_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->distinct("vpd.chapter_id");
		$estore_avision->select("vpd.chapter_id,vpd.subject_id,vc.chapter_id,vc.chapter_name");
		$estore_avision->from("video_product_details vpd");
		$estore_avision->join("video_chapter vc","vc.chapter_id = vpd.chapter_id");
		$estore_avision->where("vpd.product_id",$prod_id);
		$estore_avision->where("vpd.subject_id",$sub_id);
		$estore_avision->order_by("vpd.chapter_id",'asc');
		$result = $estore_avision->get()->result_array();
		
		return $result;
	}
	
	public function vdochapwise($chapter_id,$sub_id,$prod_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("video_product_details");
		$estore_avision->where("product_id",$prod_id);
		$estore_avision->where("subject_id",$sub_id);
		$estore_avision->where("chapter_id",$chapter_id);
		$estore_avision->where("demo_video",0);
		$estore_avision->order_by("video_id",'asc');
		$result = $estore_avision->get()->result_array();
		//echo $estore_avision->last_query();
		//print_r($result);
		//exit;
		return $result;
	}
	
	public function vdochapwise_menu($chapter_id,$sub_id,$prod_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("video_product_details");
		$estore_avision->where("product_id",$prod_id);
		$estore_avision->where("subject_id",$sub_id);
		$estore_avision->where("chapter_id",$chapter_id);
		$estore_avision->order_by("video_id",'asc');
		$result = $estore_avision->get()->result_array();
		//echo $estore_avision->last_query();
		//print_r($result);
		//exit;
		return $result;
	}
	
	public function fetch_course_video($prod_id,$chap_id,$sub_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("video_product_details");
		$estore_avision->where("product_id",$prod_id);
		$estore_avision->where("subject_id",$sub_id);
		$estore_avision->where("chapter_id",$chap_id);
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function login_data($loginData){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select("user_id");
		$estore_avision->from("users");
		$estore_avision->where("user_password",md5($loginData['user_password']));
		$or_where='user_phone="'.$loginData['user_email'].'" or user_email="'.$loginData['user_email'].'"';
		$estore_avision->where($or_where);
		$result = $estore_avision->get()->result_array();
		if(count($result) > 0){
			
			$user_id = $result[0]['user_id'];
			return $user_id;
		}else{
			return 0;
		}
	}
	
	public function product_buy_now($user_id,$prod_id){
		
		date_default_timezone_set("Asia/Kolkata");
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$data=array(
		 'product_id' => $prod_id,
		 'user_id' => $user_id,
		 'status' => 0,
		 'created_date' => date('Y-m-d')
			
		);
		
		$estore_avision->insert('product_buy_now',$data);
		
		$estore_avision->select('*');
		$estore_avision->from('product');
		$estore_avision->where('product_id',$prod_id);
		$query=$estore_avision->get()->result_array();
		
		$prod_name = $query[0]['product_name'];
		$product_price = $query[0]['product_offer_price'];
		
		$estore_avision->select('*');
		$estore_avision->from('users');
		$estore_avision->where('user_id',$user_id);
		$result=$estore_avision->get()->result_array();
		
		$payer_name=$result[0]['user_name'];
		$payer_email=$result[0]['user_email'];
		$payer_phone=$result[0]['user_phone'];
		
		$info = array(
		
			'product_name' => $prod_name,
			'product_price' => $product_price,
			'payer_name' => $payer_name,
			'payer_email' => $payer_email,
			'payer_phone' => $payer_phone,
		);	
		
		return $info;
	}
	
	public function	add_user_inofo($inofoData, $user_id) {
    	$estore_avision = $this->load->database('estore_avision', TRUE);

    	$estore_avision->select("user_id");
    	$estore_avision->from('user_info');
    	$estore_avision->where('user_id', $user_id);
    	$result = $estore_avision->get()->result_array();
    	if(count($result) > 0){
    		$estore_avision->where('user_id', $user_id);
    		$estore_avision->update('user_info', $inofoData);
    	}
    	else {
    		$estore_avision->insert('user_info', $inofoData);
    	}
    	

    	if($estore_avision->affected_rows() > 0){
			return 1;
		}else{
			return 0;
		}

	}

	public function get_user_info($user_id) {
		$estore_avision = $this->load->database('estore_avision', TRUE);

		$estore_avision->select("*");		
		$estore_avision->where('user_id', $user_id);		
		$result = $estore_avision->get('user_info')->result_array();

		if(count($result) > 0){
			return $result;
		}
		else {
			return 0;
		}

	}

	public function get_user_all_data($user_id) {		

		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");		
		$estore_avision->where('user_id', $user_id);		
		$result = $estore_avision->get('users')->result_array();

		if(count($result) > 0){
			return $result;
		}
		else {
			return 0;
		}
	}

	public function update_user_phone($inofoData, $user_id) {
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->where('user_id', $user_id);
    	$estore_avision->update('users', $inofoData);

    	if($estore_avision->affected_rows() > 0){
			return 1;
		}else{
			return 0;
		}

	}


	public function update_user_img($data, $user_id) {
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->where('user_id', $user_id);
    	$estore_avision->update('users', $data);

    	if($estore_avision->affected_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}

	public function update_user_password($userPassword, $prev_password, $user_id) {
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->where('user_id', $user_id);
		$estore_avision->where('user_password', md5($prev_password));
		$estore_avision->update('users', $userPassword);
		if($estore_avision->affected_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}

	public function update_exam_details($exam) {
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->insert('user_suggested_category', $exam);
		if($estore_avision->affected_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}

	public function get_exam_details($user_id)
	{
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->distinct("course_id");
		$estore_avision->select("course_id, courses_name");
		$estore_avision->from('user_suggested_category us');
		$estore_avision->join("courses c", "c.courses_id = us.course_id");
		$estore_avision->where('us.user_id', $user_id);
		
		$result = $estore_avision->get()->result_array();
		$res = [];
		$final_res = [];
		foreach ($result as $value) {
			$estore_avision->select("*");
			$estore_avision->from('user_suggested_category uc');
			$estore_avision->join('sub_category sc', "sc.sub_category_id = uc.subcategory_id");
			$estore_avision->where('uc.course_id', $value['course_id']);
			$estore_avision->where('uc.user_id', $user_id);			
			$res = $estore_avision->get()->result_array();

			
				$final_res[]  = array(
					'courses_name' => $value['courses_name'],
					'sub_category' => $res 
				);
		}

		return $final_res;
		//$estore_avision->join("sub_category sc", "sc.sub_category_id = us.subcategory_id");
		
			
	}

	public function delete_exam_details($suggested_id) {
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->where("suggested_id", $suggested_id);
		$estore_avision->delete("user_suggested_category");
		if($estore_avision->affected_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}

	public function subCategoryNameUpdated($courseId, $user_id)
	{
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from('user_suggested_category');		
		$estore_avision->where('course_id', $courseId);
		$estore_avision->where('user_id', $user_id);			
		$result = $estore_avision->get()->result_array();

		if(empty($result))
		{
			$estore_avision = $this->load->database('estore_avision', TRUE);
		return $estore_avision->query("select * from `courses` inner join sub_courses on courses.courses_id=sub_courses.courses_id inner join sub_category on sub_courses.sub_courses_id=sub_category.sub_courses_id where courses.courses_id='$courseId'")->result_array();
		}
		$sub_cat_arr = array();
		foreach ($result as $value) {
			array_push($sub_cat_arr,$value['subcategory_id']);
		}
		
			$estore_avision->select("c.courses_id,c.courses_name,scat.sub_category_id,scat.sub_category_name");
			$estore_avision->from("courses c");
			$estore_avision->join("sub_courses sc","sc.courses_id=c.courses_id");
			$estore_avision->join("sub_category scat","scat.sub_courses_id=sc.sub_courses_id");
			$estore_avision->where("c.courses_id",$courseId);
			$estore_avision->where_not_in("scat.sub_category_id",$sub_cat_arr);
			$res = $estore_avision->get()->result_array();

			
		return $res;		
	}
	
	public function fetch_demo_chapter_vdo($prod_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->from("video_product_details");
		$estore_avision->where("demo_video",1);
		$estore_avision->where("product_id",$prod_id);
		$estore_avision->order_by("video_id",'asc');
		$result = $estore_avision->get()->result_array();
		//echo $estore_avision->last_query();
		//print_r($result);
		//exit;
		return $result;
	}
	
	public function get_quiz_information($quiz_id)
	{
		$estore_avision = $this->load->database('estore_avision', TRUE);

		$estore_avision->select('quiz_name, no_of_qs, duration, changable, correct_mark, negative_mark');
		$estore_avision->from("quiz_name");
		$estore_avision->where("quiz_id", $quiz_id);
		$res = $estore_avision->get()->result_array();

		$estore_avision->select('qd.duration,  qt.question_type_name, qd.question_type_id, qd.total_question');
		$estore_avision->from("quiz_name qn");
		$estore_avision->join("quiz_durations qd", "qd.quiz_id = qn.quiz_id");
		$estore_avision->join("question_type qt", "qt.question_type_id = qd.question_type_id");
		$estore_avision->where("qn.quiz_id", $quiz_id);
		$result = $estore_avision->get()->result_array();
		$final_res = array('quiz_info' =>$res, 'quiz_details'=> $result);
		return $final_res;
	}
	public function get_quiz_question($quiz_id, $question_type_id,$test_taken_id) {
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("q.question_id, q.directions, q.question, q.question_type_id, q.question_img, q.directions_img, q.directions_status,q.ans_desc");
		$estore_avision->from("question q");
		$estore_avision->where("quiz_id", $quiz_id);
		$estore_avision->where("q.question_type_id", $question_type_id);
		$estore_avision->order_by("q.question_id", "asc");
		$result = $estore_avision->get()->result_array();


		$final_array = [];
		foreach ($result as $key =>  $value) {

		$estore_avision->select('question_status');
		$estore_avision->from('student_full_tests_question_temp');
		$estore_avision->where('test_question_id',$value['question_id']);
		$estore_avision->where('test_taken_id',$test_taken_id);
		$result_qn = $estore_avision->get()->result_array();
		if(!empty($result_qn)){

		$result[$key]['question_status'] = $result_qn[0]['question_status'];


		}else{
		$result[$key]['question_status'] = 0;
		}
		$estore_avision->select("ans,ans_id,status");
		$estore_avision->from("answers");
		$estore_avision->where("question_id", $value['question_id']);
		$res = $estore_avision->get()->result_array();

		$final_array[] = array(
		'question_details' => $result[$key],
		'answers_list'=> $res
		);


		}

		return $final_array;


	}


	public function user_name($user_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select('user_name,user_phone,user_email');
		$estore_avision->from('users');
		$estore_avision->where('user_id',$user_id);
		$result = $estore_avision->get()->result_array();
		return $result;
		
	}

	public function start_quiz($quiz_id, $student_id)
	{	date_default_timezone_set('Asia/Kolkata');
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("student_taken_tests_id");
		$estore_avision->from("student_full_tests");
		$estore_avision->where("test_id", $quiz_id);
		$estore_avision->where("student_id", $student_id);
		$result = $estore_avision->get()->result_array();
		if(count($result) == 0) {
		$estore_avision->insert("student_full_tests", array('test_id' =>$quiz_id, 'student_id'=> $student_id, "status"=> 1, 'created_date'=>date("Y-m-d"), "start_time"=> date("H:i:s"), "created_from"=>2 ));

		return $estore_avision->insert_id();



		}
		else {
		return false;
		}

	}

	public function save_answer($test_taken_id, $test_question_id, $question_status, $answer_id)
	{
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("student_test_question_id");
		$estore_avision->from("student_full_tests_question_temp");
		$estore_avision->where("test_question_id", $test_question_id);
		$estore_avision->where("test_taken_id", $test_taken_id);
		$result = $estore_avision->get()->result_array();
		if(count($result)==0) {
		$estore_avision->insert("student_full_tests_question_temp",array('test_taken_id' => $test_taken_id, 'test_question_id'=> $test_question_id, 'question_status'=>$question_status, "created_date"=> date("Y-m-d") ));

		if($estore_avision->affected_rows() > 0) {


		if($answer_id != 0) {
		return $answer_id;
		$estore_avision->insert("student_full_tests_answers_temp",array('test_taken_id' => $test_taken_id, 'test_question_id'=> $test_question_id, 'asnwer_id'=>$answer_id, "created_date"=> date("Y-m-d") ));
		}
		}

		}
		else {

		$estore_avision->where("test_question_id", $test_question_id);
		$estore_avision->where("test_taken_id", $test_taken_id);
		$estore_avision->update("student_full_tests_question_temp", array('question_status' =>$question_status ));

		if($estore_avision->affected_rows() > 0) {

		$estore_avision->select("student_test_answers_id");
		$estore_avision->where("test_question_id", $test_question_id);
		$estore_avision->where("test_taken_id", $test_taken_id);
		$res = $estore_avision->get("student_full_tests_answers_temp")->result_array();

		if(count($res)== 0) {
		if($answer_id != 0) {
		$estore_avision->insert("student_full_tests_answers_temp",array('test_taken_id' => $test_taken_id, 'test_question_id'=> $test_question_id, 'asnwer_id'=>$answer_id, "created_date"=> date("Y-m-d") ));
		}
		}
		else{

		$estore_avision->where("test_question_id", $test_question_id);
		$estore_avision->where("test_taken_id", $test_taken_id);
		$estore_avision->update("student_full_tests_answers_temp",array('asnwer_id'=>$answer_id));
		}


		}

		else {

		if($question_status == 3) {

		$estore_avision->where("test_question_id", $test_question_id);
		$estore_avision->where("test_taken_id", $test_taken_id);
		$estore_avision->update("student_full_tests_answers_temp",array('asnwer_id'=>$answer_id));
		}
		}

		}

		return $answer_id;
	}

	public function rank_n_score($student_id,$quiz_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select('user_name');
		$estore_avision->from('users');
		$estore_avision->where('user_id',$student_id);
		$result_student = $estore_avision->get()->result_array();
		
		$user_name = $result_student[0]['user_name'];

		$estore_avision->select('*');
		$estore_avision->from('quiz_name');				/*************quiz_name*************/
		$estore_avision->where('quiz_id',$quiz_id);
		$result=$estore_avision->get()->result_array();
		
		$quiz_name = $result[0]['quiz_name'];
		$duration = $result[0]['duration'];
		$total_qs = $result[0]['no_of_qs'];
		$correct_marks = $result[0]['correct_mark'];
		$total_mark = $total_qs * $correct_marks;
		
		$estore_avision->select('*');
		$estore_avision->from('student_full_test_result');				/*************nmarks_scored*************/
		$estore_avision->where('test_id',$quiz_id);
		$estore_avision->where('student_id',$student_id);
		$result_score_obtained = $estore_avision->get()->result_array();
		
		$score_obtained = $result_score_obtained[0]['marks'];
		
		$estore_avision->select('*');
		$estore_avision->from('student_full_tests');				/*************nmarks_scored*************/
		$estore_avision->where('test_id',$quiz_id);
		$estore_avision->where('student_id',$student_id);
		$result=$estore_avision->get()->result_array();
		//echo $estore_avision->last_query();
		$test_taken_id=$result[0]['student_taken_tests_id'];
		$created_from = $result[0]['created_from'];
		$start_time = $result[0]['start_time'];
		$end_time = $result[0]['end_time'];
		
		$start_date = new DateTime($start_time);
		$since_start = $start_date->diff(new DateTime($end_time));
		$total_time='';
		if($since_start->h != 0){
			$total_time = $since_start->h.' hrs'.$since_start->i.' min '.$since_start->s.' sec';
		}else{
			$total_time = $since_start->i.' min '.$since_start->s.' sec';
		}
		
		$estore_avision->select('*');
		$estore_avision->from('student_full_test_result');				/*************nmarks_scored*************/
		$estore_avision->where('test_id',$quiz_id);
		$estore_avision->order_by('marks','asc');
		$result_rank = $estore_avision->get()->result_array();
		$total_given_exam = count($result);
		$test_rank_count=1;
		foreach ($result as  $value) {
			
			if($value['student_id'] == $student_id){

				break;
			}else{
				$test_rank_count++;
			}
		}
		$estore_avision->select('sum(count_correct) as correct,sum(count_wrong) as wrong');
		$estore_avision->from('student_full_test_sectional');				/*************nmarks_scored*************/
		$estore_avision->where('test_id',$quiz_id);
		$estore_avision->where('student_id',$student_id);
		$estore_avision->where('test_taken_id',$test_taken_id);
		$result_accuracy = $estore_avision->get()->result_array();
		
		$total_attempted = $result_accuracy[0]['correct'] + $result_accuracy[0]['wrong'];
		
		$percentile = round(($total_attempted / $total_qs) * 100,2);
		
		$accuracy = round(($result_accuracy[0]['correct']/$total_attempted) * 100,2);
		$total_skip = $total_qs - $total_attempted;
		$score_data = array(
			'user_name'	=> $user_name,
			'quiz_name' => $quiz_name,
			'total_score' => $score_obtained,
			'test_full_marks' => $total_mark,
			'rank_count' =>	$test_rank_count,
			'total_given_exam' => $total_given_exam,
			'test_time_taken' => $total_time,
			'total_attempted'=> $total_attempted,	
			'test_total_time'	=> 	$duration,
			'precentile'	=> $percentile,
			'accuracy'	=> $accuracy,
			'total_qs'	=> $total_qs,
			'total_correct' => $result_accuracy[0]['correct'],
			'total_wrong'	=> $result_accuracy[0]['wrong'],
			'total_skip'	=> $total_skip
		);
		
			
		 
		return $score_data;
	}	

	public function sectional_analysis_mark($student_id,$quiz_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select('*');
		$estore_avision->from('quiz_durations qd');
		$estore_avision->join('question_type qt','qt.question_type_id = qd.question_type_id');
		$estore_avision->where('qd.quiz_id',$quiz_id);
		$result_section = $estore_avision->get()->result_array();
			
		$estore_avision->select('student_taken_tests_id');
		$estore_avision->from('student_full_tests');
		$estore_avision->where('test_id',$quiz_id);	
		$estore_avision->where('student_id',$student_id);
		$result_id = $estore_avision->get()->result_array();
		$test_your_taken_id = $result_id[0]['student_taken_tests_id'];
		
		
		
		foreach($result_section as $row){
			
			//echo $row['question_type_name'];
			$estore_avision->select('correct_marks,wrong_marks');
			$estore_avision->from('student_full_test_sectional');
			$estore_avision->where('test_id',$quiz_id);	
			$estore_avision->where('student_id',$student_id);
			$estore_avision->where('section_id',$row['question_type_id']);
			$estore_avision->where('test_taken_id',$test_your_taken_id);
			$result = $estore_avision->get()->result_array();
			$your_mark = $result[0]['correct_marks'] - $result[0]['wrong_marks'];
			
			$estore_avision->select('max(marks) as max_marks,student_id');
			$estore_avision->from('student_full_test_result');
			$estore_avision->where('test_id',$quiz_id);
			$result = $estore_avision->get()->result_array();
			$topper_mark = $result[0]['max_marks'];
			$topper_id = $result[0]['student_id'];
			
			$estore_avision->select('student_taken_tests_id');
			$estore_avision->from('student_full_tests');
			$estore_avision->where('test_id',$quiz_id);	
			$estore_avision->where('student_id',$topper_id);
			$result = $estore_avision->get()->result_array();
			$test_taken_id = $result[0]['student_taken_tests_id'];
			
			$estore_avision->select('correct_marks,wrong_marks');
			$estore_avision->from('student_full_test_sectional');
			$estore_avision->where('test_id',$quiz_id);	
			$estore_avision->where('student_id',$topper_id);
			$estore_avision->where('section_id',$row['question_type_id']);
			$estore_avision->where('test_taken_id',$test_taken_id);
			$result = $estore_avision->get()->result_array();
			$topper_mark = $result[0]['correct_marks'] - $result[0]['wrong_marks'];
			
			$estore_avision->select('avg(correct_marks) as avg_correct_marks,avg(wrong_marks) as avg_wrong_marks');
			$estore_avision->from('student_full_test_sectional');
			$estore_avision->where('test_id',$quiz_id);
			$estore_avision->where('section_id',$row['question_type_id']);
			$result = $estore_avision->get()->result_array();
			$average_mark = $result[0]['avg_correct_marks'] - $result[0]['avg_wrong_marks'];
			$average_mark = round($average_mark,2);
			
			$estore_avision->select('count_correct,count_wrong');
			$estore_avision->from('student_full_test_sectional');
			$estore_avision->where('test_id',$quiz_id);
			$estore_avision->where('student_id',$student_id);
			$estore_avision->where('test_taken_id',$test_your_taken_id);
			$estore_avision->where('section_id',$row['question_type_id']);
			$result = $estore_avision->get()->result_array();
			$your_count_correct = $result[0]['count_correct'];
			$your_count_wrong = $result[0]['count_wrong'];
			
			$estore_avision->select('count_correct,count_wrong');
			$estore_avision->from('student_full_test_sectional');
			$estore_avision->where('test_id',$quiz_id);
			$estore_avision->where('student_id',$topper_id);
			$estore_avision->where('test_taken_id',$test_taken_id);
			$estore_avision->where('section_id',$row['question_type_id']);
			$result = $estore_avision->get()->result_array();
			$topper_count_correct = $result[0]['count_correct'];
			$topper_count_wrong = $result[0]['count_wrong'];
			//echo $estore_avision->last_query();
			
			$estore_avision->select('avg(count_correct) as count_correct,avg(count_wrong) as count_wrong');
			$estore_avision->from('student_full_test_sectional');
			$estore_avision->where('test_id',$quiz_id);
			$estore_avision->where('section_id',$row['question_type_id']);
			$result = $estore_avision->get()->result_array();
			$avg_count_correct = round($result[0]['count_correct'],2);
			$avg_count_wrong = round($result[0]['count_wrong'],2);
			
			/*$analysis_arr = array(
			'marks' => array('your' => $your_mark,'topper' => $topper_mark,'averge' => $average_mark),
			'correcr_count' => array('your' => $your_count_correct,'topper' => $topper_count_correct,'averge' => $avg_count_correct),
			'wrong_count' => array('your' => $your_count_wrong,'topper' => $topper_count_wrong,'averge' => $avg_count_wrong),
			);*/
				
			$analysis_arr[0] = array('analytical_type'=> 'mark','your' => $your_mark,'topper' => $topper_mark,'averge' => $average_mark);
			$analysis_arr[1] = array('analytical_type'=> 'Correct','your' => $your_count_correct,'topper' => $topper_count_correct,'averge' => $avg_count_correct);
			$analysis_arr[2] = array('analytical_type'=> 'Wrong','your' => $your_count_wrong,'topper' => $topper_count_wrong,'averge' => $avg_count_wrong);
			
			
			$section_arr[] = array(
			
				'section_name' => $row['question_type_name'],
				'section_id' => $row['question_type_id'],
				'section_anlysis_arr' => $analysis_arr
			
			);
			$analysis_arr = array();
		}
		//print_r($analysis_arr);
		//exit;
		return 	$section_arr;
	}

	public function get_product_buy_status($prodcut_id,$user_id) {
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("status");
		$estore_avision->where("product_id", $prodcut_id);
		$estore_avision->where("user_id", $user_id);
		$result = $estore_avision->get("product_buy_now")->result_array();
		
		
		return $result;
		
		
		
	}
	
	public function compare_with_topper($student_id,$quiz_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select('*');
		$estore_avision->from('quiz_durations qd');
		$estore_avision->join('question_type qt','qt.question_type_id = qd.question_type_id');
		$estore_avision->where('qd.quiz_id',$quiz_id);
		$result_section = $estore_avision->get()->result_array();
			
		$estore_avision->select('student_taken_tests_id');
		$estore_avision->from('student_full_tests');
		$estore_avision->where('test_id',$quiz_id);	
		$estore_avision->where('student_id',$student_id);
		$result_id = $estore_avision->get()->result_array();
		$test_your_taken_id = $result_id[0]['student_taken_tests_id'];
		
		$estore_avision->select('correct_mark,negative_mark,no_of_qs');
		$estore_avision->from('quiz_name');
		$estore_avision->where('quiz_id',$quiz_id);
		$result_quiz_mark = $estore_avision->get()->result_array();
		
		
		/****************your*****************************/	
		$estore_avision->select('sum(correct_marks) as correct_marks,sum(wrong_marks) as wrong_marks,sum(count_correct) as count_correct,sum(count_wrong) as count_wrong');
		$estore_avision->from('student_full_test_sectional');
		$estore_avision->where('test_taken_id',$test_your_taken_id);
		$result_marks = $estore_avision->get()->result_array();
		
		$total_obt_marks = 	$result_marks[0]['correct_marks'] - $result_marks[0]['wrong_marks'];
		
		
		$total_marks = $result_quiz_mark[0]['correct_mark'] * $result_quiz_mark[0]['no_of_qs'];
		
		$total_obt_mark_percent = round(($total_obt_marks/$total_marks)*100);
		
		$total_attempted = $result_marks[0]['count_correct'] + $result_marks[0]['count_wrong'];
		
		$total_correct = $result_marks[0]['count_correct'];
		$total_wrong = $result_marks[0]['count_wrong'];
		
		$accuracy = round(($total_correct/$total_attempted)*100);
		
		$total_correct_percent = round(($total_correct/$result_quiz_mark[0]['no_of_qs'])*100);
		$total_wrong_percent = round(($total_correct/$result_quiz_mark[0]['no_of_qs'])*100);
		$your_marks = array(
		
			'score' => $total_obt_mark_percent,
			'accuracy' => $accuracy,
			'total_correct_percent' => $total_correct_percent,
			'total_wrong_percent' => $total_wrong_percent,
			'total_marks' => $total_marks,
		);
		/****************your*****************************/	
		
		
		
		/**********************topper**********************************/
		
		$estore_avision->select('marks as max_marks,student_id');
		$estore_avision->from('student_full_test_result');
		$estore_avision->where('test_id',$quiz_id);
		$estore_avision->order_by('marks','desc');
		$estore_avision->limit(1);
		$result = $estore_avision->get()->result_array();
		
		$topper_mark = $result[0]['max_marks'];
		$topper_id = $result[0]['student_id'];
		$estore_avision->select('sum(correct_marks) as correct_marks,sum(wrong_marks) as wrong_marks,sum(count_correct) as count_correct,sum(count_wrong) as count_wrong');
		$estore_avision->from('student_full_test_sectional');
		$estore_avision->where('test_id',$quiz_id);
		$estore_avision->where('student_id',$topper_id);
		$result_marks_topper = $estore_avision->get()->result_array();
		
		
		$total_obt_marks_topper = 	$result_marks_topper[0]['correct_marks'] - $result_marks_topper[0]['wrong_marks'];
		$total_obt_mark_percent_topper = round(($total_obt_marks_topper/$total_marks)*100);
		
		$total_attempted_topper = $result_marks_topper[0]['count_correct'] + $result_marks_topper[0]['count_wrong'];
		
		$total_correct_topper = $result_marks_topper[0]['count_correct'];
		$total_wrong_topper = $result_marks_topper[0]['count_wrong'];
		
		$accuracy_topper = round(($total_correct_topper/$total_attempted_topper)*100);
		
		$total_correct_percent_topper = round(($total_correct_topper/$result_quiz_mark[0]['no_of_qs'])*100);
		$total_wrong_percent_topper = round(($total_correct_topper/$result_quiz_mark[0]['no_of_qs'])*100);
		
		$topper_marks = array(
		
			'score' => $total_obt_mark_percent_topper,
			'accuracy' => $accuracy_topper,
			'total_correct_percent' => $total_correct_percent_topper,
			'total_wrong_percent' => $total_wrong_percent_topper,
			'total_marks' => $total_marks,
		);
		/**********************topper**********************************/
		/****************************Average************************************/
		
		$estore_avision->select('avg(correct_marks) as correct_marks,avg(wrong_marks) as wrong_marks,avg(count_correct) as count_correct,avg(count_wrong) as count_wrong');
		$estore_avision->from('student_full_test_sectional');
		$estore_avision->where('test_id',$quiz_id);
		$result_marks_avg = $estore_avision->get()->result_array();
		
		$total_obt_marks_avg = 	$result_marks_avg[0]['correct_marks'] - $result_marks_avg[0]['wrong_marks'];
		$total_obt_mark_percent_avg = round(($total_obt_marks_avg/$total_marks)*100);
		
		$total_attempted_avg = $result_marks_avg[0]['count_correct'] + $result_marks_avg[0]['count_wrong'];
		
		$total_correct_avg = $result_marks_avg[0]['count_correct'];
		$total_wrong_avg = $result_marks_avg[0]['count_wrong'];
		
		$accuracy_avg = round(($total_correct_avg/$total_attempted_avg)*100);
		
		$total_correct_percent_avg = round(($total_correct_avg/$result_quiz_mark[0]['no_of_qs'])*100);
		$total_wrong_percent_avg = round(($total_correct_avg/$result_quiz_mark[0]['no_of_qs'])*100);
		
		$average_marks = array(
		
			'score' => $total_obt_mark_percent_avg,
			'accuracy' => $accuracy_avg,
			'total_correct_percent' => $total_correct_percent_avg,
			'total_wrong_percent' => $total_wrong_percent_avg,
			'total_marks' => $total_marks,
		);
		
		/****************************Average************************************/
		
		
		$compare_arr = array(
		
			'section_name' => 'all',
			'your_arr'	=> $your_marks,
			'topper_arr'	=> $topper_marks,
			'average_arr'	=> $average_marks,
			
		
		);
		
		return $compare_arr;
		
	}
	
	
	public function compare_with_section_section($student_id,$quiz_id,$section_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select('*');
		$estore_avision->from('quiz_durations qd');
		$estore_avision->join('question_type qt','qt.question_type_id = qd.question_type_id');
		$estore_avision->where('qd.quiz_id',$quiz_id);
		$estore_avision->where('qd.question_type_id',$section_id);
		$result_section = $estore_avision->get()->result_array();
			
		$estore_avision->select('student_taken_tests_id');
		$estore_avision->from('student_full_tests');
		$estore_avision->where('test_id',$quiz_id);	
		$estore_avision->where('student_id',$student_id);
		$result_id = $estore_avision->get()->result_array();
		$test_your_taken_id = $result_id[0]['student_taken_tests_id'];
		
		$estore_avision->select('correct_mark,negative_mark,no_of_qs');
		$estore_avision->from('quiz_name');
		$estore_avision->where('quiz_id',$quiz_id);
		$result_quiz_mark = $estore_avision->get()->result_array();
		
		
		/****************your*****************************/	
		$estore_avision->select('correct_marks as correct_marks,wrong_marks as wrong_marks,count_correct as count_correct,count_wrong as count_wrong');
		$estore_avision->from('student_full_test_sectional');
		$estore_avision->where('test_taken_id',$test_your_taken_id);
		$estore_avision->where('section_id',$section_id);
		$result_marks = $estore_avision->get()->result_array();
		
		$total_obt_marks = 	$result_marks[0]['correct_marks'] - $result_marks[0]['wrong_marks'];
		
		
		$total_marks = $result_section[0]['total_question'] * $result_quiz_mark[0]['correct_mark'];
		
		$total_obt_mark_percent = round(($total_obt_marks/$total_marks)*100);
		
		$total_attempted = $result_marks[0]['count_correct'] + $result_marks[0]['count_wrong'];
		if($total_attempted == 0){
		    $total_attempted = 1;
		}
		$total_correct = $result_marks[0]['count_correct'];
		$total_wrong = $result_marks[0]['count_wrong'];
		
		$accuracy = round(($total_correct/$total_attempted)*100);
		
		$total_correct_percent = round(($total_correct/$result_section[0]['total_question'])*100);
		$total_wrong_percent = round(($total_correct/$result_section[0]['total_question'])*100);
		$your_marks = array(
		
			'score' => $total_obt_mark_percent,
			'accuracy' => $accuracy,
			'total_correct_percent' => $total_correct_percent,
			'total_wrong_percent' => $total_wrong_percent,
			'total_marks' => $total_marks,
		);
		/****************your*****************************/	
		
		
		
		/**********************topper**********************************/
		
		$estore_avision->select('marks as max_marks,student_id');
		$estore_avision->from('student_full_test_result');
		$estore_avision->where('test_id',$quiz_id);
		$estore_avision->order_by('marks','desc');
		$estore_avision->limit(1);
		$result = $estore_avision->get()->result_array();
		
		$topper_mark = $result[0]['max_marks'];
		$topper_id = $result[0]['student_id'];
		$estore_avision->select('correct_marks as correct_marks,wrong_marks as wrong_marks,count_correct as count_correct,count_wrong as count_wrong');
		$estore_avision->from('student_full_test_sectional');
		$estore_avision->where('test_id',$quiz_id);
		$estore_avision->where('student_id',$topper_id);
		$estore_avision->where('section_id',$section_id);
		$result_marks_topper = $estore_avision->get()->result_array();
		
		
		$total_obt_marks_topper = 	$result_marks_topper[0]['correct_marks'] - $result_marks_topper[0]['wrong_marks'];
		$total_obt_mark_percent_topper = round(($total_obt_marks_topper/$total_marks)*100);
		
		$total_attempted_topper = $result_marks_topper[0]['count_correct'] + $result_marks_topper[0]['count_wrong'];
		
		if($total_attempted_topper == 0){
			$total_attempted_topper =1;
		}
		
		$total_correct_topper = $result_marks_topper[0]['count_correct'];
		$total_wrong_topper = $result_marks_topper[0]['count_wrong'];
		
		$accuracy_topper = round(($total_correct_topper/$total_attempted_topper)*100);
		
		$total_correct_percent_topper = round(($total_correct_topper/$result_section[0]['total_question'])*100);
		$total_wrong_percent_topper = round(($total_correct_topper/$result_section[0]['total_question'])*100);
		
		$topper_marks = array(
		
			'score' => $total_obt_mark_percent_topper,
			'accuracy' => $accuracy_topper,
			'total_correct_percent' => $total_correct_percent_topper,
			'total_wrong_percent' => $total_wrong_percent_topper,
			'total_marks' => $total_marks,
		);
		/**********************topper**********************************/
		/****************************Average************************************/
		
		$estore_avision->select('avg(correct_marks) as correct_marks,avg(wrong_marks) as wrong_marks,avg(count_correct) as count_correct,avg(count_wrong) as count_wrong');
		$estore_avision->from('student_full_test_sectional');
		$estore_avision->where('test_id',$quiz_id);
		$estore_avision->where('section_id',$section_id);
		$result_marks_avg = $estore_avision->get()->result_array();
		
		$total_obt_marks_avg = 	$result_marks_avg[0]['correct_marks'] - $result_marks_avg[0]['wrong_marks'];
		$total_obt_mark_percent_avg = round(($total_obt_marks_avg/$total_marks)*100);
		
		$total_attempted_avg = $result_marks_avg[0]['count_correct'] + $result_marks_avg[0]['count_wrong'];
		
		$total_correct_avg = $result_marks_avg[0]['count_correct'];
		$total_wrong_avg = $result_marks_avg[0]['count_wrong'];
		
		$accuracy_avg = round(($total_correct_avg/$total_attempted_avg)*100);
		
		$total_correct_percent_avg = round(($total_correct_avg/$result_section[0]['total_question'])*100);
		$total_wrong_percent_avg = round(($total_correct_avg/$result_section[0]['total_question'])*100);
		
		$average_marks = array(
		
			'score' => $total_obt_mark_percent_avg,
			'accuracy' => $accuracy_avg,
			'total_correct_percent' => $total_correct_percent_avg,
			'total_wrong_percent' => $total_wrong_percent_avg,
			'total_marks' => $total_marks,
		);
		
		/****************************Average************************************/
		
		
		$compare_arr = array(
		
			'section_name' => 'all',
			'your_arr'	=> $your_marks,
			'topper_arr'	=> $topper_marks,
			'average_arr'	=> $average_marks,
			
		
		);
		
		return $compare_arr;
	}
	
	public function submit_exam($test_taken_id, $student_id, $status,$quiz_id) {
		
		date_default_timezone_set("Asia/Kolkata");
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->where("student_taken_tests_id", $test_taken_id);
		$estore_avision->where("student_id", $student_id);
		$estore_avision->update("student_full_tests", array('status' => $status, 'end_time' => date("H:i:s")));
		
		$estore_avision->select('correct_mark,negative_mark,no_of_qs');
		$estore_avision->from('quiz_name');
		$estore_avision->where('quiz_id',$quiz_id);
		$result_quiz_mark = $estore_avision->get()->result_array();
		
		$estore_avision->select('question_id');
		$estore_avision->from('question');
		$estore_avision->where('quiz_id',$quiz_id);
		$result_qn_id = $estore_avision->get()->result_array();
		$questiin_id_arr = array();
		
		foreach($result_qn_id as $row_qn_id){
			
			array_push($questiin_id_arr,$row_qn_id['question_id']);
		}
		
		$estore_avision->select('*');
		$estore_avision->from('student_full_tests_question_temp');
		$estore_avision->where('test_taken_id',$test_taken_id);
		$result_taken_qn_id = $estore_avision->get()->result_array();
		
		$skip_count = count($questiin_id_arr) - count($result_taken_qn_id);
		
		$estore_avision->select('*');
		$estore_avision->from('quiz_durations qd');
		$estore_avision->where('qd.quiz_id',$quiz_id);
		$result_qn_type = $estore_avision->get()->result_array();
		
		$skip=0;
		$correct=0;
		$wrong=0;
		foreach($result_qn_type as $row_qn_type){
			
			$estore_avision->select('question_id');
			$estore_avision->from('question');
			$estore_avision->where('quiz_id',$quiz_id);
			$estore_avision->where('question_type_id',$row_qn_type['question_type_id']);
			$question = $estore_avision->get()->result_array();
			
			$qn_arr = array();
			
			foreach($question as $row_qn){
				
				array_push($qn_arr,$row_qn['question_id']);
			}
			
			$estore_avision->select('test_question_id');
			$estore_avision->from('student_full_tests_question_temp');
			$estore_avision->where_in('test_question_id',$qn_arr);
			$estore_avision->where_in('test_taken_id',$test_taken_id);
			$result_qn_cnt = $estore_avision->get()->result_array();
			
			$skip = count($question) - count($result_qn_cnt);
			
			foreach($question as $row_ques){
				$estore_avision->select('a.status');
				$estore_avision->from('student_full_tests_question_temp sft');
				$estore_avision->join('student_full_tests_answers_temp sfa','sfa.test_question_id=sft.test_question_id');
				$estore_avision->join('answers a','a.ans_id=sfa.asnwer_id');
				$estore_avision->where('sfa.test_taken_id',$test_taken_id);
				$estore_avision->where('sft.test_taken_id',$test_taken_id);
				$estore_avision->where('sft.test_question_id',$row_ques['question_id']);
				$result_anser = $estore_avision->get()->result_array();
				
				if(!empty($result_anser)){
					if($result_anser[0]['status'] == 1){
						$correct++;
					}else{
						$wrong++;
					}
				}
				
			
			}
			$correct_marks = $correct * $result_quiz_mark[0]['correct_mark'];
			$wrong_marks = floatval($wrong) * floatval($result_quiz_mark[0]['negative_mark']);
			$data=array(
			
				'test_taken_id' => $test_taken_id,
				'student_id' => $student_id,
				'section_id' => $row_qn_type['question_type_id'],
				'test_id' => $quiz_id,
				'correct_marks' => $correct_marks,
				'wrong_marks' => $wrong_marks,
				'count_correct' => $correct,
				'count_wrong' => $wrong,
				'count_skipped' => $skip,
				'created_date' => date('Y-m-d')
			);
			$estore_avision->select('*');
			$estore_avision->from('student_full_test_sectional');
			$estore_avision->where('student_id',$student_id);
			$estore_avision->where('test_id',$quiz_id);
			$estore_avision->where('test_taken_id',$test_taken_id);
			$estore_avision->where('section_id',$row_qn_type['question_type_id']);
			$result_full_sec_test = $estore_avision->get()->result_array();
			if(count($result_full_sec_test) == 0){
				$estore_avision->insert('student_full_test_sectional',$data);
			}
			$skip=0;
			$correct=0;
			$wrong = 0;
		}
		
		$estore_avision->select('sum(correct_marks) as correct_marks,sum(wrong_marks) as wrong_marks');
		$estore_avision->from('student_full_test_sectional');
		$estore_avision->where('test_taken_id',$test_taken_id);
		$result_full = $estore_avision->get()->result_array();
		
		$total_marks = $result_full[0]['correct_marks'] - $result_full[0]['wrong_marks'];
		
		$data_full = array(
		
			'test_id' => $quiz_id,
			'student_id' => $student_id,
			'marks' => $total_marks,
			'created_date' => date('Y-m-d')
		);
		
		$estore_avision->select('*');
		$estore_avision->from('student_full_test_result');
		$estore_avision->where('student_id',$student_id);
		$estore_avision->where('test_id',$quiz_id);
		$result_full_test = $estore_avision->get()->result_array();
		if(count($result_full_test) == 0){
			$estore_avision->insert('student_full_test_result',$data_full);
		}
	
		if($estore_avision->affected_rows() > 0){
		return 1;
		}else{
		return 0;
		}
	}
	
	public function test_result($test_taken_id,$student_id,$quiz_id){
			$estore_avision = $this->load->database('estore_avision', TRUE);
			$estore_avision->select('*');
			$estore_avision->from('student_full_test_sectional sft');
			$estore_avision->join('question_type qt','qt.question_type_id = sft.section_id');
			$estore_avision->where('student_id',$student_id);
			$estore_avision->where('test_id',$quiz_id);
			$estore_avision->where('test_taken_id',$test_taken_id);
			$result_full_sec_test = $estore_avision->get()->result_array();
			
			return $result_full_sec_test;
	}
	
	
	public function get_quiz_question_sol($quiz_id, $question_type_id,$test_taken_id) {
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("q.question_id, q.directions, q.question, q.question_type_id, q.question_img, q.directions_img, q.directions_status,q.ans_desc");
		$estore_avision->from("question q");
		$estore_avision->where("quiz_id", $quiz_id);
		$estore_avision->where("q.question_type_id", $question_type_id);
		$estore_avision->order_by("q.question_id", "asc");
		$result = $estore_avision->get()->result_array();

		$final_array = [];
		foreach ($result as $key =>  $value) {

		$estore_avision->select('question_status');
		$estore_avision->from('student_full_tests_question_temp');
		$estore_avision->where('test_question_id',$value['question_id']);
		$estore_avision->where('test_taken_id',$test_taken_id);
		$result_qn = $estore_avision->get()->result_array();
		if(!empty($result_qn)){

		$result[$key]['question_status'] = $result_qn[0]['question_status'];


		}else{
		$result[$key]['question_status'] = 0;
		}
		
		$estore_avision->select("ans,ans_id,status");
		$estore_avision->from("answers");
		$estore_avision->where("question_id", $value['question_id']);
		$res_ans = $estore_avision->get()->result_array();
		
		$estore_avision->select("a.ans,a.ans_id,a.status");
		$estore_avision->from("answers a");
		$estore_avision->join("student_full_tests_answers_temp sfta","sfta.asnwer_id = a.ans_id");
		$estore_avision->where("sfta.test_question_id", $value['question_id']);
		$estore_avision->where("sfta.test_taken_id", $test_taken_id);
		$res = $estore_avision->get()->result_array();

		$final_array[] = array(
		'question_details' => $result[$key],
		'answers_list'=> $res_ans,
		'student_ans' => $res	
		);


		}

		return $final_array;


	}
	
	public function teacherdetails(){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select('*');
		$estore_avision->from('teacher_login');
		$estore_avision->where('status',1);
		$result = $estore_avision->get()->result_array();
		return $result;
		
	}
	
	/*public function centerList(){
		
		$avision_group = $this->load->database('avision_group',TRUE);
		$result = $avision_group->query("SELECT `wp_p`.`ID`, `wp_p`.`post_title`, `wp_ps`.`meta_value` FROM `wp_posts` as `wp_p` INNER JOIN `wp_postmeta` AS `wp_ps` ON `wp_p`.`ID` = `wp_ps`.`post_id` WHERE `wp_p`.`post_type` = 'center' AND `wp_p`.`post_status` = 'publish' AND `wp_ps`.`meta_key` = 'adress'")->result_array();
		
		return $result;
		
	}*/
	
	public function centerList(){
	    
	    $estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select('*');
		$estore_avision->from('avision_center');
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function centerDetails($page_slug){
	    
	    $estore_avision = $this->load->database('estore_avision', TRUE);
	    $estore_avision->select('*');
		$estore_avision->from('avision_center ac');
		$estore_avision->where('ac.slug',$page_slug);
		$result = $estore_avision->get()->result_array();
		
		return $result;
	    
	}
	
	
	public function centerMetaContent($page_slug){
	    
	    $estore_avision = $this->load->database('estore_avision', TRUE);
	    
	    $estore_avision->select('acm.meta_id,acm.meta_name');
		$estore_avision->from('avision_center ac');
	    $estore_avision->join('avision_center_meta acm','acm.center_id = ac.center_id');
		$estore_avision->where('ac.slug',$page_slug);
		$result = $estore_avision->get()->result_array();
		
		
		return $result;
	}
	
	public function centerSubmetaContent($meta_id){
	    
	   $estore_avision = $this->load->database('estore_avision', TRUE);
	    
	    $estore_avision->select('acsm.submeta_id,acsm.submeta_name,acc.center_content');
		$estore_avision->from('avision_center_sub_meta acsm');
		$estore_avision->join('avision_center_content acc','acc.meta_id = acsm.meta_id and acc.submeta_id = acsm.submeta_id');
		$estore_avision->where('acsm.meta_id',$meta_id);
		$result = $estore_avision->get()->result_array();
		return $result; 
	}
	
	public function get_product_id($slug){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select('product_id');
		$estore_avision->from('product');
		$estore_avision->where('product_slug',$slug);
		$result = $estore_avision->get()->result_array();
		return $result;
	}
	
	public function submit_enquiry($fullname,$email,$phoneNo,$examName,$message) {
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$exam_name = implode(",",$examName);
		
		$estore_avision->insert("admision_enquiry", array('user_name' =>$fullname, 'user_email'=>$email, 'user_phone'=> $phoneNo, 'exam_name'=>$exam_name, 'message'=>$message, 'created_date'=> date("Y-m-d") ));

		if($estore_avision->affected_rows() > 0){
			$to = "avisioninstitute@gmail.com";
			$subject = "Enquiry For Avision";

			$message = "
			<html>
			<head>
			<title>Enquiry</title>
			</head>
			<body>
			<p>Enquiry For Avision!</p>
			<table>
			<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Mobile</th>
			<th>Course</th>
			<th>Message</th>
			</tr>
			<tr>
			<td>".$fullname."</td>
			<td>".$email."</td>
			<td>".$phoneNo."</td>
			<td>".$exam_name."</td>
			<td>".$message."</td>
			</tr>
			</table>
			</body>
			</html>
			";

			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			

			mail($to,$subject,$message,$headers);	
		return 1;
		}else{
		return 0;
		}
	}
	
	public function get_courses_name() {
		$estore_avision = $this->load->database('estore_avision', TRUE);

		$estore_avision->select("sub_courses_name, sub_courses_desc");
		$result = $estore_avision->get("sub_courses")->result_array();
		if($estore_avision->affected_rows() > 0){
		return $result;
		}else{
		return 0;
		}
	}
	
	public function getCourseId($slug){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);

		$estore_avision->select("sub_category_id");
		$estore_avision->where("sub_cat_slug",$slug);
		$result = $estore_avision->get("sub_category")->result_array();
		if($estore_avision->affected_rows() > 0){
			return $result[0]['sub_category_id'];
		
		}else{
			return 0;
		}
	}
	
	public function study_list(){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select('type_id,type_name,type_img');
		$estore_avision->from('add_question_type');
		$result = $estore_avision->get()->result_array();

		return $result;
	}
	
	public function get_chapter_list($sub_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select('*');
		$estore_avision->from('chapter');
		$estore_avision->where('parent_subject_id',$sub_id);
		$estore_avision->order_by('LENGTH(order_id),order_id','asc');
		$result = $estore_avision->get()->result_array();
	   
		return $result;	
	}
	
	public function get_quiz_information_resume($quiz_id, $user_id,$test_taken_id) {
			$estore_avision = $this->load->database('estore_avision', TRUE);

			$estore_avision->select('start_time, end_time');
			$estore_avision->from("student_full_tests");
			$estore_avision->where("student_taken_tests_id", $test_taken_id);
			$result  = $estore_avision->get()->result_array();

			$start_time = strtotime($result[0]['start_time']);
			$end_time = strtotime($result[0]['end_time']);
			$start_time = new DateTime($result[0]['start_time']);
			$end_time = new DateTime($result[0]['end_time']);
			$interval = $start_time->diff($end_time);
			 $h = $interval->format('%h');
			 $m = $interval->format('%i');
			 $s = $interval->format('%s');
			 $toal_left_time = ($h*60) + $m + ($s/100);
			 
			$estore_avision->select('changable');
			$estore_avision->from("quiz_name");
			$estore_avision->where("quiz_id", $quiz_id);
			$result_quiz_change  = $estore_avision->get()->result_array(); 
			
			$changable = $result_quiz_change[0]['changable'];
			if($changable == 0){
				$estore_avision->select('*');
				$estore_avision->from("quiz_durations");
				$estore_avision->where("quiz_id", $quiz_id);
				$result_quiz_duration  = $estore_avision->get()->result_array();

				$duration = 0;
				$updated_duration = 0;

				foreach ($result_quiz_duration as $key =>  $value) {
				$duration = $duration + intval($value['duration']);

				if( $duration > intval($toal_left_time)) {

				$res[] = $value['question_type_id'];
				}
				}



				$estore_avision = $this->load->database('estore_avision', TRUE);
				$estore_avision->select('quiz_name, no_of_qs, duration, changable, correct_mark, negative_mark');
				$estore_avision->from("quiz_name");
				$estore_avision->where("quiz_id", $quiz_id);
				$res2 = $estore_avision->get()->result_array();




				foreach ($res as  $value) {
				$estore_avision->select('qd.duration,  qt.question_type_name, qd.question_type_id, qd.total_question');
				$estore_avision->from("quiz_name qn");
				$estore_avision->join("quiz_durations qd", "qd.quiz_id = qn.quiz_id");
				$estore_avision->join("question_type qt", "qt.question_type_id = qd.question_type_id");
				$estore_avision->where("qn.quiz_id", $quiz_id);
				$estore_avision->where("qd.question_type_id",  $value);
				$result = $estore_avision->get()->result_array();
				$final_res_arr[] = array($result);
				}



				function foo($seconds) {
				 $t = round($seconds);
				 return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
				}


				$estore_avision->select('qd.duration,  qt.question_type_name, qd.question_type_id, qd.total_question');
				$estore_avision->from("quiz_name qn");
				$estore_avision->join("quiz_durations qd", "qd.quiz_id = qn.quiz_id");
				$estore_avision->join("question_type qt", "qt.question_type_id = qd.question_type_id");
				$estore_avision->where("qn.quiz_id", $quiz_id);

				$result2 = $estore_avision->get()->result_array();
				$final_res_arr2 = array($result2);


				// print_r($final_res_arr2);
				// echo count($final_res_arr2);
				// exit();




				$get_time_left = 0;
				$get_time_left_updated = 0;

				 $toal_left_time1 = $h.":".$m.":".$s;

				 //print_r($final_res_arr);
				$updated_duration = $final_res_arr2[0][0]['duration'];
				$count =0;
				$flag=0;
				for ($i=0; $i < count($final_res_arr2[0]) ; $i++) {

				$tab_duration = $final_res_arr2[0][$i]['duration'];

				if( $updated_duration > intval($toal_left_time)) {

				$time1 = new DateTime(foo($updated_duration*60));
				//print_r($time1);
				$time2 = new DateTime($toal_left_time1);
				//print_r($time2);
				$interval = $time1->diff($time2);
				$get_time_left = $interval->format('%h:%i:%s');
				//echo $get_time_left;
				//$get_time_left_updated = $get_time_left;
				// $time12 = new DateTime(foo($tab_duration*60));

				// //echo $get_time_left;
				// $time22 = new DateTime($get_time_left);
				// $interval = $time12->diff($time22);
				// echo $get_time_left_updated = $interval->format('%h:%i:%s');
				$count++;
				$flag=1;

				}
				else {
				$updated_duration = $updated_duration + $final_res_arr2[0][$i]['duration'];
				$count++;
				}
				//echo $count;

				// if($count != 1){

				// $time12 = new DateTime(foo($tab_duration*60));

				// //echo $get_time_left;
				// $time22 = new DateTime($get_time_left);
				// $interval = $time12->diff($time22);
				// $get_time_left_updated = $interval->format('%h:%i:%s');

				// }

				if($flag == 1){
				break;
				}




				}


				$quiz_details_arr = array();

				$get_final_array = [];

				for ($i=0; $i < count($final_res_arr); $i++) {


				if($i == 0) {


				$quiz_details_arr[] =array(
				'duration' => $final_res_arr[$i][0][0]['duration'],
				'question_type_name' => $final_res_arr[$i][0][0]['question_type_name'],
				'question_type_id' => $final_res_arr[$i][0][0]['question_type_id'],
				'total_question' => $final_res_arr[$i][0][0]['total_question'],
				'left_time' =>  $get_time_left,
				'left_time_status' => 1
				);
				}

				else {
				$quiz_details_arr[] =array(
				'duration' => $final_res_arr[$i][0][0]['duration'],
				'question_type_name' => $final_res_arr[$i][0][0]['question_type_name'],
				'question_type_id' => $final_res_arr[$i][0][0]['question_type_id'],
				'total_question' => $final_res_arr[$i][0][0]['total_question'],
				'left_time_status' => 0
				);
				}
				}


				$get_final_array = array(
				'quiz_info' => $res2,
				'quiz_details'=> $quiz_details_arr,
				'quiz_tab_list'=> $final_res_arr2
				);
				
					
					return $get_final_array;
			}else{
				
				/*$estore_avision->select('*');
				$estore_avision->from("quiz_durations");
				$estore_avision->where("quiz_id", $quiz_id);
				$result_quiz_duration  = $estore_avision->get()->result_array();

				$duration = 0;
				$updated_duration = 0;

				foreach ($result_quiz_duration as $key =>  $value) {
				$duration = $duration + intval($value['duration']);

				if( $duration > intval($toal_left_time)) {

				$res[] = $value['question_type_id'];
				}
				}*/



				$estore_avision = $this->load->database('estore_avision', TRUE);
				$estore_avision->select('quiz_name, no_of_qs, duration, changable, correct_mark, negative_mark');
				$estore_avision->from("quiz_name");
				$estore_avision->where("quiz_id", $quiz_id);
				$res2 = $estore_avision->get()->result_array();




				
				$estore_avision->select('qd.duration,  qt.question_type_name, qd.question_type_id, qd.total_question');
				$estore_avision->from("quiz_name qn");
				$estore_avision->join("quiz_durations qd", "qd.quiz_id = qn.quiz_id");
				$estore_avision->join("question_type qt", "qt.question_type_id = qd.question_type_id");
				$estore_avision->where("qn.quiz_id", $quiz_id);
				$result = $estore_avision->get()->result_array();
				$final_res_arr[] = $result;
				
				//echo "<pre/>";
				//print_r($final_res_arr);
				//exit;

				
				function foo($seconds) {
				 $t = round($seconds);
				 return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
				}


				$estore_avision->select('qn.duration,  qt.question_type_name, qd.question_type_id, qd.total_question');
				$estore_avision->from("quiz_name qn");
				$estore_avision->join("quiz_durations qd", "qd.quiz_id = qn.quiz_id");
				$estore_avision->join("question_type qt", "qt.question_type_id = qd.question_type_id");
				$estore_avision->where("qn.quiz_id", $quiz_id);

				$result2 = $estore_avision->get()->result_array();
				$final_res_arr2 = array($result2);


				// print_r($final_res_arr2);
				// echo count($final_res_arr2);
				// exit();




				$get_time_left = 0;
				$get_time_left_updated = 0;

				 $toal_left_time1 = $h.":".$m.":".$s;

				 //print_r($final_res_arr);
				$updated_duration = $final_res_arr2[0][0]['duration'];
				$time1 = new DateTime(foo($updated_duration*60));
				//print_r($time1);
				$time2 = new DateTime($toal_left_time1);
				//print_r($time2);
				$interval = $time1->diff($time2);
				$get_time_left = $interval->format('%h:%i:%s');
				


				$quiz_details_arr = array();

				$get_final_array = [];
				//echo count($final_res_arr);
				for ($i=0; $i < count($final_res_arr[0]); $i++) {

					$quiz_details_arr[] =array(
					'question_type_name' => $final_res_arr[0][$i]['question_type_name'],
					'question_type_id' => $final_res_arr[0][$i]['question_type_id'],
					'total_question' => $final_res_arr[0][$i]['total_question'],
					'left_time' =>  $get_time_left,
					'left_time_status' => 1
					);
				
				}


				$get_final_array = array(
				'quiz_info' => $res2,
				'quiz_details'=> $quiz_details_arr,
				'quiz_tab_list'=> $final_res_arr2
				);
				
					/* echo "<pre/>";
					print_r($get_final_array);
					exit; */
					return $get_final_array;
			}
	}
	
	public function page_sectional_banner($page_id){
			
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select('banner_url');
		$estore_avision->from('page_sectional_banner');
		$estore_avision->where('page_id',$page_id);
		$result = $estore_avision->get()->result_array();
	   
		return $result;	
	}
	
	public function homePopupBanner(){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select('popoup_status,pop_up_banner');
		$estore_avision->from('page_meta');
		$estore_avision->where('page_id',14);
		$result = $estore_avision->get()->result_array();
		
		return $result;
	}
	
	public function testSeriesBuyStat($user_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select('status');
		$estore_avision->from('product_buy_now');
		$estore_avision->where('user_id',$user_id);
		$estore_avision->where('product_type',2);
		$estore_avision->where('status',1);
		$result = $estore_avision->get()->result_array();
		
		return $result;
	}
	
	
	
	public function login_data_scholarship($loginData){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select("user_id");
		$estore_avision->from("users");
		$estore_avision->where("user_password",md5($loginData['user_password']));
		$or_where='user_phone="'.$loginData['user_email'].'" or user_email="'.$loginData['user_email'].'"';
		$estore_avision->where($or_where);
		$result = $estore_avision->get()->result_array();
		if(count($result) > 0){
			
			$user_id = $result[0]['user_id'];
			return $user_id;
		}else{
			return 0;
		}
	}
	
	public function user_name_scholarship($user_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select('user_name,user_phone,user_email');
		$estore_avision->from('users');
		$estore_avision->where('user_id',$user_id);
		$result = $estore_avision->get()->result_array();
		return $result;
		
	}
	
	public function get_user_data_by_phone($phone_no) {
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("user_id, user_name, user_email, user_phone");
		$estore_avision->where("user_phone", $phone_no);
		$result = $estore_avision->get("users_scholarship")->result_array();
		return $result;
	}

	public function add_user_data_by_phone($email, $name, $phone) {
		date_default_timezone_set("Asia/Kolkata");
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->insert("users_scholarship", array('user_name' => $name, 'user_email'=> $email, 'user_phone'=> $phone, 'user_group'=> 1, 'user_access'=> 1, 'user_role'=> 's', 'student_area'=> 3, 'created_date' => date("Y-m-d") ));
		$user_id = $estore_avision->insert_id();

		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("user_id, user_name, user_email, user_phone");
		$estore_avision->where("user_id", $user_id);
		$result = $estore_avision->get("users_scholarship")->result_array();
		return $result;
	}

	public function signup_wiith_password($email, $name, $password, $phone) {

		date_default_timezone_set("Asia/Kolkata");
		$estore_avision = $this->load->database('estore_avision', TRUE);

		$estore_avision->select("user_id");
		$estore_avision->where("user_email", $email);
		$result = $estore_avision->get("users")->result_array();

		if($estore_avision->affected_rows() > 0){
		return 0;
		}

		$estore_avision->select("user_id");
		$estore_avision->where("user_phone", $phone);
		$result = $estore_avision->get("users")->result_array();

		if($estore_avision->affected_rows() > 0){
		return 0;
		}

		date_default_timezone_set("Asia/Kolkata");
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->insert("users", array('user_name' => $name, 'user_email'=> $email, 'user_phone'=> $phone, 'user_group'=> 1, 'user_access'=> 1, 'user_role'=> 's', 'student_area'=> 3, 'user_password'=>md5($password),'user_scholarship_stat'=>1,'created_date' => date("Y-m-d") ));
		$user_id = $estore_avision->insert_id();
		
		/*$to = $email;
			$subject = "All India Talent Search Exam For Avision";

			$message = "
			<html>
			<head>
			<title>Talent Search Exam For Avision</title>
			</head>
			<body>
			<h5>Dear ".$name."</h5>
			<p>Greetings From AVISION</p>

				<p>It gives us immense pleasure to announce the much-awaited Scholarship Test - AVISION Talent Search Examination (ATSE). The ATSE will be conducted on the 15th of September, 2020 (TUESDAY) in two slots. The ATSE is open to students who wish to prepare for BANK – PO/CLERK 2020/21. Based on performance, students writing this examination will become eligible for a certain amount of Scholarship on our BANK 2020/21 Full Course fee (Terms & Conditions Apply).

				    Top 5 Performers will get 100% Scholarship.</p>

				<p>The ATSE will be conducted in an online mode and can be taken from the comfort of your home.</p>

				<p>The test Areas: Quantitative Ability, Logical Ability and Verbal Ability</p>

				<p style='color: #000;font-weight: 600;'>Test Date: 15th of September 2020.</p>

				<p style='color: #000;font-weight: 600;'>Test Slots: 12:00 & 5:00 pm slot (Student is requested to register for only one preferred slot)</p>

				<p style='color: #000;font-weight: 600;'>Test duration: 1 hour.</p>

				<p>A special aspect of this ATSE is that every student writing it and enrolling for the BANK 2020/21 course between 5th September to 15th of September,2020 gets a minimum assured discount of 20% (T&C Apply). To grab this opportunity and get yourself a minimum assured discount for BANK 2020/21, contact the AVISION office near you.</p>

				 

				<p>You can register for the ATSE by registering online. In case you have any further clarifications, please feel free to contact us at avisioninstitute@gmail.com</p>

				<p>Ph-No: 9088479999 OR 9073386602</p>

				                

				<p>We strongly recommend that you make the best use of this wonderful opportunity!</p>

				<p>Heres wishing you all the very best!</p>
                <p style='color: #000;font-weight: 600;'>Note : - To register in our Avision's Scholarship Test, please visit our website avision.co.in and click on to pop window of AVISION TALENT SEARCH EXAM (ATSE) and fill up the registration fields as required or you can just click on test series section and click on the register below.</p>
				<p>Team AVISION</p>
			</body>
			</html>
			";

			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= "From: avisioninstitute@gmail.com\r\n";
            $headers .= "Reply-To: avisioninstitute@gmail.com\r\n";
            $headers .= "Return-Path: avisioninstitute@gmail.com\r\n";
            $headers .= "CC: avisioninstitute@gmail.com\r\n";
            $headers .= "BCC: avisioninstitute@gmail.com\r\n";
			

			mail($to,$subject,$message,$headers);	*/

		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("user_id, user_name, user_email, user_phone");
		$estore_avision->where("user_id", $user_id);
		$result = $estore_avision->get("users")->result_array();
		return $result;

	}
	
	public function get_banner($table){
	    
	    $estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select('*');
		$estore_avision->from($table);
		$estore_avision->where('activate_image',1);
		$result= $estore_avision->get()->result();
		
		return $result;
	}
	
	public function get_free_quiz_question_answer($quiz_id) {
$estore_avision = $this->load->database('estore_avision', TRUE);
$estore_avision->select("quiz_id, quiz_base, quiz_name, type_id, tot_qus");
$estore_avision->where("quiz_id", $quiz_id);
$res = $estore_avision->get("small_quiz")->result_array();

$estore_avision->select("q.question_id, q.question, q.sol_desc");
$estore_avision->from("small_quiz_question q");
$estore_avision->where("q.quiz_id", $quiz_id);
$result = $estore_avision->get()->result_array();

$final_array = [];

foreach ($result as $key => $value) {
$estore_avision->select("answer, answer_status, answer_id");
$estore_avision->where("question_id", $value['question_id']);
$answers = $estore_avision->get("small_quiz_answer")->result_array();
$final_array[] = array(
'question_details' => $result[$key],
'answers_list'=> $answers
);
}

$final_res = array(
'quiz_info'=> $res,
'quiz_question_answer' => $final_array
);


return $final_res;
}


public function get_free_quiz_list_all($limit,$user_id) {
	$estore_avision = $this->load->database('estore_avision', TRUE);

	$estore_avision->distinct("test_id");
	$estore_avision->select("test_id");
	$estore_avision->where("student_id", $user_id);
	$test_ids = $estore_avision->get("student_taken_topic_tests_temp")->result_array();


	$ids = [];
	foreach ($test_ids as $key => $value) {
	array_push($ids, $value['test_id']);
	}





	$estore_avision->select("quiz_id, quiz_base, quiz_name, type_id, tot_qus");
	$estore_avision->where_not_in("quiz_id", $ids);
	$estore_avision->order_by("created_date","desc");
	$estore_avision->limit(12,$limit);
	$res = $estore_avision->get("small_quiz")->result_array();

	return $res;
}

public function get_free_quiz_list_all_no_user($limit) {
$estore_avision = $this->load->database('estore_avision', TRUE);
$estore_avision->select("quiz_id, quiz_base, quiz_name, type_id, tot_qus");
$estore_avision->order_by("created_date","desc");
$estore_avision->limit(12,$limit);
$res = $estore_avision->get("small_quiz")->result_array();
return $res;
}

public function get_small_quiz_question_type() {

$estore_avision = $this->load->database('estore_avision', TRUE);
$estore_avision->select("type_id, type_name");
$res = $estore_avision->get("small_question_type")->result_array();
return $res;


}

public function get_free_quiz_list_by_id($id,$limit) {

$estore_avision = $this->load->database('estore_avision', TRUE);
$estore_avision->select("quiz_id, quiz_base, quiz_name, type_id, tot_qus");
$estore_avision->order_by("quiz_id","desc");
$estore_avision->where("type_id", $id);
$estore_avision->limit(12,$limit);
$res = $estore_avision->get("small_quiz")->result_array();

return $res;
}

public function add_topic_tests($quiz_id, $student_id) {
date_default_timezone_set("Asia/Kolkata");
$estore_avision = $this->load->database('estore_avision', TRUE);
$estore_avision->insert("student_taken_topic_tests_temp", array('test_id' => intval($quiz_id), 'student_id'=>   intval($student_id), 'status'=> 1, 'start_time'=> date("H:i:s"), 'created_date' => date("Y-m-d")));
return $estore_avision->insert_id();

}

public function add_free_quiz_student_question($test_question_id, $test_taken_id, $question_status) {
date_default_timezone_set("Asia/Kolkata");
$estore_avision = $this->load->database('estore_avision', TRUE);
$estore_avision->insert("student_topic_tests_question_temp", array('test_question_id' => $test_question_id, 'test_taken_id'=> $test_taken_id, 'question_status'=> $question_status, 'created_date' => date("Y-m-d") ));
return $estore_avision->insert_id();

}


public function add_free_quiz_student_answer($test_question_id, $test_taken_id, $asnwer_id) {
date_default_timezone_set("Asia/Kolkata");
$estore_avision = $this->load->database('estore_avision', TRUE);
$estore_avision->insert("student_topic_tests_answers_temp", array('test_question_id' => $test_question_id, 'test_taken_id'=> $test_taken_id, 'asnwer_id'=> $asnwer_id, 'created_date' => date("Y-m-d") ));
return $estore_avision->insert_id();
}


public function submit_free_quiz($test_taken_id) {

date_default_timezone_set("Asia/Kolkata");
$estore_avision = $this->load->database('estore_avision', TRUE);
$estore_avision->where("student_taken_tests_id", $test_taken_id);
$estore_avision->update("student_taken_topic_tests_temp", array('status'=> 2, 'end_time'=> date("H:i:s")));

return 1;

}

public function get_free_quiz_solution($quiz_id, $test_taken_id) {

$estore_avision = $this->load->database('estore_avision', TRUE);
$estore_avision->select("*");
$estore_avision->from("small_quiz_question");
$estore_avision->where("quiz_id", $quiz_id);
$estore_avision->order_by("question_id", "asc");
$result = $estore_avision->get()->result_array();

$final_array = [];
foreach ($result as $key =>  $value) {

$estore_avision->select('question_status');
$estore_avision->from('student_topic_tests_question_temp');
$estore_avision->where('test_question_id',$value['question_id']);
$estore_avision->where('test_taken_id',$test_taken_id);
$result_qn = $estore_avision->get()->result_array();
if(!empty($result_qn)){

$result[$key]['question_status'] = $result_qn[0]['question_status'];


}else{
$result[$key]['question_status'] = 0;
}

$estore_avision->select("answer,answer_id,answer_status");
$estore_avision->from("small_quiz_answer");
$estore_avision->where("question_id", $value['question_id']);
$res_ans = $estore_avision->get()->result_array();

$estore_avision->select("a.answer,a.answer_id,a.answer_status");
$estore_avision->from("small_quiz_answer a");
$estore_avision->join("student_topic_tests_answers_temp sfta","sfta.asnwer_id = a.answer_id");
$estore_avision->where("sfta.test_question_id", $value['question_id']);
$estore_avision->where("sfta.test_taken_id", $test_taken_id);
$res = $estore_avision->get()->result_array();

$final_array[] = array(
'question_details' => $result[$key],
'answers_list'=> $res_ans,
'student_ans' => $res
);


}

return $final_array;

}

public function get_user_test_taken_id($quiz_id, $student_id) {

$estore_avision = $this->load->database('estore_avision', TRUE);


$estore_avision->select("student_taken_tests_id");
$estore_avision->where("test_id", $quiz_id);
$estore_avision->where("student_id", $student_id);
$res = $estore_avision->get("student_taken_topic_tests_temp")->result_array();
return $res;
}


	

	public function getschollarExamStat($test_taken_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("status");
		$estore_avision->where("student_taken_tests_id",$test_taken_id);
		$res = $estore_avision->get("student_full_tests")->result_array();
		return $res;
	}
	
	public function get_practice_question($subject_id,$chapter_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("question_id,question_statement");
		$estore_avision->where("parent_subject_id",$subject_id);
		$estore_avision->where("chapter_id",$chapter_id);
		$estore_avision->limit(4);
		$res = $estore_avision->get("practic_question")->result_array();
		return $res;
	}
	
	
	public function get_practice_question_answer($question_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("answer_id,answer_text,answer_status");
		$estore_avision->where("question_id",$question_id);
		$res = $estore_avision->get("question_answer")->result_array();
		return $res;
	}
	
	
	
	public function get_parent_course_name() {
        $estore_avision = $this->load->database('estore_avision', TRUE);
        $estore_avision->select("courses_id,courses_name");
        $estore_avision->from("courses");
        $result = $estore_avision->get()->result_array();
        return $result;
    }
    public function get_sub_courses_name($id){
    
        $estore_avision = $this->load->database('estore_avision', TRUE);
        $estore_avision->select("sub_courses_id, sub_courses_name");
        $estore_avision->where("courses_id", $id);
        $estore_avision->from("sub_courses");
        $result = $estore_avision->get()->result_array();
        return $result;
    }
    public function get_quiz_by_sub_category_id($id) {
        $estore_avision = $this->load->database('estore_avision', TRUE);
        $estore_avision->select("sub_category_id");
        $estore_avision->where("sub_courses_id", $id);
        $estore_avision->from("sub_category");
        $result = $estore_avision->get()->result_array();      
               
        
                foreach ($result as $key => $value) {
                $sub_cat[] = $value['sub_category_id'];
                }      
        
        
        $estore_avision->select("*");
        $estore_avision->where_in("sub_category_id", $sub_cat);
        $estore_avision->where("question_year_stat", 2);
        
        $res = $estore_avision->get("quiz_name")->result_array();
        if(!empty($res)) {
        $final_array = $res;
        }
        
        return $final_array;
    }
    
    public function get_previous_year_quiz_given_test($student_id) {

        $estore_avision = $this->load->database('estore_avision', TRUE);
        
        $estore_avision->select("*");
        $estore_avision->from("quiz_name q");
        $estore_avision->join("student_full_tests s", "q.quiz_id = s.test_id");
        $estore_avision->where("question_year_stat", 2);
        $estore_avision->where("s.student_id", $student_id);
        $res = $estore_avision->get()->result_array();
        
        return $res;


    }
    
    public function get_practice_subject($user_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("type_id,type_name,type_img");
		$res = $estore_avision->get("add_question_type")->result_array();
		

		
		foreach($res as $row){

			$estore_avision = $this->load->database('estore_avision', TRUE);
			$estore_avision->distinct("chapter_id");
			$estore_avision->select("chapter_id");
			$estore_avision->where("user_id",$user_id);
			$estore_avision->where("subject_id",$row['type_id']);
			$res_given_chap = $estore_avision->get("practic_test_taken")->num_rows();
		

			$estore_avision = $this->load->database('estore_avision', TRUE);
			$estore_avision->select("chapter_id");
			$estore_avision->where("parent_subject_id",$row['type_id']);
			$res_total_chap = $estore_avision->get("chapter")->num_rows();

		
			$sub_arr[] = array(
			
				'subject_id' => $row['type_id'],
				'subject_name' => $row['type_name'],
				'subject_img' => $row['type_img'],
				'given_chapter_count' => $res_given_chap,
				'total_chapter_count' => $res_total_chap
			);
		}
		
		if(!empty($sub_arr)){

			return $sub_arr;
		}else{
			return false;
		}
		
	}
	public function total_chapter_count($type_id){
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("chapter_id");
		$estore_avision->where("parent_subject_id",$type_id);
		$res = $estore_avision->get("chapter")->result_array();
		return count($res);
	}
	
	public function count_given_answer($subject_id,$chapter_id,$user_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("test_taken_id");
		$estore_avision->where("user_id",$user_id);
		$estore_avision->where("subject_id",$subject_id);
		$estore_avision->where("chapter_id",$chapter_id);
		$res = $estore_avision->get("practic_test_taken")->result_array();
		
		if(!empty($res)){
			
			$test_taken_id = $res[0]['test_taken_id'];
			$estore_avision->select("*");
			$estore_avision->where("test_taken_id",$test_taken_id);
			$res_count = $estore_avision->get("practic_test_taken_review")->num_rows();
			return $res_count;
	
			
		}else{
			return 0;
		}
	}
	
	public function count_total_question($subject_id,$chapter_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("*");
		$estore_avision->where("parent_subject_id",$subject_id);
		$estore_avision->where("chapter_id",$chapter_id);
		$res = $estore_avision->get("practic_question")->num_rows();
		return $res;
	}
	
	public function user_given_chapter_count($user_id,$type_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->distinct("chapter_id");
		$estore_avision->select("chapter_id");
		$estore_avision->where("user_id",$user_id);
		$estore_avision->where("subject_id",$type_id);
		$res = $estore_avision->get("practic_test_taken")->result_array();
		return count($res);
	}
	
	
	
	

	
	public function get_practice_question_count($subject_id,$chapter_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("question_id,question_statement");
		$estore_avision->where("parent_subject_id",$subject_id);
		$estore_avision->where("chapter_id",$chapter_id);
		$estore_avision->limit(36,4);
		$estore_avision->order_by('question_id','asc');
		$res = $estore_avision->get("practic_question")->result_array();
		return $res;
	}
	
	
	
	public function get_practice_question_answer_sol($question_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("solution");
		$estore_avision->where("question_id",$question_id);
		$res = $estore_avision->get("add_question")->result_array();
		return $res;
	}


	public function savePraciceTest($user_id,$subject_id,$chapter_id){

		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("type_name");
		$estore_avision->where("type_id",$subject_id);
		$res = $estore_avision->get("add_question_type")->result_array();
		$subject_name = $res[0]['type_name'];

		$estore_avision->select("chapter_name");
		$estore_avision->where("chapter_id",$chapter_id);
		$res2 = $estore_avision->get("chapter")->result_array();
		$chapter_name = $res2[0]['chapter_name'];


		$data = array(

			'user_id' => $user_id,
			'subject_id' => $subject_id,
			'chapter_id' => $chapter_id,
			'subject_name' => $subject_name,
			'chapter_name'	=> $chapter_name,	
			'created_date' => date('Y-m-d')
		);

		$estore_avision->insert('practic_test_taken',$data);

		if($estore_avision->affected_rows() > 0){
			return $estore_avision->insert_id();
		}else{
			return 0;
		}


	}

	public function savePraciceQuestion($test_taken_id,$question_id,$answer_id,$anser_status){

		$estore_avision = $this->load->database('estore_avision', TRUE);
		$data = array(

			'test_taken_id' => $test_taken_id,
			'question_id' => $question_id,
			'answer_id' => $answer_id,
			'status' => $anser_status,	
			'created_date' => date('Y-m-d')
		);
		
		$estore_avision->select("question_id,status");
		$estore_avision->from("practic_test_taken_review");
		$estore_avision->where("question_id",$question_id);
		$estore_avision->where("test_taken_id",$test_taken_id);
		$result = $estore_avision->get()->result_array();

		if(count($result) == 0){
			
			$estore_avision->insert('practic_test_taken_review',$data);	
		}else{
			$data = array(
			'answer_id' => $answer_id,
			'status' => $anser_status
			);
			if($result[0]['status'] != 1 && $result[0]['status'] !=0){
				$estore_avision->where("question_id",$question_id);
				$estore_avision->where("test_taken_id",$test_taken_id);	
				$estore_avision->update('practic_test_taken_review',$data);	
				
			}else{
				return false;
			}
		}
		

		if($estore_avision->affected_rows() > 0){
			return true;
		}else{
			return false;
		}

	}

	public function check_practice_test($user_id,$subject_id,$chapter_id){

		$estore_avision = $this->load->database('estore_avision', TRUE);

		$estore_avision->select("test_taken_id");
		$estore_avision->where("user_id",$user_id);
		$estore_avision->where("subject_id",$subject_id);
		$estore_avision->where("chapter_id",$chapter_id);
		$res = $estore_avision->get("practic_test_taken")->result_array();
		if(!empty($res)){
				$test_taken_id = $res[0]['test_taken_id'];

				$estore_avision->select("question_id");
				$estore_avision->where("parent_subject_id",$subject_id);
				$estore_avision->where("chapter_id",$chapter_id);
				$res_qn = $estore_avision->get("practic_question")->result_array();
				$question_arr = array();
				foreach ($res_qn as $row) {

					$estore_avision->select("*");
					$estore_avision->where("test_taken_id",$test_taken_id);
					$estore_avision->where("question_id",$row['question_id']);
					$res_question = $estore_avision->get("practic_test_taken_review")->result_array();

					$estore_avision->select("*");
					$estore_avision->where("question_id",$row['question_id']);
					$res_answer = $estore_avision->get("question_answer")->result_array();
					foreach ($res_answer as $row_ans) {
						$ans_arr[] = array(

							'ans_id' => $row_ans['answer_id'],
							'ans_text' => $row_ans['answer_text'],
							'ans_stat'	=> $row_ans['answer_status']
						);
					}
					if(!empty($res_question)){

						$question_arr[] = array(

						'question_id' => $row['question_id'],
						'given_answer_id'	  => $res_question[0]['answer_id'],
						'given_answer_status' => 	$res_question[0]['status'],
						'answer_arr'	=> $ans_arr
						);
					}else{
						$question_arr[] = array(

						'question_id' => $row['question_id'],
						'given_answer_id'	  => 0,
						'given_answer_status' => 	3,
						'answer_arr'	=> $ans_arr	
						);
					}
						$ans_arr = array();			
				}
				
				
				if(!empty($question_arr)){

					
					return $question_arr;
				}else{

					return false;
				}

	}else{
		return false;
	}


	}
	
	
    public function check_practice_test_complete($user_id,$subject_id,$chapter_id){

		$estore_avision = $this->load->database('estore_avision', TRUE);

		$estore_avision->select("test_taken_id");
		$estore_avision->where("user_id",$user_id);
		$estore_avision->where("subject_id",$subject_id);
		$estore_avision->where("chapter_id",$chapter_id);
		$res = $estore_avision->get("practic_test_taken")->result_array();

		if(!empty($res)){

			$test_taken_id = $res[0]['test_taken_id'];
			$estore_avision->select("*");
			$estore_avision->where("test_taken_id",$test_taken_id);
			$res_question = $estore_avision->get("practic_test_taken_review")->result_array();
			if(!empty($res_question)){

				return count($res_question);		
			}else{
				return false;
			}
			
		}else{
			return false;
		}
	}
	
	public function fetch_practice_test_taken_id($user_id,$subject_id,$chapter_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		
		$estore_avision->select("test_taken_id");
		$estore_avision->where("user_id",$user_id);
		$estore_avision->where("subject_id",$subject_id);
		$estore_avision->where("chapter_id",$chapter_id);
		$res = $estore_avision->get("practic_test_taken")->result_array();
		if(!empty($res)){
			
			return $res[0]['test_taken_id'];
		}else{
			return 0;
		}
		
	}

	
		public function order_history($user_id) {

			$estore_avision = $this->load->database('estore_avision', TRUE);

			$estore_avision->select("pb.product_id, pb.user_id, pb.status, pb.created_date, p.product_name, p.product_price, p.product_offer_price");
			$estore_avision->from("product_buy_now pb");
			$estore_avision->join("product p", "p.product_id = pb.product_id");
			$estore_avision->where("user_id",$user_id);
			$res = $estore_avision->get()->result_array();

			$estore_avision->select("pb.product_id, pb.user_id, pb.status, pb.created_date, p.plan_name as product_name, p.price as product_price, p.offer_price as product_offer_price");
			$estore_avision->from("product_buy_now pb");
			$estore_avision->join("estore_plan p", "p.plan_id = pb.product_id");
			$estore_avision->where("user_id",$user_id);
			$res2 = $estore_avision->get()->result_array();

			return (array_merge_recursive($res,$res2));


		}

		public function get_complete_quiz_status($user_id) {
			$estore_avision = $this->load->database('estore_avision', TRUE);
			$estore_avision->select("sf.student_taken_tests_id, sf.test_id, sf.student_id, sf.status, qn.quiz_name");
			$estore_avision->from("student_full_tests sf");
			$estore_avision->join("quiz_name qn", "qn.quiz_id = sf.test_id");
			$estore_avision->where("sf.student_id", $user_id);
			$result  = $estore_avision->get()->result_array();
			return $result;
		}

		public function get_product_brief($prod_id){

			$estore_avision = $this->load->database('estore_avision', TRUE);
			$estore_avision->select("lcm.live_class_title, lcm.live_class, lcm.mock_test, lcm.pdf_notes, lcm.practice_question,p.product_price,p.product_offer_price,lcv.youtube_url");
			$estore_avision->from("live_class_meta lcm");
			$estore_avision->join("live_class_video lcv", "lcv.prod_id = lcm.product_id");
			$estore_avision->join("product p", "p.product_id = lcm.product_id");
			$or_where = "(lcv.vdo_orientation=1 OR lcv.vdo_orientation=0)";
			$estore_avision->where($or_where);
			$estore_avision->where("lcm.product_id", $prod_id);
			//$estore_avision->where("lcv.vdo_orientation",1);
			$estore_avision->limit(1);
			$result  = $estore_avision->get()->result_array();
			
			return $result;
		}

		public function get_recomended_product($prod_id){

			$estore_avision = $this->load->database('estore_avision', TRUE);
			$estore_avision->select("p.product_id,lcm.live_class_title, lcm.live_class, lcm.mock_test, lcm.pdf_notes, lcm.practice_question,p.product_price,p.product_offer_price,lcm.image");
			$estore_avision->from("live_class_meta lcm");
			$estore_avision->join("live_class_video lcv", "lcv.prod_id = lcm.product_id");
			$estore_avision->join("product p", "p.product_id = lcm.product_id");
			$estore_avision->where_not_in("lcm.product_id", $prod_id);
			$estore_avision->where("lcv.vdo_orientation",1);
			$estore_avision->group_by("lcm.live_class_title");
			$result  = $estore_avision->get()->result_array();

			return $result;
		}
		
		public function count_total_practice_question($subject_id,$chapter_id){

			$estore_avision = $this->load->database('estore_avision', TRUE);

			$estore_avision->select('question_id');
			$estore_avision->where('parent_subject_id',$subject_id);
			$estore_avision->where('chapter_id',$chapter_id);
			$result_count = $estore_avision->get('practic_question')->num_rows();

			return $result_count;

		}
		public function get_practice_chapter($subject_id){
		
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$estore_avision->select("ch.chapter_id,ch.chapter_name,pq.question_id,count('*') as question_count");
		$estore_avision->from("chapter ch");
		$estore_avision->join("practic_question pq","(pq.chapter_id = ch.chapter_id AND pq.parent_subject_id = ch.parent_subject_id)","left");
		$estore_avision->where("ch.parent_subject_id",$subject_id);
		$estore_avision->group_by('ch.chapter_id');
		$res = $estore_avision->get()->result_array();
			
		return $res;
	}
	
	public function get_product_slug($prodId){

			$estore_avision = $this->load->database('estore_avision', TRUE);

			$estore_avision->select('product_slug');
			$estore_avision->where('product_id',$prodId);
			$result_slug = $estore_avision->get('product')->result_array();

			return $result_slug;
		}
		
	public function fetchstateParentCategory($id){
			
			$estore_avision = $this->load->database('estore_avision', TRUE);

			$estore_avision->select('parent_cat_id,category_name');
			$estore_avision->where('course_id',$id);
			$estore_avision->order_by('parent_cat_id','asc');
			$result_slug = $estore_avision->get('parent_category')->result_array();
			
			
			foreach($result_slug as $row){
				
				$estore_avision->select('sub_category_id,sub_cat_slug,sub_category_name');
				$estore_avision->where('parent_cat_id',$row['parent_cat_id']);
				$result_sub_cat = $estore_avision->get('sub_category')->result_array();
				
				
				foreach($result_sub_cat as $row_sub_cat){
					
					$content[] = array(
					
						'sub_category_id' => $row_sub_cat['sub_category_id'],
						'sub_cat_slug' => $row_sub_cat['sub_cat_slug'],
						'sub_category_name' => $row_sub_cat['sub_category_name']
					);		
				}
				if(!empty($content)){
					
					$state_ptarent_cat[] = array(
					
					'parent_cat_id' => $row['parent_cat_id'],
					'category_name' => $row['category_name'],
					'content' => $content
					);	
				}
				
				$content = array();
				
				
			}	
				
				return $state_ptarent_cat;
		}
		
		
		public function submit_center_enquiry($name,$email,$phone,$examName,$centerId){
		    
		    $estore_avision = $this->load->database('estore_avision', TRUE);
		    $exam_list = implode(',',$examName);
		    
		    $data = array(
		        
		        'user_name' => $name,
		        'user_email' => $email,
		        'user_phone' => $phone,
		        'exam_name' => $exam_list,
		        'center_id' => $centerId,
		        'created_date' => date('Y-m-d')
		        
		  );
		  
		  $estore_avision->insert('admision_enquiry',$data);
		  
		  if($estore_avision->affected_rows() > 0){
		      $estore_avision->select("center_name");
		      $estore_avision->from('avision_center');
		      $estore_avision->where('center_id',$centerId);
		      $result = $estore_avision->get()->result_array();
		      
			$to = "avisioninstitute@gmail.com";
			$subject = "Enquiry For Avision For Center:-".$result[0]['center_name'];

			$message = "
			<html>
			<head>
			<title>Enquiry</title>
			</head>
			<body>
			<p>Enquiry For Avision!</p>
			<table>
			<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Mobile</th>
			<th>Course</th>
			</tr>
			<tr>
			<td>".$name."</td>
			<td>".$email."</td>
			<td>".$phone."</td>
			<td>".$exam_list."</td>
			</tr>
			</table>
			</body>
			</html>
			";

			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			

			mail($to,$subject,$message,$headers);	
		return 1;
		}else{
		return 0;
		}
		  
		  
		  
		}

	
	
}
