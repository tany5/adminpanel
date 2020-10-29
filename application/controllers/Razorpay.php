<?php

require('config.php');
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

defined('BASEPATH') OR exit('No direct script access allowed');



class Razorpay extends CI_Controller { 

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

	public function create_order($reciecpt,$amount,$currency,$payment_capture,$keyId,$keySecret,$prod_id,$user_id,$cupon_code){

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$estore_avision = $this->load->database('estore_avision', TRUE);

		$data = array(
			'user_id' => $user_id,
			'prod_id' => $prod_id,
			'buy_status' => 0,
			'coupon_code' => $cupon_code,
			'created_date' => date('Y-m-d')
		);
		$estore_avision->select('*');
		$estore_avision->from('buy_liveclass');
		$estore_avision->where('user_id',$user_id);
		$estore_avision->where('prod_id',$prod_id);
		$res = $estore_avision->get()->num_rows();
		
		if($res == 0){

			$estore_avision->insert('buy_liveclass',$data);
		}
		

		$api = new Api($keyId, $keySecret);


		$orderData = [
		    'receipt'         => $reciecpt,
		    'amount'          => $amount * 100, // 2000 rupees in paise
		    'currency'        => $currency,
		    'payment_capture' => $payment_capture // auto capture
		];

		$razorpayOrder = $api->order->create($orderData);

		$razorpayOrderId = $razorpayOrder['id'];
		if($res == 0){

			$data = array(

				'status' => 200,
				'order_id' => $razorpayOrderId,
				'msg'	=> 'order_created'
			);

		}else{
			$data = array(

				'status' => 203,
				'order_id' => $razorpayOrderId,
				'msg'	=> 'order can not be created or already the product has been purchased'
			);	
		}	
			
			echo json_encode($data);
}

public function verify_signature($razorpay_payment_id,$order_id,$razorpay_signature,$key_id,$key_secret,$prod_id,$user_id){

	header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$success=true;

		$api = new Api($key_id, $key_secret);
		$data = array(

			'buy_status' => 1	
		);
		$estore_avision->where('user_id',$user_id);
		$estore_avision->where('prod_id',$prod_id);
		$estore_avision->update('buy_liveclass',$data);
		try
	    {
	        // Please note that the razorpay order ID must
	        // come from a trusted source (session here, but
	        // could be database or something else)
	        $attributes = array(
	            'razorpay_order_id' => $order_id,
	            'razorpay_payment_id' => $razorpay_payment_id,
	            'razorpay_signature' => $razorpay_signature
	        );

	        $var = $api->utility->verifyPaymentSignature($attributes);
	       
	    }
	    catch(SignatureVerificationError $e)
	    {
	        $success = false;
	        $error = 'Razorpay Error : ' . $e->getMessage();
	    }

	    if ($success === true)
		{
		    $data = array(

		    	'status' => 200,
		    	'verify_stat' => 1,
		    	'msg'	=> 'signature verified'

		    );
		}
		else
		{
		   $data = array(

		    	'status' => 203,
		    	'verify_stat' => 0,
		    	'msg'	=> 'signature not verified'

		    ); 
		}

		echo json_encode($data);
}


public function create_order_plan($reciecpt,$amount,$currency,$payment_capture,$keyId,$keySecret,$prod_id,$user_id){

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");

		$estore_avision = $this->load->database('estore_avision', TRUE);

		$data = array(
			'user_id' => $user_id,
			'product_id' => $prod_id,
			'product_type' => 2,
			'qty' => 1,
			'status' => 0,
			'created_date' => date('Y-m-d')
		);
		$estore_avision->select('*');
		$estore_avision->from('product_buy_now');
		$estore_avision->where('user_id',$user_id);
		$estore_avision->where('product_id',$prod_id);
		$estore_avision->where('status',1);
		$res = $estore_avision->get()->num_rows();
		if($res == 0){

			$estore_avision->insert('product_buy_now',$data);
		}
		

		$api = new Api($keyId, $keySecret);


		$orderData = [
		    'receipt'         => $reciecpt,
		    'amount'          => $amount * 100, // 2000 rupees in paise
		    'currency'        => $currency,
		    'payment_capture' => $payment_capture // auto capture
		];

		$razorpayOrder = $api->order->create($orderData);

		$razorpayOrderId = $razorpayOrder['id'];
		if($res == 0){

			$data = array(

				'status' => 200,
				'order_id' => $razorpayOrderId,
				'msg'	=> 'order_created'
			);

		}else{
			$data = array(

				'status' => 203,
				'order_id' => $razorpayOrderId,
				// 'msg'	=> 'order can not be created or already the product has been purchased'
				'msg' => $res
			);	
		}	
			
			echo json_encode($data);
}


	
	public function verify_signature_plan($razorpay_payment_id,$order_id,$razorpay_signature,$key_id,$key_secret,$prod_id,$user_id){

		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("Content-Type: application/json; charset=UTF-8");
		$estore_avision = $this->load->database('estore_avision', TRUE);
		$success=true;

		$api = new Api($key_id, $key_secret);
		$data = array(

			'status' => 1	
		);
		$estore_avision->where('user_id',$user_id);
		$estore_avision->where('product_id',$prod_id);
		$estore_avision->update('product_buy_now',$data);
		try
	    {
	        // Please note that the razorpay order ID must
	        // come from a trusted source (session here, but
	        // could be database or something else)
	        $attributes = array(
	            'razorpay_order_id' => $order_id,
	            'razorpay_payment_id' => $razorpay_payment_id,
	            'razorpay_signature' => $razorpay_signature
	        );

	        $var = $api->utility->verifyPaymentSignature($attributes);
	       
	    }
	    catch(SignatureVerificationError $e)
	    {
	        $success = false;
	        $error = 'Razorpay Error : ' . $e->getMessage();
	    }

	    if ($success === true)
		{
		    $data = array(

		    	'status' => 200,
		    	'verify_stat' => 1,
		    	'msg'	=> 'signature verified'

		    );
		}
		else
		{
		   $data = array(

		    	'status' => 203,
		    	'verify_stat' => 0,
		    	'msg'	=> 'signature not verified'

		    ); 
		}

		echo json_encode($data);	
	}


}
