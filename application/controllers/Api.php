<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Api extends CI_Controller {



	public function __construct()

	{

		parent::__construct();

		$this->load->model('api_model');

		$this->load->helper('url');

		$this->load->helper('text');

		$this->load->database();
		
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods:  GET,POST,PUT,DELETE,OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


	}





	public function getTitle($title_keyword){



		header("Access-Control-Allow-Origin: *");

		$estore_avision = $this->load->database('estore_avision', TRUE);

		 $query = trim($title_keyword);

		$like = 'like %'.$title_keyword.'%';

		$estore_avision->select('*');

		$estore_avision->from('page');

		$estore_avision->like('title_keyword',trim($title_keyword),'both');

		$query = $estore_avision->get()->result_array();

		$result = $estore_avision->last_query();



		return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($query));

	}



	public function getCourseContent($sub_cat_id){

		header("Access-Control-Allow-Origin: *");

		$estore_avision = $this->load->database('estore_avision', TRUE);

		$estore_avision->select('page_heading,page_content');

    	$estore_avision->from('course_content');

        $estore_avision->where('sub_cat_id',$sub_cat_id);

        $query = $estore_avision->get()->result_array(); 

		echo json_encode($query);

		

        /*foreach($query as $data){



            $posts[] = array(

                'page_heading' => $data->page_heading,

                'page_content' => $data->page_content

            );

        }

		if(!empty($posts)){

			return $this->output

				->set_content_type('application/json')

				->set_output($posts);

		}else{

			$msg ="No Data Avaulable";

			return $this->output

				->set_content_type('application/json')

				->set_output($posts);

		}*/

       

	}



	public function getCourseName($sub_cat_id){

		header("Access-Control-Allow-Origin: *");



        $estore_avision = $this->load->database('estore_avision', TRUE);



		$estore_avision->select('sub_category_name');

        $estore_avision->from('sub_category');

        $estore_avision->where('sub_category_id ',$sub_cat_id);

        $estore_avision->limit(1, 0);

       

		$query = $estore_avision->get()->result_array();



        return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($query));



	}

	

	public function video_courseById($sub_cat_id){

		header("Access-Control-Allow-Origin: *");	

		$result = $this->api_model->video_courseById($sub_cat_id);

		$video_course= array();

		foreach($result as $row){

			

			$video_course[] = array(

				"product_id" => $row['product_id'],

				"product_name" => $row['product_name'],

				"product_img" => 'https://estore.avision24x7.com/'.$row['product_img']

				

			);

		}

		echo json_encode($video_course);

		

	}

	

	public function testSeriesById($sub_cat_id){

		header("Access-Control-Allow-Origin: *");	

		$result = $this->api_model->testSeriesById($sub_cat_id);

		/*if(!empty($result))

           {    

                $data['message']="Test Series Fetch Sucessfully";

                $data['status_code']= '200';

				$data['TestSeries'] = $result;

           }



           else {

                $data['message']="get student Details Not Found";

                $data['status_code']= '203'; 

           }



            echo json_encode($data);*/

		$testSeries = array();	

		foreach($result as $row){

			

			$testSeries[] = array(

			

				"product_id" => $row['product_id'],

				"product_name" => $row['product_name'],

				"sub_category_image" => 'https://estore.avision24x7.com/'.$row['sub_category_image']

			);

		}	

		echo json_encode($testSeries);

	}



	public function subCategoryAllData()

	{

	  header("Access-Control-Allow-Origin: *");	

		$estore_avision = $this->load->database('estore_avision', TRUE);

	  $course=$estore_avision->query("select * from courses")->result_array();



	  $arrayDetails = array();



	  foreach($course as $courseDetail)

	  {

	  	$arrayDetails[]=array(

       	"course_name"=>$courseDetail['courses_name'],

       	"subCatDetails"=>$estore_avision->query("SELECT * FROM `courses` INNER JOIN sub_courses on courses.courses_id=sub_courses.courses_id INNER JOIN sub_category on sub_category.sub_courses_id=sub_courses.sub_courses_id where courses.courses_id =".$courseDetail['courses_id'])->result_array()

       );

	  }



	  return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($arrayDetails));

	} 



	public function TestSeriescourseDetails()

	{

		header("Access-Control-Allow-Origin: *");



		$table="courses";

		$coursesData = $this->api_model->get_datas($table);



		$details = array();

		if(!empty($coursesData)){

			foreach($coursesData as $coursesDetails){



				$details[] = array(

					'courses_id' => $coursesDetails->courses_id,

					'courses_name' => $coursesDetails->courses_name,

					'courses_desc' => $coursesDetails->courses_desc,

					'courses_icon' => $coursesDetails->courses_icon,

					'course_changed_icon' => $coursesDetails->course_changed_icon

				);

			}

		}



		return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($details));

	}

	public function passData()

	{

		header("Access-Control-Allow-Origin: *");



		$table="estore_plan";

		$passData=$this->api_model->get_datas($table);



		$details = array();

		if(!empty($passData)){

			foreach($passData as $passDetails){



				$details[] = array(
					'plan_id'	=> $passDetails->plan_id,
					'plan_name' => strip_tags($passDetails->plan_name),
					'offer_price' => strip_tags($passDetails->offer_price),
					'price' => strip_tags($passDetails->price)

				);

					

			}

		}



		return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($details));

	}



	public function testSeriesSingleData($courseId)

	{

		header("Access-Control-Allow-Origin: *");



		$testSeriesData = $this->api_model->getTestSeriesByCategories($courseId);

		/*echo "<pre>";

		print_r($testSeriesData);

		die();*/

		return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($testSeriesData));



	}





	public function examPreprationDetails()

	{

		header("Access-Control-Allow-Origin: *");

		$avision_group = $this->load->database('avision_group',TRUE);

		$examPreprationData=$avision_group->query("select * from wp_postmeta where post_id='141' and (meta_key='home_page_title' or meta_key='home_page_content')")->result();



		$details = array();

		if(!empty($examPreprationData)){

			foreach($examPreprationData as $examPreprationDetails){



				$details[] = array(

					'meta_id' => strip_tags($examPreprationDetails->meta_id),

					'post_id' => strip_tags($examPreprationDetails->post_id),

					'meta_key' => strip_tags($examPreprationDetails->meta_key),

					'meta_value' => strip_tags($examPreprationDetails->meta_value)

				);

					

			}

		}



		

		return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($details));



	}



	public function featuredDetails()

	{



		header("Access-Control-Allow-Origin: *");

		$avision_group = $this->load->database('avision_group',TRUE);

		$featuredData=$avision_group->query("select * from wp_postmeta where post_id='141' and (meta_key='home_page_block_1' or meta_key='home_page_block_2' or meta_key='home_page_block_3' or meta_key='home_page_block_4')")->result();

		

        $details = array();

		if(!empty($featuredData)){

			foreach($featuredData as $featuredDetails){



				$details[] = array(

					'meta_id' => strip_tags($featuredDetails->meta_id),

					'post_id' => strip_tags($featuredDetails->post_id),

					'meta_key' => strip_tags($featuredDetails->meta_key),

					'meta_value' => strip_tags($featuredDetails->meta_value)

				);

					

			}

		}



		return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($details));



	}



	public function testSeriesData()

	{

		header("Access-Control-Allow-Origin: *");



		$testSeriesData = $this->api_model->get_TestSeriesdatas();

		return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($testSeriesData));

	}



	public function bannerDetails()

	{

		header("Access-Control-Allow-Origin: *");



		$table="angular_banner";

		$bannerData = $this->api_model->get_banner($table);



		$details = array();

		if(!empty($bannerData)){

			foreach($bannerData as $bannersDetails){



				$details[] = array(

					'banner_id' => $bannersDetails->banner_id,

					'main_heading' => $bannersDetails->main_heading,

					'sub_heading' => $bannersDetails->sub_heading,

					'banner_sub_sub_heading' => $bannersDetails->banner_sub_sub_heading,

					'button_text' => $bannersDetails->button_text,

					'banner_image' => $bannersDetails->banner_image,
					
					'banner_new_image' => $bannersDetails->banner_new_image,
					
					'image_link'	=> $bannersDetails->image_link,

					'banner_background_image' => $bannersDetails->banner_background_image

				);

			}

		}



		return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($details));

	}



	public function testimonialDetails()

	{

		header("Access-Control-Allow-Origin: *");



		$table="testimonial";

		$testimonialData = $this->api_model->get_datas($table);



		$details = array();

		if(!empty($testimonialData)){

			foreach($testimonialData as $testimonialDetails){



				$details[] = array(

					'id' => $testimonialDetails->id,

					'content' => strip_tags($testimonialDetails->content),

					'image' => $testimonialDetails->image,

					'rating' => $testimonialDetails->rating,

					'user_name' => $testimonialDetails->user_name

					

				);

			}

		}



		return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($details));

	}



	public function courseDetails()

	{

		header("Access-Control-Allow-Origin: *");



		$table="courses";

		$coursesData = $this->api_model->get_datas($table);



		$details = array();

		if(!empty($coursesData)){

			foreach($coursesData as $coursesDetails){



				$details[] = array(

					'courses_id' => $coursesDetails->courses_id,

					'courses_name' => $coursesDetails->courses_name,

					'courses_desc' => $coursesDetails->courses_desc,

					'courses_icon' => $coursesDetails->courses_icon,

					'course_changed_icon' => $coursesDetails->course_changed_icon

				);

			}

		}



       



		return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($details));

			

	}



	public function passValue(){



		$coursesData = $this->api_model->get_passVal();

	}



  

	public function subCategoryName($courseId)

	{

		/* Please Be CareFull Changing on this function is working in home page as well as pass page */

		header("Access-Control-Allow-Origin: *");



		$coursesData = $this->api_model->get_data($courseId);



		if(!empty($coursesData)){

			foreach($coursesData as $coursesDetails){



				$details[] = array(

					'courses_id' => $coursesDetails->courses_id,

					'courses_name' => $coursesDetails->courses_name,

					'courses_desc' => $coursesDetails->courses_desc,

					'courses_icon' => $coursesDetails->courses_icon,

					'course_changed_icon' => $coursesDetails->course_changed_icon,

					'created_date' => $coursesDetails->created_date,

					'modified_date' => $coursesDetails->modified_date,

					'sub_courses_id' => $coursesDetails->sub_courses_id,
					
					'sub_cat_slug'	=> $coursesDetails->sub_cat_slug,

					'sub_courses_name' => $coursesDetails->sub_courses_name,

					'sub_courses_desc' => $coursesDetails->sub_courses_desc,

					'sub_category_id' => $coursesDetails->sub_category_id,

					'sub_category_name' => $coursesDetails->sub_category_name,

					'sub_category_desc' => $coursesDetails->sub_category_desc,

					'correct_marks' => $coursesDetails->correct_marks,

					'negetive_marks' => $coursesDetails->negetive_marks,

					'sub_category_image' => $coursesDetails->sub_category_image,

					'trending_order' => $coursesDetails->trending_order,

				);



			}

		}



		return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($details));



	}



	public function resultTab(){



		header("Access-Control-Allow-Origin: *");

		$avision_group = $this->load->database('avision_group',TRUE);



		$result_data = $avision_group->query("SELECT `term`.`term_id`, `term`.`name` FROM `wp_term_taxonomy` as `wpt` INNER JOIN `wp_terms` AS `term` ON `term`.`term_id` = `wpt`.`term_id` WHERE `wpt`.`taxonomy` = 'all-result'")->result_array();



		return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($result_data));



	}



	public function getStudentImage($term_id)

	{



		header("Access-Control-Allow-Origin: *");

		//$details=[];

		$avision_group = $this->load->database('avision_group',TRUE);



		$result_data = $avision_group->query("SELECT `wp`.`ID`, `wp`.`post_title` FROM `wp_posts` AS `wp` INNER JOIN `wp_term_relationships` AS `wpterm` ON `wp`.`ID` = `wpterm`.`object_id` WHERE `wp`.`post_type` = 'resultdp' AND `wpterm`.`term_taxonomy_id` = '$term_id'  order by `wp`.`ID` DESC")->result_array();

		if(!empty($result_data)){

		foreach($result_data as $data)

		{



		  $details=$avision_group->query("SELECT `ID`,`post_title`,`guid` FROM `wp_posts` WHERE `post_parent` = ".$data['ID']." AND `post_type` = 'attachment' order by `ID`  limit 0,1")->result_array();



		  $designation=$avision_group->query("SELECT `post_id`,`meta_key`,`meta_value` FROM `wp_postmeta` WHERE `post_id` = ".$data['ID']." AND `meta_key` = 'course' ")->row_array();

		  if(!empty($details)){

		  foreach($details as $result){



		  	 $result_arr[] = array(



		  		'ID' => $result['ID'],

		  		'post_title' => $data['post_title'],

		  		'guid' => $result['guid'],

		  		'meta_value' => $designation['meta_value']

		  	);

		  }

		 }



		}

	}

		/*echo "<pre>";

		print_r($result_arr);

		die();*/

       

		return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($result_arr));



	}



	public function getSingleStudentImage($term_id)

	{



		header("Access-Control-Allow-Origin: *");

		//$details=[];

		$avision_group = $this->load->database('avision_group',TRUE);



		$result_data = $avision_group->query("SELECT `wp`.`ID`, `wp`.`post_title` FROM `wp_posts` AS `wp` INNER JOIN `wp_term_relationships` AS `wpterm` ON `wp`.`ID` = `wpterm`.`object_id` WHERE `wp`.`post_type` = 'resultdp' AND `wpterm`.`term_taxonomy_id` = '$term_id' order by `wp`.`ID` DESC")->result_array();

		

		foreach($result_data as $data)

		{

		  $details=$avision_group->query("SELECT `ID`,`post_title`,`guid` FROM `wp_posts` WHERE `post_parent` = ".$data['ID']." AND `post_type` = 'attachment' order by `ID` desc limit 0,1")->row_array();



		  $designation=$avision_group->query("SELECT `post_id`,`meta_key`,`meta_value` FROM `wp_postmeta` WHERE `post_id` = ".$data['ID']." AND `meta_key` = 'course' ")->row_array();

		  

		 $result_arr[] = array(



		  		'ID' => $details['ID'],

		  		'post_title' => $data['post_title'],

		  		'guid' => $details['guid'],

		  		'meta_value' => $designation['meta_value']

		  	);

		  

		}

		

		return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($result_arr));

	}



	public function featured_blogs()

	{

		header("Access-Control-Allow-Origin: *");



		$blogs = $this->api_model->get_blogs($featured=true, $recentpost=false);



		$posts = array();

		if(!empty($blogs)){

			foreach($blogs as $blog){

				

				$short_desc = strip_tags(character_limiter($blog->description, 70));

				$author = $blog->first_name.' '.$blog->last_name;



				$posts[] = array(

					'id' => $blog->id,

					'title' => $blog->title,

					'short_desc' => html_entity_decode($short_desc),

					'author' => $author,

					'image' => base_url('media/images/'.$blog->image),

					'created_at' => $blog->created_at

				);

			}

		}



		return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($posts));

	}



	public function blog($id)

	{

		header("Access-Control-Allow-Origin: *");

		

		$blog = $this->api_model->get_blog($id);



		$author = $blog->first_name.' '.$blog->last_name;



		$post = array(

			'id' => $blog->id,

			'title' => $blog->title,

			'description' => $blog->description,

			'author' => $author,

			'image' => base_url('media/images/'.$blog->image),

			'created_at' => $blog->created_at

		);

		

		$this->output

			->set_content_type('application/json')

			->set_output(json_encode($post));

	}



	public function recent_blogs()

	{

		header("Access-Control-Allow-Origin: *");



		$blogs = $this->api_model->get_blogs($featured=false, $recentpost=5);



		$posts = array();

		if(!empty($blogs)){

			foreach($blogs as $blog){

				

				$short_desc = strip_tags(character_limiter($blog->description, 70));

				$author = $blog->first_name.' '.$blog->last_name;



				$posts[] = array(

					'id' => $blog->id,

					'title' => $blog->title,

					'short_desc' => html_entity_decode($short_desc),

					'author' => $author,

					'image' => base_url('media/images/'.$blog->image),

					'created_at' => $blog->created_at

				);

			}

		}



		$this->output

			->set_content_type('application/json')

			->set_output(json_encode($posts));

	}



	public function categories()

	{

		header("Access-Control-Allow-Origin: *");



		$categories = $this->api_model->get_categories();



		$category = array();

		if(!empty($categories)){

			foreach($categories as $cate){

				$category[] = array(

					'id' => $cate->id,

					'name' => $cate->category_name

				);

			}

		}



		$this->output

			->set_content_type('application/json')

			->set_output(json_encode($category));

	}



	public function page($slug)

	{

		header("Access-Control-Allow-Origin: *");

		

		$page = $this->api_model->get_page($slug);



		$pagedata = array(

			'id' => $page->id,

			'title' => $page->title,

			'description' => $page->description

		);

		

		$this->output

			->set_content_type('application/json')

			->set_output(json_encode($pagedata));

	}



	public function contact()

	{

		header("Access-Control-Allow-Origin: *");

		header("Access-Control-Request-Headers: GET,POST,OPTIONS,DELETE,PUT");

		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');



		$formdata = json_decode(file_get_contents('php://input'), true);



		if( ! empty($formdata)) {



			$name = $formdata['name'];

			$email = $formdata['email'];

			$phone = $formdata['phone'];

			$message = $formdata['message'];



			$contactData = array(

				'name' => $name,

				'email' => $email,

				'phone' => $phone,

				'message' => $message,

				'created_at' => date('Y-m-d H:i:s', time())

			);

			

			$id = $this->api_model->insert_contact($contactData);



			$this->sendemail($contactData);

			

			$response = array('id' => $id);

		}

		else {

			$response = array('id' => '');

		}

		

		$this->output

			->set_content_type('application/json')

			->set_output(json_encode($response));

	}



	public function sendemail($contactData)

	{

		$message = '<p>Hi, <br />Some one has submitted contact form.</p>';

		$message .= '<p><strong>Name: </strong>'.$contactData['name'].'</p>';

		$message .= '<p><strong>Email: </strong>'.$contactData['email'].'</p>';

		$message .= '<p><strong>Phone: </strong>'.$contactData['phone'].'</p>';

		$message .= '<p><strong>Name: </strong>'.$contactData['message'].'</p>';

		$message .= '<br />Thanks';



		$this->load->library('email');



		$config['protocol'] = 'sendmail';

		$config['mailpath'] = '/usr/sbin/sendmail';

		$config['charset'] = 'iso-8859-1';

		$config['wordwrap'] = TRUE;

		$config['mailtype'] = 'html';



		$this->email->initialize($config);



		$this->email->from('demo@rsgitech.com', 'RSGiTECH');

		$this->email->to('demo2@rsgitech.com');

		$this->email->cc('another@rsgitech.com');

		$this->email->bcc('them@rsgitech.com');



		$this->email->subject('Contact Form');

		$this->email->message($message);



		$this->email->send();

	}



	public function login() 

	{

		header("Access-Control-Allow-Origin: *");

		header("Access-Control-Request-Headers: GET,POST,OPTIONS,DELETE,PUT");

		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');



		$formdata = json_decode(file_get_contents('php://input'), true);



		$username = $formdata['username'];

		$password = $formdata['password'];



		$user = $this->api_model->login($username, $password);



		if($user) {

			$response = array(

				'user_id' => $user->id,

				'first_name' => $user->first_name,

				'last_name' => $user->last_name,

				'token' => $user->token

			);

		}

		else {

			$response = array();

		}



		$this->output

				->set_content_type('application/json')

				->set_output(json_encode($response));

	}



	public function adminBlogs()

	{

		header("Access-Control-Allow-Origin: *");

		header("Access-Control-Allow-Headers: authorization, Content-Type");



		$token = $this->input->get_request_header('Authorization');



		$isValidToken = $this->api_model->checkToken($token);



		$posts = array();

		if($isValidToken) {

			$blogs = $this->api_model->get_admin_blogs();

			foreach($blogs as $blog) {

				$posts[] = array(

					'id' => $blog->id,

					'title' => $blog->title,

					'image' => base_url('media/images/'.$blog->image),

					'created_at' => $blog->created_at

				);

			}



			$this->output

				->set_status_header(200)

				->set_content_type('application/json')

				->set_output(json_encode($posts)); 

		}

	}



	public function adminBlog($id)

	{

		header("Access-Control-Allow-Origin: *");

		header("Access-Control-Allow-Headers: authorization, Content-Type");



		$token = $this->input->get_request_header('Authorization');



		$isValidToken = $this->api_model->checkToken($token);



		if($isValidToken) {



			$blog = $this->api_model->get_admin_blog($id);



			$post = array(

				'id' => $blog->id,

				'title' => $blog->title,

				'description' => $blog->description,

				'image' => base_url('media/images/'.$blog->image),

				'is_featured' => $blog->is_featured,

				'is_active' => $blog->is_active

			);

			



			$this->output

				->set_status_header(200)

				->set_content_type('application/json')

				->set_output(json_encode($post)); 

		}

	}



	public function createBlog()

	{

		header("Access-Control-Allow-Origin: *");

		header("Access-Control-Request-Headers: GET,POST,OPTIONS,DELETE,PUT");

		header("Access-Control-Allow-Headers: authorization, Content-Type");



		$token = $this->input->get_request_header('Authorization');



		$isValidToken = $this->api_model->checkToken($token);



		if($isValidToken) {



			$title = $this->input->post('title');

			$description = $this->input->post('description');

			$is_featured = $this->input->post('is_featured');

			$is_active = $this->input->post('is_active');



			$filename = NULL;



			$isUploadError = FALSE;



			if ($_FILES && $_FILES['image']['name']) {



				$config['upload_path']          = './media/images/';

	            $config['allowed_types']        = 'gif|jpg|png|jpeg';

	            $config['max_size']             = 500;



	            $this->load->library('upload', $config);

	            if ( ! $this->upload->do_upload('image')) {



	            	$isUploadError = TRUE;



					$response = array(

						'status' => 'error',

						'message' => $this->upload->display_errors()

					);

	            }

	            else {

	            	$uploadData = $this->upload->data();

            		$filename = $uploadData['file_name'];

	            }

			}



			if( ! $isUploadError) {

	        	$blogData = array(

					'title' => $title,

					'user_id' => 1,

					'description' => $description,

					'image' => $filename,

					'is_featured' => $is_featured,

					'is_active' => $is_active,

					'created_at' => date('Y-m-d H:i:s', time())

				);



				$id = $this->api_model->insertBlog($blogData);



				$response = array(

					'status' => 'success'

				);

			}



			$this->output

				->set_status_header(200)

				->set_content_type('application/json')

				->set_output(json_encode($response)); 

		}

	}



	public function updateBlog($id)

	{

		header("Access-Control-Allow-Origin: *");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");



		$token = $this->input->get_request_header('Authorization');



		$isValidToken = $this->api_model->checkToken($token);



		if($isValidToken) {



			$blog = $this->api_model->get_admin_blog($id);

			$filename = $blog->image;



			$title = $this->input->post('title');

			$description = $this->input->post('description');

			$is_featured = $this->input->post('is_featured');

			$is_active = $this->input->post('is_active');



			$isUploadError = FALSE;



			if ($_FILES && $_FILES['image']['name']) {



				$config['upload_path']          = './media/images/';

	            $config['allowed_types']        = 'gif|jpg|png|jpeg';

	            $config['max_size']             = 500;



	            $this->load->library('upload', $config);

	            if ( ! $this->upload->do_upload('image')) {



	            	$isUploadError = TRUE;



					$response = array(

						'status' => 'error',

						'message' => $this->upload->display_errors()

					);

	            }

	            else {

	   

					if($blog->image && file_exists(FCPATH.'media/images/'.$blog->image))

					{

						unlink(FCPATH.'media/images/'.$blog->image);

					}



	            	$uploadData = $this->upload->data();

            		$filename = $uploadData['file_name'];

	            }

			}



			if( ! $isUploadError) {

	        	$blogData = array(

					'title' => $title,

					'user_id' => 1,

					'description' => $description,

					'image' => $filename,

					'is_featured' => $is_featured,

					'is_active' => $is_active

				);



				$this->api_model->updateBlog($id, $blogData);



				$response = array(

					'status' => 'success'

				);

           	}



			$this->output

				->set_status_header(200)

				->set_content_type('application/json')

				->set_output(json_encode($response)); 

		}

	}



	public function deleteBlog($id)

	{

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");



		$token = $this->input->get_request_header('Authorization');



		$isValidToken = $this->api_model->checkToken($token);



		if($isValidToken) {



			$blog = $this->api_model->get_admin_blog($id);



			if($blog->image && file_exists(FCPATH.'media/images/'.$blog->image))

			{

				unlink(FCPATH.'media/images/'.$blog->image);

			}



			$this->api_model->deleteBlog($id);



			$response = array(

				'status' => 'success'

			);



			$this->output

				->set_status_header(200)

				->set_content_type('application/json')

				->set_output(json_encode($response)); 

		}

	}

	

	public function data_bank_po(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_menu_data(1);

		echo json_encode($result);

		

	}

	

	public function data_bank_so(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_menu_data(2);

		echo json_encode($result);

		

	}

	

	public function data_bank_clerk(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_menu_data(3);

		echo json_encode($result);

	}

	

	public function data_bank_rrb(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_menu_data(4);

		echo json_encode($result);

	}

	public function data_ssc(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_menu_data(5);

		echo json_encode($result);

	}

	public function data_railways(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_menu_data(6);

		echo json_encode($result);

	}

	public function data_insurance(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_menu_data(7);

		echo json_encode($result);

	}

	public function data_tet(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_menu_data(8);

		echo json_encode($result);

	}

	public function data_bank_defence(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_menu_data(9);

		echo json_encode($result);

	}

	public function data_wb(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_menu_data(10);

		echo json_encode($result);

	}

	public function data_fci(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_menu_data(11);

		echo json_encode($result);

	}

	

	public function data_footer_menu(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->data_footer_menu();

		echo json_encode($result);

	}

	

	public function get_faculties(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$faculties[0] = array(

			

			'Id'	=> 0,	

			'Name' => 'Jeetu Tiwari',

			'qualification' => 'B.Sc',

			'post'	=> 'General Study Faculty',

			'Experience' => '4+'

		);

		$faculties[1] = array(

			'Id'	=> 1,

			'Name' => 'Sanjiv Kumar Dubey',

			'qualification' => 'MSc  zoology.',

			'post'	=> 'General Study Faculty',

			'Experience' => '3+'

		);

		$faculties[2] = array(

			'Id'	=> 2,

			'Name' => 'Urvashi singhi ',

			'qualification' => 'Mcom (BADM) , B.ed',

			'post'	=> 'General Awareness Faculty',

			'Experience' => '3+'

		);

		$faculties[3] = array(

			'Id'	=> 3,

			'Name' => 'Amal Kumar',

			'qualification' => 'Msc in Mathematics',

			'post'	=> 'Quantitative Aptitude Faculty',

			'Experience' => '7+'

		);

		$faculties[4] = array(

			'Id'	=> 4,

			'Name' => 'Arindam Dutta',

			'qualification' => 'Msc Computer Science',

			'post'	=> 'Reasoning Faculty',

			'Experience' => '5'

		);

		$faculties[5] = array(

			'Id'	=> 5,

			'Name' => 'Arka Roy',

			'qualification' => 'B.Tech',

			'post'	=> 'Quantitative Aptitude Faculty',

			'Experience' => '3+'

		);

		$faculties[6] = array(

			'Id'	=> 6,

			'Name' => 'Surayakant Ram',

			'qualification' => 'M.A in Political Science,B.Ed',

			'post'	=> 'General Study Faculty',

			'Experience' => '7+'

		);

		$faculties[7] = array(

			'Id'	=> 7,

			'Name' => 'Subhodeep Das',

			'qualification' => 'M.Sc In Economics',

			'post'	=> 'Quantitative Aptitude Faculty',

			'Experience' => '6+'

		);	

		$faculties[8] = array(

			'Id'	=> 8,

			'Name' => 'Pallobi Das',

			'qualification' => 'B.A LLB',

			'post'	=> 'English Faculty',

			'Experience' => '5+'

		);  

		$faculties[9] = array(

			'Id'	=> 9,

			'Name' => 'Biswajit Dutta',

			'qualification' => 'M.Sc',

			'post'	=> 'Reasoning Faculty',

			'Experience' => '13+'

		); 

		$faculties[10] = array(

			'Id'	=> 10,

			'Name' => 'Saroj Kumar Jha',

			'qualification' => 'M.A in English',

			'post'	=> 'English Faculty',

			'Experience' => '12+'

		); 

		$faculties[11] = array(

			'Id'	=> 11,

			'Name' => 'Swati Singh',

			'qualification' => 'M.B.A',

			'post'	=> 'Genaral Awareness Faculty',

			'Experience' => '3+'

		);

		echo json_encode($faculties);

	}

	

	public function get_full_test($product_id){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->get_full_test($product_id);

		

		foreach($result as $row){

			

			$arr[] = array(

			

				'quiz_id'	=> $row['quiz_id'],

				'quiz_name'	=> $row['quiz_name'],

				'no_of_qs'	=> $row['no_of_qs'],

				'correct_mark'	=> $row['correct_mark'],

				'duration'	=> $row['duration']

			);

		}

		

		echo json_encode($arr);

		

	}

	

	public function get_sectional_test($product_id){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->get_sectional_test($product_id);

		$arr = array();

		if(!empty($result)){

			foreach($result as $row){

				

				$arr[] = array(

				

					'quiz_id'	=> $row['quiz_id'],

					'quiz_name'	=> $row['quiz_name'],

					'no_of_qs'	=> $row['no_of_qs'],

					'correct_mark'	=> $row['correct_mark'],

					'duration'	=> $row['duration']

				);

			}

		}

		

		echo json_encode($arr);

		

	}

	

	public function get_prev_yr_test($product_id){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->get_prev_yr_test($product_id);

		

		$arr = array();

		if(!empty($result)){

			foreach($result as $row){

				

				$arr[] = array(

				

					'quiz_id'	=> $row['quiz_id'],

					'quiz_name'	=> $row['quiz_name'],

					'no_of_qs'	=> $row['no_of_qs'],

					'correct_mark'	=> $row['correct_mark'],

					'duration'	=> $row['duration']

				);

			}

		}

		

		echo json_encode($arr);

		

	}

	

	public function get_product_details($product_id){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->get_product_details($product_id);

		

		

		

			$arr = array(

			

				'product_id'	=> $result[0]['product_id'],

				'product_name'	=> $result[0]['product_name']

			);

		

		

		echo json_encode($arr);

	}

	

	public function get_test_count($product_id){

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->get_test_count($product_id);

		

		

		

			$arr = array(

			

				'total_count'	=> $result

			);

		

		

		echo json_encode($arr);

	}

	

	public function fetchbanking_VideoCourse(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_VideoCourse(1);

		if(!empty($result)){

			foreach($result as $row){

				if(!empty($row['product_tags'])){
				$tags_arr = array();
				$tags = explode(',',$row['product_tags']);
				foreach($tags as $tag_val){
					array_push($tags_arr,$tag_val);
				}
			}else{
				$tags_arr =[];
			}

				$vdo_arr[] = array(

					'courses_name' => $row['courses_name'],

					'product_id' => $row['product_id'],
					
					'product_slug' => $row['product_slug'],

					'product_name' => $row['product_name'],

					'product_desc' => $row['product_desc'],

					'product_img' => 'https://estore.avision24x7.com/'.$row['product_img'],
					
					'product_tags'	=> $tags_arr,

					'product_price' => $row['product_price'],

					'product_offer_price' => $row['product_offer_price']

				);

			}

			

			$data = array(

		

			'vdo_data' => $vdo_arr,

			'status' => 200,

			'message' => 'data found'

		);

		}else{

			$data = array(

		

			'vdo_data' => [],

			'status' => 203,

			'message' => 'data not found'

		);

			

		}

		

		echo json_encode($data);

	}

	

	public function fetchssc_VideoCourse(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_VideoCourse(2);

		if(!empty($result)){

			foreach($result as $row){

				

				$vdo_arr[] = array(

					'courses_name' => $row['courses_name'],

					'product_id' => $row['product_id'],
					
					'product_slug' => $row['product_slug'],

					'product_name' => $row['product_name'],

					'product_desc' => $row['product_desc'],

					'product_img' => 'https://estore.avision24x7.com/'.$row['product_img'],

					'product_price' => $row['product_price'],

					'product_offer_price' => $row['product_offer_price']

				);

			}

			

			$data = array(

			

				'vdo_data' => $vdo_arr,

				'status' => 200,

				'message' => 'data found'

			);

		}else{

			$vdo_arr = array();

				$data = array(

			

				'vdo_data' => $vdo_arr,

				'status' => 203,

				'message' => 'data not found'

			);

			

		}

		

		echo json_encode($data);

	}

	

	public function fetchinsurance_VideoCourse(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_VideoCourse(4);

		if(!empty($result)){

		foreach($result as $row){

			

			$vdo_arr[] = array(

				'courses_name' => $row['courses_name'],

				'product_id' => $row['product_id'],
				
				'product_slug' => $row['product_slug'],

				'product_name' => $row['product_name'],

				'product_desc' => $row['product_desc'],

				'product_img' => 'https://estore.avision24x7.com/'.$row['product_img'],

				'product_price' => $row['product_price'],

				'product_offer_price' => $row['product_offer_price']

			);

		}

		

		$data = array(

		

			'vdo_data' => $vdo_arr,

			'status' => 200,

			'message' => 'data found'

		);

		}else{

			

			$vdo_arr = array();

			$data = array(

		

			'vdo_data' => $vdo_arr,

			'status' => 203,

			'message' => 'data not found'

		);

		}

		

		echo json_encode($data);

	}

	

	public function fetchrailway_VideoCourse(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_VideoCourse(3);

		if(!empty($result)){

		foreach($result as $row){

			

			$vdo_arr[] = array(

				'courses_name' => $row['courses_name'],

				'product_id' => $row['product_id'],
				
				'product_slug' => $row['product_slug'],
				
				'product_name' => $row['product_name'],

				'product_desc' => $row['product_desc'],

				'product_img' => 'https://estore.avision24x7.com/'.$row['product_img'],

				'product_price' => $row['product_price'],

				'product_offer_price' => $row['product_offer_price']

			);

		}

		

		$data = array(

		

			'vdo_data' => $vdo_arr,

			'status' => 200,

			'message' => 'data found'

		);

		}else{

			

			$vdo_arr = array();

			$data = array(

		

			'vdo_data' => $vdo_arr,

			'status' => 203,

			'message' => 'data not found'

		);

		}

		

		echo json_encode($data);

	}

	

	public function fetchteaching_VideoCourse(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_VideoCourse(7);

		if(!empty($result)){

		foreach($result as $row){

			

			$vdo_arr[] = array(

				'courses_name' => $row['courses_name'],

				'product_id' => $row['product_id'],				
				
				'product_slug' => $row['product_slug'],

				'product_name' => $row['product_name'],

				'product_desc' => $row['product_desc'],

				'product_img' => 'https://estore.avision24x7.com/'.$row['product_img'],

				'product_price' => $row['product_price'],

				'product_offer_price' => $row['product_offer_price']

			);

		}

		

		$data = array(

		

			'vdo_data' => $vdo_arr,

			'status' => 200,

			'message' => 'data found'

		);

		}else{

			

			$vdo_arr = array();

			$data = array(

		

			'vdo_data' => $vdo_arr,

			'status' => 203,

			'message' => 'data not found'

		);

		}

		

		echo json_encode($data);

	}

	

	public function fetchdefence_VideoCourse(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_VideoCourse(8);

		if(!empty($result)){

		foreach($result as $row){

			

			$vdo_arr[] = array(

				'courses_name' => $row['courses_name'],

				'product_id' => $row['product_id'],
				
				'product_slug' => $row['product_slug'],

				'product_name' => $row['product_name'],

				'product_desc' => $row['product_desc'],

				'product_img' => 'https://estore.avision24x7.com/'.$row['product_img'],

				'product_price' => $row['product_price'],

				'product_offer_price' => $row['product_offer_price']

			);

		}

		

		$data = array(

		

			'vdo_data' => $vdo_arr,

			'status' => 200,

			'message' => 'data found'

		);

		}else{

			

			$vdo_arr = array();

			$data = array(

		

			'vdo_data' => $vdo_arr,

			'status' => 203,

			'message' => 'data not found'

		);

		}

		

		echo json_encode($data);

	}

	

	public function fetchstateexam_VideoCourse(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetch_VideoCourse(5);

		if(!empty($result)){

		foreach($result as $row){

			

			$vdo_arr[] = array(

				'courses_name' => $row['courses_name'],

				'product_id' => $row['product_id'],
				
				'product_slug' => $row['product_slug'],

				'product_name' => $row['product_name'],

				'product_desc' => $row['product_desc'],

				'product_img' => 'https://estore.avision24x7.com/'.$row['product_img'],

				'product_price' => $row['product_price'],

				'product_offer_price' => $row['product_offer_price']

			);

		}

		

		$data = array(

		

			'vdo_data' => $vdo_arr,

			'status' => 200,

			'message' => 'data found'

		);

		}else{

			

			$vdo_arr = array();

			$data = array(

		

			'vdo_data' => $vdo_arr,

			'status' => 203,

			'message' => 'data not found'

		);

		}

		

		echo json_encode($data);

	}

	

	public function fetchall_VideoCourse(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->fetchall_VideoCourse();

		if(!empty($result)){

		foreach($result as $row){
			if(!empty($row['product_tags'])){
				$tags_arr = array();
				$tags = explode(',',$row['product_tags']);
				foreach($tags as $tag_val){
					array_push($tags_arr,$tag_val);
				}
			}else{
				$tags_arr =[];
			}
			$vdo_arr[] = array(
				
				'product_id' => $row['product_id'],
				
				'product_slug' => $row['product_slug'],

				'product_name' => $row['product_name'],

				'product_desc' => $row['product_desc'],

				'product_img' => 'https://estore.avision24x7.com/'.$row['image'],
				
				'product_tags'	=> $tags_arr,

				'product_price' => $row['product_price'],

				'product_offer_price' => $row['product_offer_price']

			);

		}

		

		$data = array(

		

			'vdo_data' => $vdo_arr,

			'status' => 200,

			'message' => 'data found'

		);

		}else{

			

			$vdo_arr = array();

			$data = array(

		

			'vdo_data' => $vdo_arr,

			'status' => 203,

			'message' => 'data not found'

		);

		}

		

		echo json_encode($data);

	}

	

	public function live_class_fetch(){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$result = $this->api_model->live_class_fetch();

		

		if(!empty($result)){

		foreach($result as $row){

			

			$vdo_arr[] = array(

				'product_id' => $row['product_id'],
				
				'product_slug' => $row['product_slug'],

				'product_name' => $row['product_name'],

				'product_price' => $row['product_price'],

				'product_offer_price' => $row['product_offer_price'],

				'live_class_title' => $row['live_class_title'],

				'feature' => strip_tags($row['feature']),
				
				'live_class' => strip_tags($row['live_class']),
				
				'mock_test' => strip_tags($row['mock_test']),
				
				'pdf_notes' => strip_tags($row['pdf_notes']),
				
				'practice_question' => strip_tags($row['practice_question']),

				'description' => substr(strip_tags($row['description']),0,150),

				'image' => 'https://estore.avision24x7.com/'.$row['image'],

				'start_date' => $row['start_date'],

			);

		}

		

		$data = array(

		

			'live_cls_data' => $vdo_arr,

			'status' => 200,

			'message' => 'data found'

		);

		}else{

			

			$vdo_arr = array();

			$data = array(

		

			'live_cls_data' => $vdo_arr,

			'status' => 203,

			'message' => 'data not found'

		);

		}

		

		echo json_encode($data);

	}

	

public function live_class_details($product_id){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		date_default_timezone_set("Asia/Kolkata");

		$result = $this->api_model->live_class_details($product_id);

		$result_subject = $this->api_model->live_cls_subject($product_id);

		if(!empty($result_subject)){

			

			

		

		foreach($result_subject as $row_subject){

			

			$result_chapter = $this->api_model->live_cls_chapter($row_subject['sub_id'],$product_id);

			foreach($result_chapter as $row_chapter){

				

				$result_videos = $this->api_model->live_cls_videos($row_subject['sub_id'],$row_chapter['chapter_id'],$product_id);

				

				foreach($result_videos as $row_videos){

					

					$live_vdo[] = array(

					

						'video_id' => $row_videos['video_id'],

						'vdo_title' => $row_videos['vdo_title'],

						'vdo_date' => $row_videos['vdo_date'],

						'time' => $row_videos['time'],

						'province' => $row_videos['am/pm']

					);

				}

				

				$live_chapter[] = array(

				

					'chapter_id' => $row_chapter['chapter_id'],

					'chapter_name' => $row_chapter['chapter_name'],

					'videos'	=> 	$live_vdo

				);

				$live_vdo = array();

			}

			

			$live_subject[] = array(

			

				'sub_id' => $row_subject['sub_id'],

				'sub_name' => $row_subject['type_name'],

				'chapter'	=> $live_chapter

	

			);

		}

	}

		$date=date_create(date('Y-m-d'));
		$start_date = date_format($date,"jS M Y");
		$cur_day='';
		$date1=date_create(date($result[0]['start_date']));
		$date2=date_create(date('Y-m-d'));
		$diff=date_diff($date1,$date2);
		$date_msg=$diff->format("%R%a");
		if($result[0]['start_date'] != date('Y-m-d') && $date_msg < 0){
		
			$cur_day = $date_msg;
		}else{
			$result_cur_day = $this->api_model->fetch_cur_day(date('Y-m-d'),$product_id);
			$cur_day = $result_cur_day;
		}

			$product_details_arr = array(

			

				'product_id' => $result[0]['product_id'],

				'product_name' => $result[0]['product_name'],

				'product_price' => $result[0]['product_price'],

				'product_offer_price' => $result[0]['product_offer_price'],

				'validity' => $result[0]['validity'],

				'feature' => $result[0]['feature'],

				'description' => $result[0]['description'],
				
				'live_class'	=> $result[0]['live_class'],
				
				'mock_test'	=> $result[0]['mock_test'],
				
				'pdf_notes'	=> $result[0]['pdf_notes'],
				
				'practice_question'	=> $result[0]['practice_question'],

				'image' => "https://estore.avision24x7.com/".$result[0]['image'],

				'start_date' => $result[0]['start_date'],

				'end_date' => $result[0]['end_date'],

				'cur_date_string' => $start_date,

				'cur_day' => $cur_day

			);

			if(!empty($live_subject)){

				$data = array(

		

				'live_cls_dtls_data' => $product_details_arr,

				'live_cls_vdo_dtls_data' => $live_subject,

				'status' => 200,

				'message' => 'data found'

			);

				

			}else{

				$data = array(

		

				'live_cls_dtls_data' => $product_details_arr,

				'live_cls_vdo_dtls_data' => [],

				'status' => 203,

				'message' => 'data found'	

			);

				

			}

			

			

		

		

		echo json_encode($data);

		

	}

	public function fetch_live_class_video($id){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		

		$str_id = explode("-",$id);

		$product_id = $str_id[0];

		$day_id = $str_id[1];

		

		$result = $this->api_model->fetch_live_class_video($product_id,$day_id);

		

		foreach($result as $row){

			$vdo_url='';

			$upload_stat=0;
			
			$result_demo = $this->api_model->checkdemo($row['video_id']);
			
			$demo_status = 0;
			
			if($result_demo){
				$demo_status = 1;
			}else{
				$demo_status = 0;
			}

			if($row['vdo_url'] == ''){

				

				$vdo_url = $row['youtube_url'];

			}else if($row['youtube_url'] == ''){

				

				$vdo_url = $row['vdo_url'];

			}

			

			if($row['youtube_url'] == '' && $row['vdo_url'] == ''){

				$upload_stat= 0;

			}else{

				$upload_stat= 1;

			}

			$date=date_create($row['vdo_date']);

			$vdo_date = date_format($date,"jS M Y");
			if($row['vdo_pdf'] !=''){
				
				$vdo_pdf = 'https://estore.avision24x7.com/'.$row['vdo_pdf'];
			}else{
				$vdo_pdf='';
			}

			$vdo_arr[] = array(

			

				'vdo_id' => $row['video_id'],

				'vdo_title' => $row['vdo_title'],

				'vdo_date' => $vdo_date,

				'time' => $row['time'],

				'province' => $row['am/pm'],

				'vdo_url' => $vdo_url,

				'subject' => $row['type_name'],

				'chapter' => $row['chapter_name'],

				'vdo_banner' => 'https://estore.avision24x7.com/'.$row['vdo_banner'],
				
				'vdo_pdf'	=>  $vdo_pdf,

				'upload_stat'	=> $upload_stat,
				
				'demo_status' => 	$demo_status

			

			);

		}

		

		$data = array(

		

			'live_cls_vdo_list' => $vdo_arr,

			'status' => 200,

			'message' => 'data found'

			);

			

		

		

		echo json_encode($data);

	}

	

	public function live_class_check_buystat($product_id,$user_id){

		

		header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header("Access-Control-Allow-Headers: authorization, Content-Type");

		$result = $this->api_model->live_class_check_buystat($product_id,$user_id);

		

		if(!empty($result)){

			

			$data = array(

		

			'buy_stat' => $result[0]['buy_status'],

			'status' => 200,

			'message' => 'data found'

			);

		}else{

			

			$data = array(

		

			'buy_stat' => 0,

			'status' => 203,

			'message' => 'data found'

			);

			

			

		}

		

		

		echo json_encode($data);

	}

	

	public function get_all_subject(){

    	header("Access-Control-Allow-Origin: *");

    	$estore_avision = $this->load->database('estore_avision', TRUE);

     	$estore_avision->select('*');

        $estore_avision->from('add_question_type');

		$query = $estore_avision->get()->result_array();

		

        return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($query));

    }



    public function get_all_chapter($id){

    	header("Access-Control-Allow-Origin: *");

    	$estore_avision = $this->load->database('estore_avision', TRUE);

        $estore_avision->select('*');

        $estore_avision->from('chapter');

       $estore_avision->where('parent_subject_id',$id);

	   $query = $estore_avision->get()->result_array();

		

        return $this->output

			->set_content_type('application/json')

			->set_output(json_encode($query));

    }



	public function insert_doubt_details(){

		 header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");



		//$formdata = json_decode(file_get_contents('php://input'), true);

		$json = file_get_contents('php://input');
		if(isset($json)){
			$data = json_decode($json);
				$type = trim($data->type);
				$sub_id = trim($data->sub_id);
				$chap_id = trim($data->chap_id);
				$title = trim($data->title);
				$desc = trim($data->desc);
				$user_id = trim($data->user_id);
				$product_id = trim($data->product_id);
				$registerData=array(
					"type"=>$type,
					"sub_id"=>$sub_id,
					"chap_id"=>$chap_id,
					"title"=>$title,
					"desc"=>$desc,
					"user_id"=>$user_id,
					"product_id"=>$product_id,
				);
			$result = $this->api_model->insert_doubt_details($registerData);
			if($result){
					
					$response=array(
					"status" => 200,
					"last_id"=>$result,
					'message' => "Suucessfully Posted"
					);
				}else{
					
					$response=array(
					"status" => 203,
					"last_id" =>0,
					'message' => "post Unsuccessfull"
					);
				}

				 echo json_encode($response);	
		}
		
		
    }
	
	public function insert_comment_details(){

		 header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");



		//$formdata = json_decode(file_get_contents('php://input'), true);

		$json = file_get_contents('php://input');
		if(isset($json)){
			$data = json_decode($json);
				$comment = trim($data->comment);
				$doubt_id = trim($data->doubt_id);
				$user_id = trim($data->user_id);
				$comentData=array(
					"comment" =>$comment,
					"doubt_id" => $doubt_id,
					"user_id"=>$user_id
				);
			$result = $this->api_model->insert_comment_details($comentData);
			if($result){
					
					$response=array(
					"status" => 200,
					"last_id"=>$result,
					'message' => "Suucessfully Posted"
					);
				}else{
					
					$response=array(
					"status" => 203,
					"last_id" =>0,
					'message' => "post Unsuccessfull"
					);
				}

				 echo json_encode($response);	
		}
		
		
    }

	

	public function get_doubt_by_id(){

		

		header("Access-Control-Allow-Origin: *");

		header("Access-Control-Request-Headers: GET,POST,OPTIONS,DELETE,PUT");

		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');

		

		$result = $this->api_model->get_doubt_by_id();

		foreach($result as $row){

		    $comment_count = $this->api_model->count_comment($row['doubt_id']);

			$date_msg = '';

			$comment_count = $this->api_model->count_comment($row['doubt_id']);

			date_default_timezone_set("Asia/Kolkata");

			

			

			if($row['created_date'] == date('Y-m-d')){

					

					$date1=date_create(date($row['time']));

					$date2=date_create(date('H:i:s'));

					$diff=date_diff($date1,$date2);

					if($diff->format("%h") != 0){

						

						$date_msg=$diff->format("%h hours ago");

					}else if($diff->format("%i") != 0){

						$date_msg=$diff->format("%i minutes ago");

					}else{

						$date_msg = "Just Now";

					}

			}else{

				

					$date1=date_create(date($row['created_date']));

					$date2=date_create(date('Y-m-d'));

					$diff=date_diff($date1,$date2);

					$date_msg=$diff->format("%d days ago");

			}

			$firstChar = mb_substr($row['user_name'], 0, 1, "UTF-8");

			$doubt_arr[]= array(

				

				"doubt_title" => $row['doubt_title'],

				"doubt_desc" => $row['doubt_desc'],

				"user_name" => $row['user_name'],

				"first_char" => $firstChar,

				"date_msg"	=> $date_msg,

				"doubt_id"	=> $row['doubt_id'],

				"user_id"	=> $row['user_id'],

				"comment_count" => $comment_count

			);

		}

		

		if(!empty($doubt_arr)){

			

			$data = array(

			

				'dbt_arr' => $doubt_arr,

				'status'=> 200,

				'message' => "data found"		

			);

		}else{

			$doubt_arr =array();

			$data = array(

			

				'dbt_arr' => $doubt_arr,

				'status'=> 203,

				'message' => "data not found"		

			);

			

		}

		

		echo json_encode($data);

		

	}

	

	 public function get_doubt_all($prod_id){

		

		header("Access-Control-Allow-Origin: *");

		header("Access-Control-Request-Headers: GET,POST,OPTIONS,DELETE,PUT");

		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');

		date_default_timezone_set("Asia/Kolkata");

		$result = $this->api_model->get_doubt_all($prod_id);

			

		foreach($result as $row){

		    $comment_count = $this->api_model->count_comment($row['doubt_id']);

			$date_msg = '';

			

			if($row['created_date'] == date('Y-m-d')){

					

					$date1=date_create(date($row['time']));

					$date2=date_create(date('H:i:s'));

					$diff=date_diff($date1,$date2);

					if($diff->format("%h") != 0){

						

						$date_msg=$diff->format("%h hours ago");

					}else if($diff->format("%i") != 0){

						$date_msg=$diff->format("%i minutes ago");

					}else{

						$date_msg = "Just Now";

					}

			}else{

				

					$date1=date_create(date($row['created_date']));

					$date2=date_create(date('Y-m-d'));

					$diff=date_diff($date1,$date2);

					$date_msg=$diff->format("%d days ago");

			}

			$firstChar = mb_substr($row['user_name'], 0, 1, "UTF-8");

			$doubt_arr[]= array(

				

				"doubt_title" => $row['doubt_title'],

				"doubt_desc" => $row['doubt_desc'],

				"user_name" => $row['user_name'],

				"first_char" => $firstChar,

				"date_msg"	=> $date_msg,

				"doubt_id"	=> $row['doubt_id'],

				"user_id"	=> $row['user_id'],

				"comment_count" => $comment_count

				

			);

		}

		

		if(!empty($doubt_arr)){

			

			$data = array(

			

				'dbt_arr' => $doubt_arr,

				'status'=> 200,

				'message' => "data found"		

			);

		}else{

			$doubt_arr = array();

			$data = array(

			

				'dbt_arr' => $doubt_arr,

				'status'=> 203,

				'message' => "data not found"		

			);

			

		}

		

		echo json_encode($data);

		

	}





	public function get_filter_doubts($sub_id,$chap_id,$prod_id){

		

		header("Access-Control-Allow-Origin: *");

		header("Access-Control-Request-Headers: GET,POST,OPTIONS,DELETE,PUT");

		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');

		date_default_timezone_set("Asia/Kolkata");

		$result = $this->api_model->get_filter_doubts($sub_id,$chap_id,$prod_id);

		

		foreach($result as $row){

		    $comment_count = $this->api_model->count_comment($row['doubt_id']);

			$date_msg = '';

			

			if($row['created_date'] == date('Y-m-d')){

					

					$date1=date_create(date($row['time']));

					$date2=date_create(date('H:i:s'));

					$diff=date_diff($date1,$date2);

					if($diff->format("%h") != 0){

						

						$date_msg=$diff->format("%h hours ago");

					}else if($diff->format("%i") != 0){

						$date_msg=$diff->format("%i minutes ago");

					}else{

						$date_msg = "Just Now";

					}

			}else{

				

					$date1=date_create(date($row['created_date']));

					$date2=date_create(date('Y-m-d'));

					$diff=date_diff($date1,$date2);

					$date_msg=$diff->format("%d days ago");

			}

			$firstChar = mb_substr($row['user_name'], 0, 1, "UTF-8");

			$doubt_arr[]= array(

				

				"doubt_title" => $row['doubt_title'],

				"doubt_desc" => $row['doubt_desc'],

				"user_name" => $row['user_name'],

				"first_char" => $firstChar,

				"date_msg"	=> $date_msg,

				"doubt_id"	=> $row['doubt_id'],

				"user_id"	=> $row['user_id'],

				"comment_count" => $comment_count

				

			);

		}

		

		if(!empty($doubt_arr)){

			

			$data = array(

			

				'dbt_arr' => $doubt_arr,

				'status'=> 200,

				'message' => "data found"		

			);

		}else{

			$doubt_arr = array();

			$data = array(

			

				'dbt_arr' => $doubt_arr,

				'status'=> 203,

				'message' => "data not found"		

			);

			

		}

		

		echo json_encode($data);

	}





	public function comment_doubt_fetch($id){

		

		header("Access-Control-Allow-Origin: *");

		header("Access-Control-Request-Headers: GET,POST,OPTIONS,DELETE,PUT");

		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');

		date_default_timezone_set("Asia/Kolkata");

		$result = $this->api_model->comment_doubt_fetch($id);

		foreach($result as $row){

			$date_msg = '';

			$comment_count = $this->api_model->count_comment($row['doubt_id']);

			if($row['created_date'] == date('Y-m-d')){

					

					$date1=date_create(date($row['time']));

					$date2=date_create(date('H:i:s'));

					$diff=date_diff($date1,$date2);

					if($diff->format("%h") != 0){

						

						$date_msg=$diff->format("%h hours ago");

					}else if($diff->format("%i") != 0){

						$date_msg=$diff->format("%i minutes ago");

					}else{

						$date_msg = "Just Now";

					}

			}else{

				

					$date1=date_create(date($row['created_date']));

					$date2=date_create(date('Y-m-d'));

					$diff=date_diff($date1,$date2);

					$date_msg=$diff->format("%d days ago");

			}

			$firstChar = mb_substr($row['user_name'], 0, 1, "UTF-8");

			$doubt_arr[]= array(

				

				"doubt_title" => $row['doubt_title'],

				"doubt_desc" => $row['doubt_desc'],

				"user_name" => $row['user_name'],

				"first_char" => $firstChar,

				"date_msg"	=> $date_msg,

				"doubt_id"	=> $row['doubt_id'],

				"user_id"	=> $row['user_id'],

				"comment_count" => $comment_count

			);

		}

		

		if(!empty($doubt_arr)){

			

			$data = array(

			

				'dbt_arr' => $doubt_arr,

				'status'=> 200,

				'message' => "data found"		

			);

		}else{

			$doubt_arr = array();

			$data = array(

			

				'dbt_arr' => $doubt_arr,

				'status'=> 203,

				'message' => "data not found"		

			);

			

		}

		

		echo json_encode($data);

		

		

	}



	
	

	public function liveclass_videos_subject($prod_id){

		header("Access-Control-Allow-Origin: *");

		header("Access-Control-Request-Headers: GET,POST,DELETE,PUT");

		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');



		$result = $this->api_model->liveclass_videos_subject($prod_id);

		

		if(!empty($result)){

			

			foreach($result as $row){

				$result_vdo_count = $this->api_model->liveclass_vdo_count($row['sub_id'],$prod_id);

				$subject_arr[] = array(

				

					'subject_id' => $row['sub_id'],

					'subject_name' => $row['type_name'],

					'live_vdo_count' => $result_vdo_count

				);

			}

		}

		

		if(!empty($subject_arr)){

			

			$data = array(

			

				'sub_arr' => $subject_arr,

				'status'=> 200,

				'message' => "data found"		

			);

		}else{

			$subject_arr =[];

			$data = array(

			

				'sub_arr' => $subject_arr,

				'status'=> 203,

				'message' => "data found"		

			);

		}

		

		echo json_encode($data);

	}



	

	public function fetchLiveclassBysubject($sub_id,$prod_id){

		

		header("Access-Control-Allow-Origin: *");

		header("Access-Control-Request-Headers: GET,POST,DELETE,PUT");

		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');

		

		$result_chapter = $this->api_model->live_cls_chapter($sub_id,$prod_id);

		

		foreach($result_chapter as $row_chapter){

				

				$result_videos = $this->api_model->live_cls_videos($sub_id,$row_chapter['chapter_id'],$prod_id);

				

				foreach($result_videos as $row_videos){

					

					$live_vdo[] = array(

					

						'video_id' => $row_videos['video_id'],

						'vdo_title' => $row_videos['vdo_title'],

						'vdo_date' => $row_videos['vdo_date'],

						'time' => $row_videos['time'],

						'province' => $row_videos['am/pm']

					);

				}

				if(!empty($live_vdo)){

					$live_chapter[] = array(

					

						'chapter_id' => $row_chapter['chapter_id'],

						'chapter_name' => $row_chapter['chapter_name'],

						'videos'	=> 	$live_vdo

					);

				}else{

					$live_chapter[] = array(

					

						'chapter_id' => $row_chapter['chapter_id'],

						'chapter_name' => $row_chapter['chapter_name'],

						'videos'	=> 	[]

					);

				}

				$live_vdo = array();

			}

			

			if(!empty($live_chapter)){

				

				$data = array(

			

				'chap_arr' => $live_chapter,

				'status'=> 200,

				'message' => "data found"		

			);

			}else{

				

				$data = array(

			

				'chap_arr' => [],

				'status'=> 203,

				'message' => "data not found"		

			);

			}

		

			

			echo json_encode($data);

	}

	

	public function fetch_user_name($user_id){

		

		header("Access-Control-Allow-Origin: *");

		header("Access-Control-Request-Headers: GET,POST,DELETE,PUT");

		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');

		

		$result = $this->api_model->fetch_user_name($user_id);

		

		foreach($result as $row){

			$firstChar = mb_substr($row['user_name'], 0, 1, "UTF-8");

			$user_arr[] = array(

			

				'user_id' => $user_id,

				'user_name' => $row['user_name'],

				'firstChar'	=> $firstChar

			

			);

		}

		

		if(!empty($user_arr)){

			

			$data = array(

			

				'user_arr' => $user_arr,

				'status' => 200,

				'message' => 'data found'

			);

	}else{

		

		$data = array(

			

				'user_arr' => [],

				'status' => 200,

				'message' => 'data not found'

			);

	}

		echo json_encode($data);

	}





	public function fetch_commentsById($doubt_id){

		

		header("Access-Control-Allow-Origin: *");

		header("Access-Control-Request-Headers: GET,POST,DELETE,PUT");

		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');

		

		$result = $this->api_model->fetch_commentsById($doubt_id);

		

		foreach($result as $row){

			$firstChar = mb_substr($row['user_name'], 0, 1, "UTF-8");

			$comment_arr[] = array(

			

				'comment_id' => $row['comment_id'],

				'coment'	=> $row['comment'],

				'count_comment'	=> count($result),

				'user_name'		=> $row['user_name'],

				'firstChar'	=> $firstChar

			

			);

		}

		

		if(!empty($comment_arr)){

			

			$data = array(

			

				'comment_arr' => $comment_arr,

				'status' => 200,

				'message' => 'data found'

			);

		}else{

			

			$data = array(

			

				'comment_arr' => [],

				'status' => 203,

				'message' => 'data not found'

			);

		}

		

		echo json_encode($data);

		

	}

	

	public function fetch_all_ori_vdo($product_id){

		

		header("Access-Control-Allow-Origin: *");

		header("Access-Control-Request-Headers: GET,POST,DELETE,PUT");

		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');

		

		date_default_timezone_set("Asia/Kolkata");

		

		$result = $this->api_model->fetch_all_ori_vdo($product_id);
		
		
		foreach($result as $row){

			

			$vdo_url='';

			if($row['vdo_url'] == ''){

				

				$vdo_url = $row['youtube_url'];

			}else if($row['youtube_url'] == ''){

				

				$vdo_url = $row['vdo_url'];

			}

			$date=date_create($row['vdo_date']);

			$vdo_date = date_format($date,"jS M Y");

			$today_status =0;

			if($row['vdo_date'] == date("Y-m-d")){

				

				$today_status = 1;

			}else{

				

				$today_status = 0;

			}

			$vdo_arr[] = array(

			

				'vdo_id' => $row['video_id'],

				'vdo_title' => $row['vdo_title'],

				'vdo_date' => $vdo_date,

				'time' => $row['time'],

				'province' => $row['am/pm'],

				'vdo_url' => $vdo_url,

				'subject' => $row['type_name'],

				'chapter' => $row['chapter_name'],

				'day_id'  => $row['day_id'],

				'vdo_banner' => 'https://estore.avision24x7.com/'.$row['vdo_banner'],
				
				'orientation' => 1,

				'today_status'	=> 	$today_status

			

			);

		} 

		

		if(!empty($vdo_arr)){

			

			$data = array(

		

			'live_cls_vdo_ori' => $vdo_arr,

			'status' => 200,

			'message' => 'data found'

			);

		}else{

			$data = array(

		

			'live_cls_vdo_ori' => [],

			'status' => 203,

			'message' => 'data found'

			);

			

		}

		

		echo json_encode($data);

	}
	
	public function live_class_dashboard_test($prod_id){
		
		header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Headers: authorization, Content-Type");
		
		$result = $this->api_model->live_class_dashboard_test($prod_id);
		foreach($result as $row){
			$test_arr[] = array(
			
				'quiz_id' => $row['quiz_id'],	
				'quiz_name' => $row['quiz_name'],
				'image'	=> 	'https://estore.avision24x7.com/'.$row['sub_category_image']
			
			);
			
		}
		
		if(!empty($test_arr)){
			
			$data = array(
		
			'test_arr' => $test_arr,
			'status' => 200,
			'message' => 'data found'
			);
		}else{
			
			$data = array(
		
			'test_arr' => [],
			'status' => 203,
			'message' => 'data not found'
			);
		}
		
		echo json_encode($data);
	}
	
	public function fetch_course_teacher($course_id){
		
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Request-Headers: GET,POST,DELETE,PUT");
		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');
		$result = $this->api_model->fetch_course_teacher($course_id);
		
		foreach($result as $row){
			
			$teacher_arr[] = array(
			
				'teacher_id' => $row['teacher_id'],
				'teacher_name' => $row['teacher_name'],
				'teacher_experience' => $row['teacher_experience'],
				'teacher_qualification' => $row['teacher_qualification'],
				'teacher_mentored' => $row['mentored'],
				'teacher_image' => $row['teacher_image'],
				'teacher_designation' => $row['teacher_designation']
			);
		}
		
		if(!empty($teacher_arr)){
			
			$data = array(
		
			'teacher_arr' => $teacher_arr,
			'status' => 200,
			'message' => 'data found'
			);
		}else{
			$data = array(
		
			'teacher_arr' => [],
			'status' => 203,
			'message' => 'data found'
			);
			
		}
		
		echo json_encode($data);
		
		
	}
	
	public function fetch_Video_Course_teacher($prodcut_id){
		
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Request-Headers: GET,POST,DELETE,PUT");
		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');
		$result = $this->api_model->fetch_Video_Course_teacher($prodcut_id);
		
		foreach($result as $row){
			
			$teacher_arr[] = array(
			
				'teacher_id' => $row['teacher_id'],
				'teacher_name' => $row['teacher_name'],
				'teacher_experience' => $row['teacher_experience'],
				'teacher_qualification' => $row['teacher_qualification'],
				'teacher_image' => $row['teacher_image'],
				'teacher_designation' => $row['teacher_designation']
			);
		}
		
		if(!empty($teacher_arr)){
			
			$data = array(
		
			'teacher_arr' => $teacher_arr,
			'status' => 200,
			'message' => 'data found'
			);
		}else{
			$data = array(
		
			'teacher_arr' => [],
			'status' => 203,
			'message' => 'data found'
			);
			
		}
		
		echo json_encode($data);
	}


	public function check_cupon_code(){
		
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Request-Headers: GET,POST,DELETE,PUT");
		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');
		
		$postdata = file_get_contents("php://input");
		
		$data = json_decode($postdata);
		
		$result = $this->api_model->check_cupon_code($data);
		
		echo json_encode($result);
	}

	
	
	public function fetch_demo_video($product_id){
		
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Request-Headers: GET,POST,DELETE,PUT");
		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');
		date_default_timezone_set("Asia/Kolkata");
		
		$result = $this->api_model->fetch_demo_video($product_id);
		
		foreach($result as $row){
			
			$vdo_url='';

			if($row['vdo_url'] == ''){

				

				$vdo_url = $row['youtube_url'];

			}else if($row['youtube_url'] == ''){

				

				$vdo_url = $row['vdo_url'];

			}

			$date=date_create($row['vdo_date']);

			$vdo_date = date_format($date,"jS M Y");
			
			$vdo_arr[] = array(

			

				'vdo_id' => $row['video_id'],

				'vdo_title' => $row['vdo_title'],

				'vdo_date' => $vdo_date,

				'time' => $row['time'],

				'province' => $row['am/pm'],

				'vdo_url' => $vdo_url,

				'subject' => $row['type_name'],

				'chapter' => $row['chapter_name'],

				'day_id'  => $row['day_id'],

				'vdo_banner' => 'https://estore.avision24x7.com/'.$row['vdo_banner'],

			

			);
		}
		
		if(!empty($vdo_arr)){
			
			$data = array(

			'live_cls_vdo_demo' => $vdo_arr,

			'status' => 200,

			'message' => 'data found'

			);
		}else{
			$data = array(

			'live_cls_vdo_demo' => [],

			'status' => 203,

			'message' => 'data not found'
			);
			
		}
		
		echo json_encode($data);
	}
	
	public function fetch_live_class_current_vdo($product_id){
		
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Request-Headers: GET,POST,DELETE,PUT");
		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');
		date_default_timezone_set("Asia/Kolkata");
		
		$result = $this->api_model->fetch_live_class_current_vdo($product_id);
		foreach($result as $row){
			
			$vdo_url='';

			if($row['vdo_url'] == ''){

				

				$vdo_url = $row['youtube_url'];

			}else if($row['youtube_url'] == ''){

				

				$vdo_url = $row['vdo_url'];

			}
			$today_status =0;

			if($row['vdo_date'] == date("Y-m-d")){

				

				$today_status = 1;

			}else{

				

				$today_status = 0;

			}

			$date=date_create($row['vdo_date']);

			$vdo_date = date_format($date,"jS M Y");
			
			$vdo_arr[] = array(

			

				'vdo_id' => $row['video_id'],

				'vdo_title' => $row['vdo_title'],

				'vdo_date' => $vdo_date,

				'time' => $row['time'],

				'province' => $row['am/pm'],

				'vdo_url' => $vdo_url,

				'subject' => $row['type_name'],

				'chapter' => $row['chapter_name'],

				'day_id'  => $row['day_id'],

				'vdo_banner' => 'https://estore.avision24x7.com/'.$row['vdo_banner'],
				
				'today_status'	=> 	$today_status

			

			);
		}
		
		if(!empty($vdo_arr)){
			
			$data = array(

			'live_cls_current_vdo' => $vdo_arr,

			'status' => 200,

			'message' => 'data found'

			);
		}else{
			$data = array(

			'live_cls_current_vdo' => [],

			'status' => 203,

			'message' => 'data not found'
			);
			
		}
		
		echo json_encode($data);
		
		
	}
	
	public function check_user_reg_date($user_id,$product_id){
		
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Request-Headers: GET,POST,DELETE,PUT");
		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');
		date_default_timezone_set("Asia/Kolkata");
		$result = $this->api_model->check_user_reg_date($user_id);
		$product_date = $this->api_model->product_start_date($product_id);
		
		$date1=date_create('2020-05-30');
		$date2=date_create($product_date);
		$diff=date_diff($date2,$date1);
		$date_msg=$diff->format("%R%a");
		
		if($date_msg>0 && $date_msg<365){
			
			$response = array(
			
				'join_stat' => 1
			);
		}else{
			$response = array(
			
				'join_stat' => 0
			);
		} 
		
		
		echo json_encode($response);
		
	}
	

	public function register(){
		
		 header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");

         
         
				$postdata = file_get_contents("php://input");
		  
				if(isset($postdata)){
				$request = json_decode($postdata);
				$name = trim($request->name);
				$pwd = trim($request->pwd);
				$email = trim($request->email);
				$phone = trim($request->phone);

				$registerData=array(
				"user_name"=>$name,
				"user_password"=>$pwd,
				"user_email"=>$email,
				"user_phone"=>$phone
				);
				$result = $this->api_model->register($registerData);
				if($result){
					
					$response=array(
					"status" => 200,
					"user_information"=>$registerData,
					"user_id"	=> $result,
					'message' => "Suucessfully Registered"
					);
				}else{
					
					$response=array(
					"status" => 203,
					"user_information" =>'',
					"user_id"	=> '',
					'message' => "Registration Unsuccessfull"
					);
				}
				

				//echo "data inserted";
				echo json_encode($response);

	}	
	}
	
	
	public function fetch_meta_data($page_slug){
		
		 header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
		
		 $result = $this->api_model->fetch_meta_data($page_slug);
		
		 foreach($result as $row){
			 
			 $meta_arr[] = array(
			 
				'page_id'	=> $row['page_id'],
				'page_title' => $row['page_title'],
				'page_content' => $row['page_content'],
				'page_description' => $row['page_description'],
				'seo_body'		=> $row['seo_body']
			 );
		 }

		if(!empty($meta_arr)){
			
			
			$data = array(
				
				'meta_data'	=> $meta_arr,
				'status'	=> 200,
				'message'	=> 'data found'
			);
		}else{
			
			$data = array(
				
				'meta_data'	=> [],
				'status'	=> 203,
				'message'	=> 'data not found found'
			);
		}

		echo json_encode($data);	
	}
	
	public function fetch_meta_inner_data($page_id,$id){
		
		header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
		
		 $result = $this->api_model->fetch_meta_inner_data($page_id,$id);
		
		 foreach($result as $row){
			 
			 $meta_arr[] = array(
			 
			 
				'page_title' => $row['page_title'],
				'page_content' => $row['page_content'],
				'page_description' => $row['page_description']
			 );
		 }

		if(!empty($meta_arr)){
			
			
			$data = array(
				
				'meta_data'	=> $meta_arr,
				'status'	=> 200,
				'message'	=> 'data found'
			);
		}else{
			
			$data = array(
				
				'meta_data'	=> [],
				'status'	=> 203,
				'message'	=> 'data not found found'
			);
		}

		echo json_encode($data);	
	}
	
	
	public function fetch_subcat_footer(){
		
		 header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
		
		 $result = $this->api_model->fetch_subcat_footer();
		 
		 foreach($result as $row){
			 
			 $sub_cat_arr[] = array(
			 
				'sub_category_id' => $row['sub_category_id'],
				'sub_category_name' => $row['sub_category_name']
			 ); 
		 }
		 
		 if(!empty($sub_cat_arr)){
			 
			 $data = array(
			 
				'sub_category_data' => $sub_cat_arr,
				'status' => 200,
				'msg'	=> 'Data Found'
			 );
		 }else{
			 
			 $data = array(
			 
				'sub_category_data' => [],
				'status' => 203,
				'msg'	=> 'Data not Found'
			 );
		 }
		 
		 echo json_encode($data);
	}
	
	public function fetch_course_detals($id){
		
		 header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
		 
		 $result = $this->api_model->fetch_course_detals($id);
		 $result_vdo_count = $this->api_model->count_noof_videos($id);
		 date_default_timezone_set("Asia/Kolkata");
		 
		 foreach($result as $row){
			 $offset = $row['validity']; 
			 $to_date = date('Y-m-d', strtotime("+$offset months", strtotime(date('Y-m-d'))));
			 $from_date = date('Y-m-d');
			 
			 $vdo_details_arr[] = array(
			 
				'product_id' => $row['product_id'], 
				'product_name' => $row['product_name'], 
				'product_desc' => $row['product_desc'], 
				'product_img' => 'https://estore.avision24x7.com/'.$row['product_img'],
				'product_price'	=> $row['product_price'],	
				'product_offer_price'	=> $row['product_offer_price'],
				'product_demo_video'	=> 	$row['product_demo_vdo'],
				'validity'	=> $row['validity'],
				'to_date'	=> $to_date,
				'from_date'	=> $from_date,
				'count_videos' => $result_vdo_count	
			 
			 );
		 }
		 
		 if(!empty($vdo_details_arr)){
			 
			 $data = array(
			 
				'vdo_details_data' => $vdo_details_arr,
				'status' => 200,
				'msg'	=> 'Data Found'
			 );
		 }else{
			 
			 $data = array(
			 
				'vdo_details_data' => [],
				'status' => 203,
				'msg'	=> 'Data not Found'
			 );
		 }
		 
		 echo json_encode($data);
		 
		 
	}
	
	public function fetch_video_course_subject($id){
		
		 header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
		 
		 $result = $this->api_model->fetch_video_course_subject($id);
		 
		 foreach($result as $row){
			 $result_count_videos = $this->api_model->fetch_video_count_subjectwise($row['subject_id'],$id);
			 
			 $vdo_course_subject[] = array(
			 
				"subject_id" =>	$row['subject_id'],	
				"subject_name" =>	$row['type_name'],	
				"subject_img" =>	'https://estore.avision24x7.com/'.$row['type_img'],
				"video_count"	=> 	$result_count_videos
				
			 
			 );
		 }
		 
		 if(!empty($vdo_course_subject)){
			 
			 $data = array(
			 
				'vdo_details_sub_data' => $vdo_course_subject,
				'status' => 200,
				'msg'	=> 'Data Found'
			 );
		 }else{
			 
			 $data = array(
			 
				'vdo_details_sub_data' => [],
				'status' => 203,
				'msg'	=> 'Data not Found'
			 );
		 }
		 
		 echo json_encode($data);
	}
	
	public function fetch_video_course_chapter($sub_id,$prod_id){
		
		 header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
		 
		 $result = $this->api_model->fetch_video_course_chapter($sub_id,$prod_id);
		 
		 foreach($result as $row){
			 $result_vdo = $this->api_model->vdochapwise($row['chapter_id'],$sub_id,$prod_id);
			 foreach($result_vdo as $row_vdo){
				 if(!empty($row_vdo['video_url'])){
					 
					$link_url = explode('/',$row_vdo["video_url"]) ;
					$vdo_str_id = $link_url[sizeof($link_url)-2];
					$url_id = $vdo_str_id;
				 }else{
					 $url_id = '';
				 }
				 $vdo_arr[] = array(
			 
					'video_id' => $row_vdo["video_id"],
					'video_name' => $row_vdo["video_name"],
					'video_url' => $url_id,
					'video_image' => 'https://estore.avision24x7.com/'.$row_vdo["video_image"],
					'demo_video'  => $row_vdo["demo_video"]	
			 
				);
				
				
			 }
			 $vdo_details_chapter[] = array(
			 
				'chapsubject_id' => $row['subject_id'],
				'chapter_id'	=> $row['chapter_id'],	
				'chapter_name'	=> $row['chapter_name'],
				'vdo_arr'		=> 	$vdo_arr
			 );
			 
			 $vdo_arr = array();
		 }
		 if(!empty($vdo_details_chapter)){
			 
			 $data = array(
			 
				'vdo_details_chapter_data' => $vdo_details_chapter,
				'status' => 200,
				'msg'	=> 'Data Found'
			 );
		 }else{
			 
			 $data = array(
			 
				'vdo_details_chapter_data' => [],
				'status' => 203,
				'msg'	=> 'Data not Found'
			 );
		 }
		 
		 echo json_encode($data);
		 
	}
	
	public function fetch_video_course_menu_chapter($sub_id,$prod_id){
		
		 header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
		 
		 $result = $this->api_model->fetch_video_course_chapter($sub_id,$prod_id);
		 
		 foreach($result as $row){
			 $result_vdo = $this->api_model->vdochapwise_menu($row['chapter_id'],$sub_id,$prod_id);
			 foreach($result_vdo as $row_vdo){
				 if(!empty($row_vdo['video_url'])){
					$link_url = explode('/',$row_vdo["video_url"]) ;
					$vdo_str_id = $link_url[sizeof($link_url)-2];
					$url_id = $vdo_str_id;
				 }else{
					 $url_id = '';
				 }
				 $vdo_arr[] = array(
			 
					'video_id' => $row_vdo["video_id"],
					'video_name' => $row_vdo["video_name"],
					'video_url' => $url_id,
					'video_image' => 'https://estore.avision24x7.com/'.$row_vdo["video_image"],
					'demo_video'  => $row_vdo["demo_video"]	
			 
				);
				
				
			 }
			 $vdo_details_chapter[] = array(
			 
				'chapsubject_id' => $row['subject_id'],
				'chapter_id'	=> $row['chapter_id'],	
				'chapter_name'	=> $row['chapter_name'],
				'vdo_arr'		=> 	$vdo_arr
			 );
			 
			 $vdo_arr = array();
		 }
		 if(!empty($vdo_details_chapter)){
			 
			 $data = array(
			 
				'vdo_details_chapter_data' => $vdo_details_chapter,
				'status' => 200,
				'msg'	=> 'Data Found'
			 );
		 }else{
			 
			 $data = array(
			 
				'vdo_details_chapter_data' => [],
				'status' => 203,
				'msg'	=> 'Data not Found'
			 );
		 }
		 
		 echo json_encode($data);
		 
	}
	
	public function fetch_course_video($prod_id,$chap_id,$sub_id){
		
		 header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
		 
		 $result = $this->api_model->fetch_course_video($prod_id,$chap_id,$sub_id);
		 
		 foreach($result as $row){
			 
			 $vdo_arr[] = array(
			 
				'video_id' => $row["video_id"],
				'video_name' => $row["video_name"],
				'video_url' => $row["video_url"],
				'video_image' => 'https://estore.avision24x7.com/'.$row["video_image"],
				'demo_video'  => $row["demo_video"]	
			 
			 );
		 }
		 
		 if(!empty($vdo_arr)){
			 
			 $data = array(
			 
				'vdo_chpater_wise' => $vdo_arr,
				'status' => 200,
				'msg'	=> 'Data Found'
			 );
		 }else{
			 
			 $data = array(
			 
				'vdo_chpater_wise' => [],
				'status' => 203,
				'msg'	=> 'Data not Found'
			 );
		 }
		 
		 echo json_encode($data);
		 
		 
	}
	
	public function login_data(){
		
		header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
		 
		 $postdata = file_get_contents("php://input");
		  
				if(isset($postdata)){
					$request = json_decode($postdata);
					$email = trim($request->email);
					$pwd = trim($request->pwd);

					$loginData=array(
					"user_email"=>$email,
					"user_password"=>$pwd
					);
					$result = $this->api_model->login_data($loginData);
					$user_name = $this->api_model->user_name($result);
					if($result){
						
						$response=array(
						"status" => 200,
						"user_information"=>$user_name,
						"user_id"	=> $result,
						'message' => "Suucessfully loggedIn"
						);
					}else{
						
						$response=array(
						"status" => 203,
						"user_information" =>'',
						"user_id"	=> '',
						'message' => "Login Unsuccessfull"
						);
					}
				

					//echo "data inserted";
					echo json_encode($response);

				}	
		 
		 
		
		
	}
	
	
	
	public function pay_now($user_id,$prod_id){
		
		header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
		 
		
			$result = $this->api_model->product_buy_now($user_id,$prod_id);
		
			include 'Instamojo.php';
			$api = new Instamojo\Instamojo('6ceacbabe0db17883dbfeabc49c0a820', '81ee2fa1024bf0b86ec196c6b54ffffc','https://www.instamojo.com/api/1.1/'); 
			//echo'Hello';
			//echo base_url();
			try {
				$response = $api->paymentRequestCreate(array(
				"purpose" => $result["product_name"],
				"amount" => $result["product_price"],
				"buyer_name" => $result['payer_name'],
				"phone" => $result['payer_phone'],
				"send_email" => true,
				"send_sms" => true,
				"email" => $result['payer_email'],
				'allow_repeated_payments' => false,
				"redirect_url" => base_url()."index.php/api/thankyou",
				"webhook" => base_url()."index.php/api/webhook"
				)); 
				//print_r($response);	
				//exit;

					$pay_ulr = $response['longurl'];
					//echo $pay_ulr;
				//Redirect($response['longurl'],302); //Go to Payment page
					//echo 'Hello Anirban';
					header("Location: $pay_ulr");
				//exit();

			}
			catch (Exception $e) {
				$data_res = array(
				
					'status' => 203,
					'message' => $e->getMessage(),
					'payment_success' => 0
				);
				
				echo json_encode($data_res);
			}

				
	}
	
	public function thankyou(){
		
		header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
		 
		$data['pay_id'] = $_GET["payment_request_id"];
		
	}
	
	public function checkVdoCourseBuyStat($user_id,$prod_id){
		
		header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
		 
		 $estore_avision = $this->load->database('estore_avision', TRUE);
		 
		 $estore_avision->select("*");
		 $estore_avision->from("product_buy_now");
		 $estore_avision->where("user_id",$user_id);
		 $estore_avision->where("product_id",$prod_id);
		 $result = $estore_avision->get()->result_array();
		 if(!empty($result)){
			 if($result[0]['status'] == 1){
			 
			 $data = array(
			 
				'status' => 200,
				'buy_stat' => 1
			 );
		 }else{
			  $data = array(
			 
				'status' => 203,
				'buy_stat' => 0
			 );
		 }
			 
		 }else{
			 $data = array(
			 
				'status' => 203,
				'buy_stat' => 0
			 );
		 }
		 
		 
		 echo json_encode($data);
	}
	
	
	
	public function add_user_inofo() {

		 header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");


		$postdata = file_get_contents("php://input");

		if(isset($postdata)) {
			$request = json_decode($postdata);
			$user_id =  $request->user_id;
			$dob = trim($request->dob);
			$category = trim($request->category);
			$location = trim($request->location);
			$language = trim($request->language);

			$inofoData = array(

				'user_id'=> $user_id,
				'dob' => $dob,
				'category' => $category,
				'location' => $location,
				'language' => $language,
				'created_date' => date("Y-m-d")

			);

			$result = $this->api_model->add_user_inofo($inofoData, $user_id);

			if($result){
						
				$response=array(
				"status" => 200,
				"user_information"=>$inofoData,				
				'message' => "Suucessfully Info Added"
				);
			}else{
				
				$response=array(
				"status" => 203,
				"user_information" =>'',
				"user_id"	=> '',
				'message' => "Info Addition Unsuccessfull"
				);
			}
		

			//echo "data inserted";
			echo json_encode($response);
		}		
	}

	public function get_user_info($user_id) 
	{
		 header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
         $result = $this->api_model->get_user_info($user_id);

         if($result){
						
				$response=array(
				"status" => 200,
				"user_information"=>$result,				
				'message' => "User Info"
				);
			}else{
				
				$response=array(
				"status" => 203,				
				'message' => "Something Wrong Happend"
				);
			}

			echo json_encode($response);
	}

	public function get_user_all_data($user_id) {
		header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");

         $result = $this->api_model->get_user_all_data($user_id);

         if($result){
						
				$response=array(
				"status" => 200,
				"user_information"=>$result,				
				'message' => "User Info"
				);
			}else{
				
				$response=array(
				"status" => 203,				
				'message' => "Something Wrong Happend"
				);
			}

			echo json_encode($response);

	}

	public function update_user_phone() {
		 header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
         $postdata = file_get_contents("php://input");

         if(isset($postdata)) {
			$request = json_decode($postdata);			
			$user_id =  $request->id;
			$phone = trim($request->phone);

			$inofoData = array(							
				'user_phone' => $phone,	
			);
		}

         $result = $this->api_model->update_user_phone($inofoData, $user_id);

         if($result){
						
				$response=array(
				"status" => 200,
				"user_phone"=>$phone,				
				'message' => "User Phone"
				);
			}else{
				
				$response=array(
				"status" => 203,				
				'message' => "Something Wrong Happend"
				);
			}

			echo json_encode($inofoData);

	}

	public function update_user_img() {
			 
	  	header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
         $postdata = file_get_contents("php://input");
	    $user_id = $_POST['user_id'];
	    $folderPath = "./media/profile_images/";
	    $msg = '';
	   
	    $file_tmp = $_FILES['image']['tmp_name'];
		$image_name = $_FILES['image']['name'];
	    $file_ext = pathinfo($image_name,PATHINFO_EXTENSION);
	    $unique_id = uniqid();
	    $file = $folderPath . $unique_id . '.'.$file_ext;
	    $result = move_uploaded_file($file_tmp, $file);

	    if($result){
						
			$msg = "Image Uploaded";
		}else{	
						
			$msg =  "Something Wrong Happend";
		}

		$absolute_file_path = "media/profile_images/".$unique_id . '.'.$file_ext;

		$data = array(
			'user_img' => base_url().$absolute_file_path 
		);

		$result = $this->api_model->update_user_img($data, $user_id);

		 if($result){						
				$response=array(
				"status" => 200,							
				'message' => "User Image Uploaded"
				);
			}else{
				
				$response=array(
				"status" => 203,				
				'message' => "Something Wrong Happend"
				);
			}

			echo json_encode($postdata);

		
	    
	}

	public function update_user_password() {

		 header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
         $postdata = file_get_contents("php://input");

         if(isset($postdata)) {
			$request = json_decode($postdata);			
			$prev_password =  $request->prev_password;
			$new_password = $request->new_password;
			$user_id = $request->id;

			$userPassword = array(							
				'user_password' => md5($new_password)	
			);
		}

         $result = $this->api_model->update_user_password($userPassword, $prev_password, $user_id);

         if($result){
						
				$response=array(
				"status" => 200,								
				'message' => "User Password Updated Succesfully"
				);
			}else{
				
				$response=array(
				"status" => 203,				
				'message' => "Current Password Doesn't Match"
				);
			}

			echo json_encode($response);		
	}


	public function update_exam_details() {

		 header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
         $postdata = file_get_contents("php://input");

         date_default_timezone_set("Asia/Kolkata");

        $response= [];

         if(isset($postdata)) {
			$request = json_decode($postdata);			
			$course_id =  $request->course_id;
			$checkboxarray = $request->checkBoxArray;
			$user_id = $request->id;

			foreach ($checkboxarray as $value) {
				$exam = array(
					'course_id' => $course_id,
					'subcategory_id' => $value,
					'user_id' => $user_id,
					'created_date' => date("Y-m-d") 
				);
				$result = $this->api_model->update_exam_details($exam);
			}

			

         if($result){
						
				$response=array(
				"status" => 200,								
				'message' => "Exam Added Succesfully"
				);
			}else{
				
				$response=array(
				"status" => 203,				
				'message' => "Exam Added Failed"
				);
			}
		}

         

			echo json_encode($response);		
	}

	public function get_exam_details() {
	 header("Access-Control-Allow-Origin: *");
	 header('Access-Control-Allow-Credentials: true');
	 header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
	 header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	 header("Content-Type: application/json; charset=UTF-8");
	 $response = [];
	 $postdata = file_get_contents("php://input");
	  if(isset($postdata)) {
		$request = json_decode($postdata);
		$user_id = $request->id;		
	 }	
	 
	 $result = $this->api_model->get_exam_details($user_id);

	 

	 if($result){
						
				$response=array(
				"status" => 200,
				"exam_category"=> $result,								
				'message' => "Exam Added Succesfully"
				);
			}else{
				
				$response=array(
				"status" => 203,
				"exam_category"=> array(),				
				'message' => "Nothing Added Yet."
				);
			}

		echo json_encode($result);
		
	}

	public function delete_exam_details() {

	 header("Access-Control-Allow-Origin: *");
	 header('Access-Control-Allow-Credentials: true');
	 header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
	 header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	 header("Content-Type: application/json; charset=UTF-8");
	 $response = [];
	 $postdata = file_get_contents("php://input");
	  if(isset($postdata)) {
		$request = json_decode($postdata);
		$suggested_id = $request->id;		
	 }
	 $result = $this->api_model->delete_exam_details($suggested_id);	 

	 if($result){
						
				$response=array(
				"status" => 200,												
				'message' => "Deleted Succesfully"
				);
			}else{
				
				$response=array(
				"status" => 203,							
				'message' => "Something Went Wrong"
				);
			}

		echo json_encode($response);

	}

	public function subCategoryNameUpdated($courseId, $user_id) {
		header("Access-Control-Allow-Origin: *");
		$coursesDetails = $this->api_model->subCategoryNameUpdated($courseId, $user_id);
		

		if($coursesDetails){
						
				$response=array(
				"status" => 200,
				"sub_category"=> $coursesDetails,												
				'message' => "Deleted Succesfully"
				);
			}else{
				
				$response=array(
				"status" => 203,							
				'message' => "Something Went Wrong"
				);
			}

		echo json_encode($response);		
	}
	
	public function fetch_demo_chapter($prod_id){
		
		 header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
		 
		 $result = $this->api_model->fetch_demo_chapter_vdo($prod_id);
		 
		 foreach($result as $row_vdo){
				
					
				 if(!empty($row_vdo['video_url'])){
					 
					$link_url = explode('/',$row_vdo["video_url"]) ;
					$vdo_str_id =$link_url[sizeof($link_url)-2];
					$url_id = $vdo_str_id;
				 }else{
					 $url_id = '';
				 }
				 $vdo_arr[] = array(
			 
					'video_id' => $row_vdo["video_id"],
					'video_name' => $row_vdo["video_name"],
					'video_url' => $url_id,
					'video_image' => 'https://estore.avision24x7.com/'.$row_vdo["video_image"],
					'demo_video'  => $row_vdo["demo_video"]	
			 
				);
				
		 }
		 if(!empty($vdo_arr)){
			 
			 $data = array(
			 
				'vdo_details_chapter_data' => $vdo_arr,
				'status' => 200,
				'msg'	=> 'Data Found'
			 );
		 }else{
			 
			 $data = array(
			 
				'vdo_details_chapter_data' => [],
				'status' => 203,
				'msg'	=> 'Data not Found'
			 );
		 }
		 
		 echo json_encode($data);
		 
	}
	
	public function data_rrb(){
		
		header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
		 
		 $result = $this->api_model->fetch_menu_data(13);

		 echo json_encode($result);
	}
	
	public function data_bank_others(){
		
		header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
		 
		 $result = $this->api_model->fetch_menu_data(14);

		 echo json_encode($result);
	}
	
	public function get_quiz_information($quiz_id)
	{

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$quiz_details = $this->api_model->get_quiz_information($quiz_id);

		if($quiz_details){

		$response=array(
		"status" => 200,
		"quiz_information"=> $quiz_details,
		'message' => "Data Found"
		);
		}else{

		$response=array(
		"status" => 203,
		'message' => "Something Went Wrong"
		);
		}
		echo json_encode($response);
	}

	public function get_quiz_question($quiz_id, $question_type_id,$test_taken_id)
	{
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		$quiz_question_answer = $this->api_model->get_quiz_question($quiz_id, $question_type_id, $test_taken_id);

		if($quiz_question_answer){

		$response=array(
		"status" => 200,
		"quiz_question_answer"=> $quiz_question_answer,
		'message' => "Data Found"
		);
		}else{

		$response=array(
		"status" => 203,
		'message' => "Something Went Wrong"
		);
		}
		echo json_encode($response);

	}
	
	public function start_quiz() {
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$response = [];
		$postdata = file_get_contents("php://input");
		 if(isset($postdata)) {
		$request = json_decode($postdata);
		$quiz_id = $request->quiz_id;
		$student_id = $request->user_id;
		}

		$result = $this->api_model->start_quiz($quiz_id, $student_id);

		if($result){

		$res=array(
		"status" => 200,
		"test_taken_id"=> $result,
		'message' => "Data Found"
		);
		}else{

		$res=array(
		"status" => 203,
		"test_taken_id"=> 0,
		'message' => "Something Went Wrong"
		);
		}
		echo json_encode($res);
	} 
	
	public function save_answer() {
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$response = [];
		$postdata = file_get_contents("php://input");
		 if(isset($postdata)) {
		$request = json_decode($postdata);
		$test_taken_id = $request->test_taken_id;
		$test_question_id = $request->test_question_id;
		$question_status = $request->question_status;
		$answer_id =   $request->answer_id;
		}

		$result = $this->api_model->save_answer($test_taken_id, $test_question_id, $question_status, $answer_id);

		if($result){

		$res=array(
		"status" => 200,
		"data" => $postdata,
		'message' => "Answer Saved"
		);
		}else{

		$res=array(
		"status" => 203,
		'message' => "Something Went Wrong"
		);
		}
		echo json_encode($result);


	}

	public function rank_n_score($user_id,$quiz_id){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$result = $this->api_model->rank_n_score($user_id,$quiz_id);
		
		if(!empty($result)){
			
			$res=array(
			"status" => 200,
			"score" => $result,
		'	message' => "Score Find"
			);
		}else{
			
			$res=array(
			"status" => 203,
			"score" => '',
		'	message' => "Score Did not found"
			);
		}
		
		echo json_encode($res);
	}


	public function sectional_analysis_mark($user_id,$quiz_id){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$result = $this->api_model->sectional_analysis_mark($user_id,$quiz_id);
		
		if(!empty($result)){
			
			$res=array(
			"status" => 200,
			"score" => $result,
		'	message' => "Score Find"
			);
		}else{
			
			$res=array(
			"status" => 203,
			"score" => '',
		'	message' => "Score Did not found"
			);
		}
		
		echo json_encode($res);
	}	
	
	
	public function get_product_buy_status($prodcut_id, $user_id) {
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$result = $this->api_model->get_product_buy_status($prodcut_id,$user_id);
		
		if(!empty($result)){
			
			$res=array(
			"status" => 200,
			"buy_status" => $result[0]['status'],
		'	message' => "Status Find"
			);
		}else{
			
			$res=array(
			"status" => 203,
			"buy_status" => 0,
		'	message' => "Status Did not found"
			);
		}		
		echo json_encode($res);
	}
	
	public function compare_with_topper($user_id,$quiz_id){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$result = $this->api_model->compare_with_topper($user_id,$quiz_id);
		
		if(!empty($result)){
			
			$res=array(
			"status" => 200,
			"compare_result" => $result,
			'message' => "Result Find"
			);
		}else{
			
			$res=array(
			"status" => 203,
			"buy_status" => [],
		'	message' => "Result Did not found"
			);
		}		
			echo json_encode($res);
		
	}
	
	public function compare_with_section_section($user_id,$quiz_id,$section_id){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$result = $this->api_model->compare_with_section_section($user_id,$quiz_id,$section_id);
		
		if(!empty($result)){
			
			$res=array(
			"status" => 200,
			"compare_result" => $result,
			'message' => "Result Find"
			);
		}else{
			
			$res=array(
			"status" => 203,
			"buy_status" => [],
		'	message' => "Result Did not found"
			);
		}		
			echo json_encode($res);
	}
	
	public function submit_exam() {

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$response = [];
		$postdata = file_get_contents("php://input");
		 if(isset($postdata)) {
			$request = json_decode($postdata);
			$test_taken_id = $request->test_taken_id;
			$student_id = $request->user_id;
			$status = $request->status;
			$quiz_id = $request->quiz_id;
		}
		$result = $this->api_model->submit_exam($test_taken_id, $student_id, $status,$quiz_id);

		if($result){
			$test_result = $this->api_model->test_result($test_taken_id,$student_id,$quiz_id);
			
			foreach($test_result as $row_test){
				
				$res_arr[] = array(
				
					'section_name' => $row_test['question_type_name'],
					'correcct' => $row_test['count_correct'],
					'wrong' => $row_test['count_wrong'],
					'skip' => $row_test['count_skipped']
				
				);
			}
		if(!empty($res_arr)){	
			$res=array(
			"status" => 200,
			"test_result" => $res_arr,
			'message' => "Quiz Submitted Succesfully"
			);
			}else{

			$res=array(
			"status" => 203,
			"test_result" => [],
			'message' => "Something Went Wrong"
			);
			}
		}
		echo json_encode($res);
	}
	
	public function pay_for_plan(){

			header("Access-Control-Allow-Origin: *");
			header('Access-Control-Allow-Credentials: true');
			header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
			header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
			header("Content-Type: application/json; charset=UTF-8");
		
		
			$postdata = file_get_contents("php://input");
			if(isset($postdata)) {
				$request = json_decode($postdata);
				$plan_id = $request->plan_id;
				$student_id = $request->user_id;
			}
			$estore_avision = $this->load->database('estore_avision', TRUE);
			$estore_avision->select('*');
			$estore_avision->from('estore_plan');
			$estore_avision->where('plan_id',$plan_id);
			$result_plan=$estore_avision->get()->result_array();

			

			$estore_avision = $this->load->database('estore_avision', TRUE);
			$estore_avision->select('*');
			$estore_avision->from('product_buy_now');
			$estore_avision->where('product_id',$plan_id);
			$estore_avision->where('user_id',$student_id);
			$estore_avision->where('product_type',2);
			$estore_avision->where('status',1);
			$buy_plan=$estore_avision->get()->num_rows();

			if($buy_plan == 0){

				$data = array(

				'status' => 200,	
				'plan_id' => $result_plan[0]['plan_id'],
				'plan_name' => $result_plan[0]['plan_name'],
				'price' => $result_plan[0]['price'],
				'offer_price' => $result_plan[0]['offer_price']

				);

			}else{

				$data = array(

				'status' => 203,
				'msg' => 'The plan already has been purchased'

				);
			}

			echo json_encode($data);

		}
	
	
// 		/*public function pay_for_plan(){

// 		header("Access-Control-Allow-Origin: *");
// 		header('Access-Control-Allow-Credentials: true');
// 		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
// 		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// 		header("Content-Type: application/json; charset=UTF-8");
		
		
// 		$postdata = file_get_contents("php://input");
// 		if(isset($postdata)) {
// 			$request = json_decode($postdata);
// 			$plan_id = $request->plan_id;
// 			$student_id = $request->user_id;
// 		}
// 		$estore_avision = $this->load->database('estore_avision', TRUE);
// 		$estore_avision->select('*');
// 		$estore_avision->from('estore_plan');
// 		$estore_avision->where('plan_id',$plan_id);
// 		$result_plan=$estore_avision->get()->result_array();
// 		$plan_offer_price = $result_plan[0]['offer_price'];
		
// 		$plan_duration = $result_plan[0]['duration'];
		
// 			$estore_avision->select('*');
// 			$estore_avision->from('users');
// 			$estore_avision->where('user_id',$student_id);
// 			$result=$estore_avision->get()->result_array();
// 			$payer_name=$result[0]['user_name'];
// 			$payer_email=$result[0]['user_email'];
// 			$payer_phone=$result[0]['user_phone'];
			
// 			/*insert information into plan_buy_now table*/

// 			$data = array(
// 				'plan_id' => $plan_id,
// 				'student_id' => $student_id,
// 				'status' => 0,
// 				'created_date' => date('Y-m-d')	
// 			);
// 			/*----------------To check whether buyer bought the plan already---------------------*/
// 			$estore_avision->select('*');
// 			$estore_avision->from('plan_buy_now');
// 			//$this->db->where('plan_id',$plan_id);
// 			$estore_avision->where('student_id',$student_id);
// 			$result_count_row = $estore_avision->get();

// 			if($result_count_row->num_rows()>0){
// 				$estore_avision->select('*');
// 				$estore_avision->from('plan_buy_now');
// 				//$this->db->where('plan_id',$plan_id);
// 				$estore_avision->where('student_id',$student_id);
// 				$result_check = $estore_avision->get()->result_array();
// 				$check_plan_id=$result_check[0]['plan_id'];
// 				$plan_shop_id = $result_check[0]['plan_shop_id'];

// 				$estore_avision->select('offer_price');
// 				$estore_avision->from('estore_plan');
// 				$estore_avision->where('plan_id',$check_plan_id);
// 				$result_current_plan_price = $estore_avision->get()->result_array();
// 				$current_plan_price = $result_current_plan_price[0]['offer_price'];

// 				$estore_avision->select('offer_price');
// 				$estore_avision->from('estore_plan');
// 				$estore_avision->where('plan_id',$plan_id);
// 				$result_request_plan_price = $estore_avision->get()->result_array();
// 				$request_plan_price = $result_request_plan_price[0]['offer_price'];
// 				/*echo $check_plan_id.'-----'.$plan_id;
// 				echo $current_plan_price.'-----'.$request_plan_price;
// 				exit;*/
// 				if($check_plan_id==$plan_id){

// 					$res = array(
// 						'status' => 204,
// 						'buy_stat' => 0
// 					);
// 					return $res;
// 				}else{
// 					$estore_avision->insert('plan_buy_now',$data);
// 					$insert_id = $estore_avision->insert_id();
// 				}
// 			}
// 			else{
// 				$estore_avision->insert('plan_buy_now',$data);
// 				$insert_id = $estore_avision->insert_id();

// 			}
// 			$res = array(
// 			'status' => 200,
// 			'buy_stat' => 0,
// 			'insert_id'	=> $insert_id
// 			);
			
// 			/*if($cupon_status == 1){

// 				$this->db->select('offer_price');
// 				$this->db->from('cupon_assigned_plan');
// 				$this->db->where('plan_id',$plan_id);
// 				$result_offer = $this->db->get()->result_array();

// 				$plan_offer_price = $result_offer[0]['offer_price'];
// 			}*/
// 			include 'Instamojo.php';
// 			$api = new Instamojo\Instamojo('6ceacbabe0db17883dbfeabc49c0a820', '81ee2fa1024bf0b86ec196c6b54ffffc','https://www.instamojo.com/api/1.1/'); 
			
// 			try {
// 				$response = $api->paymentRequestCreate(array(
// 				"purpose" => "Test Series For ".$plan_duration." Days",
// 				"amount" => $plan_offer_price,
// 				"buyer_name" => $payer_name,
// 				"phone" => $payer_phone,
// 				"send_email" => true,
// 				"send_sms" => true,
// 				"email" => $payer_email,
// 				'allow_repeated_payments' => false,
// 				"redirect_url" => base_url()."index.php/api/thankyou_1/".$insert_id,
// 				"webhook" => base_url()."index.php/front/front_ctr/webhook_1"
// 				)); 
				
// 				$pay_ulr = $response['longurl'];
				
// 				$data_res = array(
				
// 					'status' => 203,
// 					'pay_url' => $pay_ulr,
// 					'payment_success' => 0
// 				);
				
// 				echo json_encode($data_res);
// 				//redirect($pay_ulr);
				

// 			}
// 			catch (Exception $e) {
// 				//echo json_encode($e);
// 				/*$res = array(
				
// 					'status' => 203,
// 					'pay_url'	=> ''
// 				);*/
// 				$data_res = array(
				
// 					'status' => 203,
// 					'message' => $e->getMessage(),
// 					'payment_success' => 0
// 				);
				
// 				echo json_encode($data_res);
// 				//echo "Hello";

// 				//$this->session->set_flashdata('server_error','<p class="alert alert-danger"><span style="margin-right: 10px; color: red;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span>Make Payment after some during some server issue.</p>');
// 				//redirect(base_url().'front/front_ctr/plans',"refresh");
// 					//print('Error: ' . $e->getMessage());
// 			}
			
			
			
		
// 	}*/
	
		public function webhook_1() {
							
		$data = $_POST;
		$mac_provided = $data['mac'];  // Get the MAC from the POST data
		unset($data['mac']);  // Remove the MAC key from the data.

		$ver = explode('.', phpversion());
		$major = (int) $ver[0];
		$minor = (int) $ver[1];

		if($major >= 5 and $minor >= 4){
			 ksort($data, SORT_STRING | SORT_FLAG_CASE);
		}
		else{
			 uksort($data, 'strcasecmp');
		}

		// You can get the 'salt' from Instamojo's developers page(make sure to log in first): https://www.instamojo.com/developers
		// Pass the 'salt' without the <>.
		$mac_calculated = hash_hmac("sha1", implode("|", $data), "054af0f7e822468b813878c1534c552f");

		if($mac_provided == $mac_calculated){
			echo "MAC is fine";
			// Do something here
			if($data['status'] == "Credit"){
			   // Payment was successful, mark it as completed in your database  
						
						$to = 'avisioninstitute@gmail.com';
						$subject = 'Website Payment Request ' .$data['buyer_name'].'';
						$message = "<h1>Payment Details</h1>";
						$message .= "<hr>";
						$message .= '<p><b>ID:</b> '.$data['payment_id'].'</p>';
						$message .= '<p><b>Amount:</b> '.$data['amount'].'</p>';
						$message .= "<hr>";
						$message .= '<p><b>Name:</b> '.$data['buyer_name'].'</p>';
						$message .= '<p><b>Email:</b> '.$data['buyer'].'</p>';
						$message .= '<p><b>Phone:</b> '.$data['buyer_phone'].'</p>';
						
						
						$message .= "<hr>";

					  
						$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
						// send email
						mail($to, $subject, $message, $headers);




			}
			else{
			   // Payment was unsuccessful, mark it as failed in your database
			}
		}
		else{
			echo "Invalid MAC passed";
		}
	}
	
	public function thankyou_1(){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$pay_id = $_GET["payment_request_id"];
		 
		$res = array(
		
			'pay_id' => $data['pay_id']
			
		);
		
		echo json_encode($res);
		//echo end($this->uri->segment_array());
		/*$data['plan_shop_id'] = end($this->uri->segment_array());
		 $this->load->view('front/common/header');
		 $this->load->view('front/Instamojo');
		$this->load->view('front/thankyou_1',$data); 
		$this->load->view('front/common/footer');*/
	}
	
	public function get_quiz_question_sol($quiz_id, $question_type_id,$test_taken_id)
	{
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		$quiz_question_answer = $this->api_model->get_quiz_question_sol($quiz_id, $question_type_id, $test_taken_id);

		if($quiz_question_answer){

		$response=array(
		"status" => 200,
		"quiz_question_answer"=> $quiz_question_answer,
		'message' => "Data Found"
		);
		}else{

		$response=array(
		"status" => 203,
		'message' => "Something Went Wrong"
		);
		}
		echo json_encode($response);
	}
	
	public function teacherdetails(){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$result = $this->api_model->teacherdetails();
		
		if(!empty($result)){
			
			$res = array(
			
				'status'=> 200,	
				'teaching_detals' => $result,
				'message'	=> 'Data Found'
			
			);
			
		}else{
			$res = array(
			
				'status'=> 203,	
				'teaching_detals' => [],
				'message'	=> 'Data not Found'
			
			);
		}
		
		echo json_encode($res);
	}
	
	public function centerList(){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$result = $this->api_model->centerList();
		foreach($result as $row){
			
			$center_arr[] = array(
			    'center_id' => $row['center_id'],
			    'center_slug' => $row['slug'],
				'center_name' => $row['center_name'],
				'center_address' => strip_tags($row['center_address']),
				'center_phone' => $row['center_ph']
			
			);
		}
		if(!empty($center_arr)){
			
			$res = array(
			
				'status'=> 200,	
				'center_list' => $center_arr,
				'message'	=> 'Data Found'
			
			);
			
		}else{
			$res = array(
			
				'status'=> 203,	
				'center_list' => [],
				'message'	=> 'Data not Found'
			
			);
		}
		
		echo json_encode($res);
	}
	
	public function centerDetails($page_slug){
	    
	    header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		$result = $this->api_model->centerDetails($page_slug);
		if(!empty($result)){
		    
		    $center_dtls = array(
		        
		        'center_id' => $result[0]['center_id'],
		        'slug' => $result[0]['slug'],
		        'center_name' => $result[0]['center_name'],
		        'center_address' => strip_tags($result[0]['center_address']),
		        'center_ph' => $result[0]['center_ph'],
		        'center_email' => $result[0]['center_email'],
		        'center_map' => $result[0]['center_map']
		    );  
		    
		}
		
		if(!empty($center_dtls)){
		    
		    $res = array(
		        
		        'status'=> 200,	
				'center_details' => $center_dtls,
				'message'	=> 'Data Found'   
		        
		    );
		}else{
		    
		    $res = array(
		        
		        'status'=> 203,	
				'center_list' => [],
				'message'	=> 'Data Not Found'   
		        
		    );
		}
		
		echo json_encode($res);
		
	}
	
	public function centerMetaContent($page_slug){
	    
	    header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		$result = $this->api_model->centerMetaContent($page_slug);
	
		if(!empty($result)){
		    
		    
		    foreach($result as $rowMeta){
		        
		        $result_submeta =  $this->api_model->centerSubmetaContent($rowMeta['meta_id']); 
		        
		        foreach($result_submeta as $rowSubmeta){
		            
		            $submetaarr[] = array(
		                
		                'submeta_id' => $rowSubmeta['submeta_id'],
		                'submeta_name' => $rowSubmeta['submeta_name'],
		                'center_content' => $rowSubmeta['center_content'],
		                
		                );
		        }
		        
		        $metaArr[] = array(
		            
		          'meta_id' => $rowMeta['meta_id'],
		          'meta_name' => $rowMeta['meta_name'],
		          'subMetaContent' => $submetaarr
		            
		          );
		          
		          $submetaarr= array();
		    }
		    
		    if(!empty($metaArr)){
		        
		        $res = array(
		            
		            'status' => 200,
		            'center_content' => $metaArr,
		            'message'	=> 'Data Found'
		      );
		    }else{
		        $res = array(
		            
		            'status' => 203,
		            'center_content' => [],
		            'message'	=> 'Data not Found'
		      );
		        
		    }
		    
		    echo json_encode($res);
		    
		}
		
		
	}
	
	public function get_product_id($slug){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$result = $this->api_model->get_product_id($slug);
		
		if(!empty($result)){
			
			$res = array(
			
				'status'=> 200,	
				'product_id' => $result[0]['product_id'],
				'message'	=> 'Product Id Found'
			
			);
			
		}else{
			$res = array(
			
				'status'=> 203,	
				'product_id' => '',
				'message'	=> 'Product Id Not Found'
			
			);
		}
		
		echo json_encode($res);
	}
	
	public function submit_enquiry() {
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$response = [];
		$postdata = file_get_contents("php://input");
		if(isset($postdata)) {
		$request = json_decode($postdata);
		$firstName = $request->firstName;
		$lastName = $request->lastName;
		$email = $request->email;
		$phoneNo = $request->phoneNo;
		$examName = $request->examName;
		$message = $request->message;
		}
		
		

		$fullname = $firstName.' '.$lastName;

		$result = $this->api_model->submit_enquiry($fullname,$email,$phoneNo,$examName,$message);

		if($result){

		$response=array(
		"status" => 200,
		'message' => "Your Enquiry Submitted Succesfully"
		);
		}else{

		$response=array(
		"status" => 203,
		'message' => "Something Went Wrong"
		);
		}
		echo json_encode($response);
	}
	
	public function get_courses_name() {
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$result = $this->api_model->get_courses_name();

		if($result){

		$response=array(
		"status" => 200,
		'courses_name' => $result,
		'message' => "Data Found"
		);
		}else{

		$response=array(
		"status" => 203,
		'message' => "Something Went Wrong"
		);
		}
		echo json_encode($response);
	}
	
	public function getCourseId($slug){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$result = $this->api_model->getCourseId($slug);

		if($result){

		$response=array(
		"status" => 200,
		'courses_id' => $result,
		'message' => "Data Found"
		);
		}else{

		$response=array(
		"status" => 203,
		'courses_id' => '',
		'message' => "Something Went Wrong"
		);
		}
		echo json_encode($response);
	}
	
	public function study_list(){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$result = $this->api_model->study_list();
		
		foreach($result as $row){
			
			$study_arr[] = array(
			
				'list_id' => $row['type_id'],
				'list_name'	=> $row['type_name']
			);
		}
		if(!empty($study_arr)){
			$response=array(
			"status" => 200,
			'list_arr' => $study_arr,
			'message' => "Data Found"
			);
		
		}else{
			
			$response=array(
			"status" => 203,
			'list_arr' => [],
			'message' => "Something Went Wrong"
			);
		}
		
		echo json_encode($response);
		
	}
	
	public function get_chapter_list($sub_id){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$result = $this->api_model->get_chapter_list($sub_id);
		
		foreach($result as $row){
			
			$chap_arr[] = array(
			
				'list_id' => $row[''],
				'list_name'	=> $row['']
			);
		}
		if(!empty($chap_arr)){
			$response=array(
			"status" => 200,
			'list_arr' => $chap_arr,
			'message' => "Data Found"
			);
		
		}else{
			
			$response=array(
			"status" => 203,
			'list_arr' => [],
			'message' => "Something Went Wrong"
			);
		}
		
		echo json_encode($response);
	}

	
	public function get_quiz_information_resume($quiz_id, $user_id,$test_taken_id)
	{

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$quiz_details = $this->api_model->get_quiz_information_resume($quiz_id, $user_id,$test_taken_id);

		if($quiz_details){

		$response=array(
		"status" => 200,
		"quiz_information"=> $quiz_details,
		'message' => "Data Found"
		);
		}else{

		$response=array(
		"status" => 203,
		'message' => "Something Went Wrong"
		);
		}
		echo json_encode($response);
	}


	public function page_sectional_banner($page_id){
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$result = $this->api_model->page_sectional_banner($page_id);
		
		if(!empty($result)){
	
			foreach($result as $row){		
				$banner_arr[] = array(
					
					'banner_url' => $row['banner_url']
				);
			}
		}
		if(!empty($banner_arr)){
			$response=array(
			"status" => 200,
			"section_banner"=> $banner_arr,
			'message' => "Data Found"
			);
		}else{

			$response=array(
			"status" => 203,
			"section_banner"=> [],
			'message' => "Something Went Wrong"
			);
		}
		
		echo json_encode($response);
	}

	public function homePopupBanner(){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$result = $this->api_model->homePopupBanner();
		
		
		if(!empty($result)){
			$response=array(
			"status" => 200,
			"popup_banner" => $result[0]['pop_up_banner'],
			"popup_ststus" => $result[0]['popoup_status'],
			'message' => "Data Found"
			);
		}else{

			$response=array(
			"status" => 203,
			"popup_banner" => '',
			"popup_ststus" => 0,
			'message' => "Data Found"
			);
		}
		
		echo json_encode($response);
	}

	public function testSeriesBuyStat($user_id){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$result = $this->api_model->testSeriesBuyStat($user_id);
		
		
		if(!empty($result)){
			$response=array(
			"status" => 200,
			"plan_buy_stat" => 1,
			'message' => "Data Found"
			);
		}else{

			$response=array(
			"status" => 203,
			"plan_buy_stat" => 0,
			'message' => "Data Not Found"
			);
		}
		
		echo json_encode($response);
	}

	

	public function login_data_scholarship(){
		
		header("Access-Control-Allow-Origin: *");
         header('Access-Control-Allow-Credentials: true');
         header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
         header("Content-Type: application/json; charset=UTF-8");
		 
		 $postdata = file_get_contents("php://input");
		  
				if(isset($postdata)){
					$request = json_decode($postdata);
					$email = trim($request->email);
					$pwd = trim($request->pwd);

					$loginData=array(
					"user_email"=>$email,
					"user_password"=>$pwd
					);
					$result = $this->api_model->login_data_scholarship($loginData);
					$user_name = $this->api_model->user_name_scholarship($result);
					if($result){
						
						$response=array(
						"status" => 200,
						"user_information"=>$user_name,
						"user_id"	=> $result,
						'message' => "Suucessfully loggedIn"
						);
					}else{
						
						$response=array(
						"status" => 203,
						"user_information" =>'',
						"user_id"	=> '',
						'message' => "Login Unsuccessfull"
						);
					}
				

					//echo "data inserted";
					echo json_encode($response);

				}	
		 
		 
		
		
	}

	public function get_user_data_by_phone($phone_no) {
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");



		$result = $this->api_model->get_user_data_by_phone($phone_no);

		if($result){

		$response=array(
		"status" => 200,
		"user_information"=> $result,
		'message' => "Question Answer Added"
		);
		}else{

		$response=array(
		"status" => 203,
		'message' => "Something Went Wrong"
		);
		}
		echo json_encode($response);
	}

	public function add_user_data_by_phone() {

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$postdata = file_get_contents("php://input");


		if(isset($postdata)) {
		$request = json_decode($postdata);
		$email = $request->email;
		$name = $request->name;
		$email = $request->email;
		$phone = $request->phone;

		}



		$result = $this->api_model->add_user_data_by_phone($email, $name, $phone);

		if($result){

		$response=array(
		"status" => 200,
		"user_information"=> $result,
		'message' => "Question Answer Added"
		);
		}else{

		$response=array(
		"status" => 203,
		'message' => "Something Went Wrong"
		);
		}
		echo json_encode($response);

	}

		public function signup_wiith_password() {

			header("Access-Control-Allow-Origin: *");
			header('Access-Control-Allow-Credentials: true');
			header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
			header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
			header("Content-Type: application/json; charset=UTF-8");

			$postdata = file_get_contents("php://input");




			if(isset($postdata)) {
			$request = json_decode($postdata);
			$email = $request->email;
			$name = $request->name;
			$password = $request->password;
			$phone = $request->phone;

			}




			$result = $this->api_model->signup_wiith_password($email, $name, $password, $phone);

			if($result){

            $main_res = $this->sent_mail($email, $name);
			$response=array(
			"status" => 200,
			"user_information"=> $result,
			'message' => "Question Answer Added"
			);
			}else{

			$response=array(
			"status" => 203,
			'message' => "You Are Already Registered"
			);
			}
			echo json_encode($response);

		}


	    
public function get_free_quiz_question_answer($quiz_id) {
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");

$result = $this->api_model->get_free_quiz_question_answer($quiz_id);

if($result){

$response=array(
"status" => 200,
'quiz_details' => $result,
'message' => "Data Found"
);
}else{

$response=array(
"status" => 203,
'message' => "Something Went Wrong"
);
}
echo json_encode($response);
}


public function get_free_quiz_list_all($limit,$user_id) {
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Credentials: true');
	header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header("Content-Type: application/json; charset=UTF-8");

	if($user_id == 0) {
	$result = $this->api_model->get_free_quiz_list_all_no_user($limit);
	}
	else {
	$result = $this->api_model->get_free_quiz_list_all($limit,$user_id);
	}



	if($result){

	$response=array(
	"status" => 200,
	'quiz_details' => $result,
	'message' => "Data Found"
	);
	}else{

	$response=array(
	"status" => 203,
	'message' => "Something Went Wrong"
	);
	}
	echo json_encode($response);
}


public function get_small_quiz_question_type() {
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");

$result = $this->api_model->get_small_quiz_question_type();

if($result){

$response=array(
"status" => 200,
'quiz_type' => $result,
'message' => "Data Found"
);
}else{

$response=array(
"status" => 203,
'message' => "Something Went Wrong"
);
}
echo json_encode($response);
}

public function get_free_quiz_list_by_id($id,$limit) {
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");

$result = $this->api_model->get_free_quiz_list_by_id($id,$limit);

if($result){

$response=array(
"status" => 200,
'quiz_details' => $result,
'message' => "Data Found"
);
}else{

$response=array(
"status" => 203,
'message' => "Something Went Wrong"
);
}
echo json_encode($response);
}

public function add_topic_tests($quiz_id, $student_id) {
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");



$result = $this->api_model->add_topic_tests($quiz_id, $student_id);

if($result){

$response=array(
"status" => 200,
"student_taken_tests_id"=> $result,
'message' => "Quiz Added"
);
}else{

$response=array(
"status" => 203,
'message' => "Something Went Wrong"
);
}
echo json_encode($response);
}

public function add_free_quiz_student_question($test_question_id, $test_taken_id, $question_status) {

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");



$result = $this->api_model->add_free_quiz_student_question($test_question_id, $test_taken_id, $question_status);

if($result){

$response=array(
"status" => 200,
"question_insert_id"=> $result,
'message' => "Question Question Added"
);
}else{

$response=array(
"status" => 203,
'message' => "Something Went Wrong"
);
}
echo json_encode($response);
}


public function add_free_quiz_student_answer($test_question_id, $test_taken_id, $asnwer_id) {

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");



$result = $this->api_model->add_free_quiz_student_answer($test_question_id, $test_taken_id, $asnwer_id);

if($result){

$response=array(
"status" => 200,
"answer_insert_id"=> $result,
'message' => "Question Answer Added"
);
}else{

$response=array(
"status" => 203,
'message' => "Something Went Wrong"
);
}
echo json_encode($response);
}

public function submit_free_quiz($test_taken_id) {
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");



$result = $this->api_model->submit_free_quiz($test_taken_id);

if($result){

$response=array(
"status" => 200,
'message' => "Quiz Submitted Succesfully !"
);
}else{

$response=array(
"status" => 203,
'message' => "Something Went Wrong"
);
}
echo json_encode($response);
}

public function get_free_quiz_solution($quiz_id, $test_taken_id) {

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");
$quiz_question_answer = $this->api_model->get_free_quiz_solution($quiz_id, $test_taken_id);

if($quiz_question_answer){

$response=array(
"status" => 200,
"quiz_question_answer"=> $quiz_question_answer,
'message' => "Data Found"
);
}else{

$response=array(
"status" => 203,
'message' => "Something Went Wrong"
);
}
echo json_encode($response);
}

public function get_user_test_taken_id($quiz_id, $student_id){
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");
$test_taken_id = $this->api_model->get_user_test_taken_id($quiz_id, $student_id);

if($test_taken_id){

$response=array(
"status" => 200,
"test_taken_id"=> $test_taken_id,
'message' => "Data Found"
);
}else{

$response=array(
"status" => 203,
'message' => "Something Went Wrong"
);
}
echo json_encode($response);
}

public function get_practice_subject($user_id){
	
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Credentials: true');
	header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header("Content-Type: application/json; charset=UTF-8");
	
	$result = $this->api_model->get_practice_subject($user_id);
	
	
	
	if(!empty($result)){
		
		$res = array(
			
			"status" => 200,
			"sub_arr"=> $result,
			'message' => "Data Found"
		);
	}else{
		
		$res = array(
			
			"status" => 203,
			"sub_arr"=> [],
			'message' => "Data Not Found"
		);
	}
	
	echo json_encode($res);
}


	
	public function getschollarExamStat($test_taken_id){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$result = $this->api_model->getschollarExamStat($test_taken_id);
		
		if(!empty($result)){
			
			$res = array(
			
				"status" => 200,
				"exam_stat"=> $result[0]['status'],
				'message' => "Data Found"	
			);
		}else{
			$res = array(
			
				"status" => 203,
				"exam_stat"=> 0,
				'message' => "Data not Found"	
			);
		}
		
		echo json_encode($res);
	}
	
	public function get_practice_question($subject_id,$chapter_id){
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$result = $this->api_model->get_practice_question($subject_id,$chapter_id);
		foreach($result as $row){
			
			$question_arr[] = array(
			
				'question_id' => $row['question_id'],
				'question_statement' => $row['question_statement']
			);
		}	
		if(!empty($question_arr)){
				
			$res = array(
			
				"status" => 200,
				"question_arr"=> $question_arr,
				'message' => "Data Found"	
			);
		}else{
			$res = array(
			
				"status" => 203,
				"question_arr"=> [],
				'message' => "Data not Found"	
			);
		}
		
		echo json_encode($res);
		
		
		
	}
	

	
	public function get_practice_question_answer($question_id){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$result = $this->api_model->get_practice_question_answer($question_id);
		
		if(!empty($result)){
			
			foreach($result as $row){
				
				$question_ans_arr[] = array(
				
					'answer_id' => $row['answer_id'],
					'answer_text' => $row['answer_text'],
					'answer_status' => $row['answer_status']
				
				);
			}
		}
		
		if(!empty($question_ans_arr)){
			
			$res = array(
			
				"status" => 200,
				"question_ans_arr"=> $question_ans_arr,
				'message' => "Data Found"	
			);
		}else{
			$res = array(
			
				"status" => 203,
				"question_ans_arr"=> [],
				'message' => "Data not Found"	
			);
		}
		
		echo json_encode($res);
	}
	
	public function get_practice_question_answer_sol($question_id){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$result = $this->api_model->get_practice_question_answer_sol($question_id);
		
		foreach($result as $row){
			
			$question_sol_arr[] = array(
				
					'solution' => $row['solution']
				
				);
			
		}
		
		if(!empty($question_sol_arr)){
			
			$res = array(
			
				"status" => 200,
				"question_arr_sol"=> $question_sol_arr,
				'message' => "Data Found"	
			);
		}else{
			$res = array(
			
				"status" => 203,
				"question_arr_sol"=> [],
				'message' => "Data not Found"	
			);
		}
		
		echo json_encode($res);
	}
	
	public function get_parent_course_name() {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header("Content-Type: application/json; charset=UTF-8");

        $result = $this->api_model->get_parent_course_name();
        
        if($result){
        
        $response=array(
        "status" => 200,
        "courses_name"=> $result,
        'message' => "Data Found"
        );
        }else{
        
        $response=array(
        "status" => 203,
        'message' => "Something Went Wrong"
        );
        }
        echo json_encode($response);
}

        public function get_sub_courses_name($id){
        
            header("Access-Control-Allow-Origin: *");
                     header('Access-Control-Allow-Credentials: true');
                     header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
                     header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
                     header("Content-Type: application/json; charset=UTF-8");
            
            $result = $this->api_model->get_sub_courses_name($id);
            
            if($result){
            
            $response=array(
            "status" => 200,
            "sub_courses"=> $result,
            'message' => "Data Found"
            );
            }else{
            
            $response=array(
            "status" => 203,
            'message' => "Something Went Wrong"
            );
            }
            echo json_encode($response);
    }
    
    public function get_quiz_by_sub_category_id($id) {
        header("Access-Control-Allow-Origin: *");
              header('Access-Control-Allow-Credentials: true');
              header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
              header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
              header("Content-Type: application/json; charset=UTF-8");
              $result = $this->api_model->get_quiz_by_sub_category_id($id);
        
        if($result){
        $response=array(
        "status" => 200,
        "quiz_details"=> $result,
        'message' => "Data Found"
        );
        }else{
        
        $response=array(
        "status" => 203,
        'message' => "Something Went Wrong"
        );
        }
        echo json_encode($response);
    }
    
    public function get_previous_year_quiz_given_test($student_id) {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header("Content-Type: application/json; charset=UTF-8");
        $result = $this->api_model->get_previous_year_quiz_given_test($student_id);
        
        if($result){
        
        $response=array(
        "status" => 200,
        "quiz_detials"=> $result,
        'message' => "Data Found"
        );
        }else{
        
        $response=array(
        "status" => 203,
        'message' => "Something Went Wrong"
        );
        }
        echo json_encode($response);
    }
    
    

	
	
	
	
	
	public function get_practice_question_count($subject_id,$chapter_id){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$result = $this->api_model->get_practice_question_count($subject_id,$chapter_id);
			
			
		foreach($result as $row){
			
			$question_arr[] = array(
			
				'question_id' => $row['question_id'],
				'question_statement' => $row['question_statement']
			);
		}	
		if(!empty($question_arr)){
				
			$res = array(
			
				"status" => 200,
				"question_arr"=> $question_arr,
				'message' => "Data Found"	
			);
		}else{
			$res = array(
			
				"status" => 203,
				"question_arr"=> [],
				'message' => "Data not Found"	
			);
		}
		
		echo json_encode($res);
	}
	
	
	
	

	public function savePraciceTest(){

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$postdata = file_get_contents("php://input");
		 if(isset($postdata)) {
			$request = json_decode($postdata);
			$user_id = $request->user_id;
			$subject_id = $request->subject_id;
			$chapter_id = $request->chapter_id;
		}

		$result = $this->api_model->savePraciceTest($user_id,$subject_id,$chapter_id);

		if($result != 0){

			$res = array(

				'status' => 200,
				'test_taken_id' => $result,
				'message'	=> 'Data inserted'
			);
		}else{
			$res = array(

				'status' => 203,
				'test_taken_id' => $result,
				'message'	=> 'Data not inserted'
			);
		}

		echo json_encode($res);
	}

	public function savePraciceQuestion(){

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$postdata = file_get_contents("php://input");
		 if(isset($postdata)) {
			$request = json_decode($postdata);
			$test_taken_id = $request->test_taken_id;
			$question_id = $request->question_id;
			$answer_id = $request->answer_id;
			$anser_status = $request->anser_status;
		}

		$result = $this->api_model->savePraciceQuestion($test_taken_id,$question_id,$answer_id,$anser_status);

		if($result){

			$res = array(

				'status' => 200,
				'message'	=> 'Data inserted'
			);
		}else{
			$res = array(

				'status' => 203,
				'message'	=> 'Data not inserted'
			);
		}

		echo json_encode($res);

	}

	public function check_practice_test($user_id,$subject_id,$chapter_id){

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$result = $this->api_model->check_practice_test($user_id,$subject_id,$chapter_id);

		// if(!empty($result) || $result!=false){

		// 	foreach ($result as $row) {
		// 		# code...
			

		// 		$question_check_arr[] = array(

		// 			'question_id' => $row['question_id'],
		// 			'answer_id'	=> $row['answer_id'],
		// 			'answer_status' => $row['status']
	 
		// 		);
		// 	}
		// }

		if(!empty($result)){

			$res = array(

				'status' => 200,
				'question_check_arr' => $result,
				'message'	=> "Data Found"

			);
		}else{

			$res = array(

				'status' => 203,
				'question_check_arr' => [],
				'message'	=> "Data Not Found"

			);

		}

		echo json_encode($res);



	}
	
	public function check_practice_test_complete($user_id,$subject_id,$chapter_id){

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$result = $this->api_model->check_practice_test_complete($user_id,$subject_id,$chapter_id);

		

		if($result){

			$res = array(

				'status' => 200,
				'test_question_count' => $result,
				'message'	=> "Data Found"

			);
		}else{

			$res = array(

				'status' => 203,
				'test_question_count' => 0,
				'message'	=> "Data Not Found"

			);

		}

		echo json_encode($res);

	}
	
	
	public function fetch_practice_test_taken_id($user_id,$subject_id,$chapter_id){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$result = $this->api_model->fetch_practice_test_taken_id($user_id,$subject_id,$chapter_id);
		
		if($result != 0){
			
			$res = array(
			
				'status' => 200,
				'test_taken_id' => $result,
				'msg' => 	'data found'
			
			);
		}else{
			$res = array(
			
				'status' => 203,
				'test_taken_id' => 0,
				'msg' => 	'data Not found'
			
			);
		}
		
		echo json_encode($res);	
		
	}
    
    
    

	
	public function order_history($user_id) {

			header("Access-Control-Allow-Origin: *");
			header('Access-Control-Allow-Credentials: true');
			header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
			header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
			header("Content-Type: application/json; charset=UTF-8");
			$result = $this->api_model->order_history($user_id);

			if($result){

			$res = array(
			"status" => 200,
			"order_history"=> $result,
			'message' => "Data Found"
			);
			}

			else{
			$res = array(
			"status" => 203,
			"order_history"=> [],
			'message' => "Data not Found"
			);
			}

			echo json_encode($res);

	}

	public function get_complete_quiz_status($user_id){

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$result = $this->api_model->get_complete_quiz_status($user_id);


		if(!empty($result)){
		$response=array(
		"status" => 200,
		"quiz_details" => $result,
		'message' => "Data Found"
		);
		}else{

		$response=array(
		"status" => 203,
		"popup_banner" => '',
		"popup_ststus" => 0,
		'message' => "Data Found"
		);
		}

		echo json_encode($response);
	}

	public function get_practice_subject_home($user_id){

	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Credentials: true');
	header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header("Content-Type: application/json; charset=UTF-8");

	$result = $this->api_model->get_practice_subject($user_id);

	if($result){

	$res = array(

	"status" => 200,
	"sub_arr"=> $result,
	'message' => "Data Found"
	);
	}else{

	$res = array(

	"status" => 203,
	"sub_arr"=> [],
	'message' => "Data Not Found"
	);
	}

	echo json_encode($res);
	}

	public function get_product_brief($prod_id){

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$result = $this->api_model->get_product_brief($prod_id);

		if(!empty($result)){

		$res = array(

		"status" => 200,
		"product_brief"=> $result,
		'message' => "Data Found"
		);
		}else{

		$res = array(

		"status" => 203,
		"sub_arr"=> [],
		'message' => "Data Not Found"
		);
	}

	echo json_encode($res);



	}

	public function get_recomended_product($prod_id){

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$result = $this->api_model->get_recomended_product($prod_id);

		if(!empty($result)){

			$res = array(

			"status" => 200,
			"product_brief"=> $result,
			'message' => "Data Found"
			);
		}else{

			$res = array(

			"status" => 203,
			"sub_arr"=> [],
			'message' => "Data Not Found"
			);
		}

		echo json_encode($res);
	}
	
	public function sent_mail($email,$name){
	    
	    $to = $email;

        $subject = "All India Talent Search Exam For Avision";
        
        $message = "<html>
        <head><title>Talent Search Exam For Avision</title>
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
			</html>";
        
        $header = 'From: <avisioninstitute@gmail.com>' . "\r\n";
        $header .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
        
        
        if(mail($to,$subject,$message,$header)){
        
        return true;
        
        }else{
        
           return false;
        }
    }
    
    public function count_applied_question($user_id,$subject_id){

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$result = $this->api_model->count_applied_question($user_id,$subject_id);

		if(!empty($result)){

			$res = array(

				'status' => 200,
				'count'	=> $result,
				'message' => 'data found'
			);
		}else{
			$res = array(

				'status' => 203,
				'count'	=> [],
				'message' => 'data not found'
			);
		}

		echo json_encode($res);

	}
	
	public function get_practice_chapter($subject_id){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$result = $this->api_model->get_practice_chapter($subject_id);
		
		
		foreach($result as $row){			
			$chap_arr[] = array(
			
				'chapter_id' => $row['chapter_id'],
				'chapter_name' => $row['chapter_name'],
				'question_id' => $row['question_id'],
				'question_count' => $row['question_count']
			);
		}
		
		if(!empty($chap_arr)){
			
			$res = array(
				
				"status" => 200,
				"chap_arr"=> $chap_arr,
				'message' => "Data Found"
			);
		}else{
			
			$res = array(
				
				"status" => 203,
				"chap_arr"=> [],
				'message' => "Data Not Found"
			);
		}
		
		echo json_encode($res);
	}
	
	public function get_atse_stat($user_id){

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$result = $this->api_model->get_atse_stat($user_id);

		if($result == 0){

			$res = array(
				'status' => 203,
				'atse_stat'	=> 0,
				'message' => 'pause disable'
			);

			
		}else{

			$res = array(
				'status' => 200,
				'atse_stat'	=> 1,
				'message' => 'pause enable'
			);
		}

		echo json_encode($res);
	}
	
	public function get_product_slug($prodId){

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$result = $this->api_model->get_product_slug($prodId);

		if(!empty($result)){

			$res = array(
				'status' => 200,
				'product_slug'	=> $result[0]['product_slug'],
				'message' => 'data found'
			);

			
		}else{

			$res = array(
				'status' => 203,
				'product_slug'	=> '',
				'message' => 'no data found'
			);
		}

		echo json_encode($res)	;	
	}
	
	public function fetchstateParentCategory(){
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$result = $this->api_model->fetchstateParentCategory(5);

		if(!empty($result)){

			$res = array(
				'status' => 200,
				'sub_cta_parent'	=> $result,
				'message' => 'data found'
			);

			
		}else{

			$res = array(
				'status' => 203,
				'sub_cta_parent'	=> '',
				'message' => 'no data found'
			);
		}

		echo json_encode($res)	;	
	}
	
	public function submit_center_enquiry(){
	    
	    header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		
		$postdata = file_get_contents("php://input");
		 if(isset($postdata)) {
			$request = json_decode($postdata);
			$name = $request->name;
			$email = $request->email;
			$phone = $request->phone;
			$examName = $request->examName;
			$centerId = $request->centerId;        
		}
		
		$result =$this->api_model->submit_center_enquiry($name,$email,$phone,$examName,$centerId);
    
        if($result){
            
            $res = array(
                
                'status' => 200,
                'message' => 'Data Inserted'
           );
        }else{
            
            $res = array(
                
                'status' => 200,
                'message' => 'Data Inserted'
           );
        }
        
        echo json_encode($res);
		 
	}   
        

}

