<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends PS_Controller
{
  public $menu_code = 'SOODSO';
	public $menu_group_code = 'SO';
  public $menu_sub_group_code = 'ORDER';
	public $title = 'ออเดอร์';
  public $filter;
  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'orders/orders';
    $this->load->model('orders/orders_model');
    $this->load->model('masters/channels_model');
    $this->load->model('masters/payment_methods_model');
    $this->load->model('masters/customers_model');
    $this->load->model('orders/order_state_model');
    $this->load->model('masters/product_tab_model');
    $this->load->model('stock/stock_model');
    $this->load->model('masters/product_style_model');
    $this->load->model('masters/products_model');
    $this->load->model('orders/discount_model');

    $this->load->helper('channels');
    $this->load->helper('payment_method');
    $this->load->helper('customer');
    $this->load->helper('users');
    $this->load->helper('state');
    $this->load->helper('product_images');
    $this->load->helper('discount');

    $this->filter = getConfig('STOCK_FILTER');
  }


  public function index()
  {
    $filter = array(
      'code'          => get_filter('code', 'code', ''),
      'customer'      => get_filter('customer', 'customer', ''),
      'user'          => get_filter('user', 'user', ''),
      'reference'     => get_filter('reference', 'reference', ''),
      'ship_code'     => get_filter('shipCode', 'shipCode', ''),
      'channels'      => get_filter('channels', 'channels', ''),
      'payment'       => get_filter('payment', 'payment', ''),
      'from_date'     => get_filter('fromDate', 'fromDate', ''),
      'to_date'       => get_filter('toDate', 'toDate', '')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment  = 4; //-- url segment
		$rows     = $this->orders_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$orders   = $this->orders_model->get_data($filter, $perpage, $this->uri->segment($segment));
    $ds       = array();
    if(!empty($orders))
    {
      foreach($orders as $rs)
      {
        $rs->channels_name = $this->channels_model->get_name($rs->channels_code);
        $rs->payment_name  = $this->payment_methods_model->get_name($rs->payment_code);
        $rs->customer_name = $this->customers_model->get_name($rs->customer_code);
        $rs->total_amount  = $this->orders_model->get_order_total_amount($rs->code);
        $rs->state_name    = get_state_name($rs->state);
        $ds[] = $rs;
      }
    }

    $filter['orders'] = $ds;

		$this->pagination->initialize($init);
    $this->load->view('orders/orders_list', $filter);
  }



  public function add_new()
  {

    $this->load->view('orders/orders_add');
  }



  public function add()
  {
    if($this->input->post('customerCode'))
    {
      $book_code = getConfig('BOOK_CODE_ORDER');
      $date_add = db_date($this->input->post('date'));
      $code = $this->get_new_code($date_add);
      $role = 'S'; //--- S = ขาย
      $has_term = $this->payment_methods_model->has_term($this->input->post('payment'));

      $ds = array(
        'code' => $code,
        'role' => $role,
        'bookcode' => $book_code,
        'reference' => $this->input->post('reference'),
        'customer_code' => $this->input->post('customerCode'),
        'customer_ref' => $this->input->post('customer_ref'),
        'channels_code' => $this->input->post('channels'),
        'payment_code' => $this->input->post('payment'),
        'is_term' => ($has_term === TRUE ? 1 : 0),
        'user' => get_cookie('uname'),
        'remark' => addslashes($this->input->post('remark'))
      );

      if($this->orders_model->add($ds) === TRUE)
      {
        $arr = array(
          'order_code' => $code,
          'state' => 1,
          'update_user' => get_cookie('uname')
        );

        $this->order_state_model->add_state($arr);

        redirect($this->home.'/edit_detail/'.$code);
      }
      else
      {
        set_error('เพิ่มเอกสารไม่สำเร็จ กรุณาลองใหม่อีกครั้ง');
        redirect($this->home.'/add_new');
      }
    }
    else
    {
      set_error('ไม่พบข้อมูลลูกค้า กรุณาตรวจสอบ');
      redirect($this->home.'/add_new');
    }
  }




  public function add_detail($order_code)
  {
    $result = TRUE;
    $err = "";
    $err_qty = 0;
    $data = $this->input->post('data');
    $order = $this->orders_model->get_order($order_code);
    if(!empty($data))
    {
      foreach($data as $rs)
      {
        $code = $rs['code']; //-- รหัสสินค้า
        $qty = $rs['qty'];
        $item = $this->products_model->get($code);

        if( $qty > 0 )
        {
          $qty = ceil($qty);

          //---- ยอดสินค้าที่่สั่งได้
          $sumStock = $this->get_sell_stock($code);


          //--- ถ้ามีสต็อกมากว่าที่สั่ง หรือ เป็นสินค้าไม่นับสต็อก
          if( $sumStock >= $qty OR $item->count_stock == 0 )
          {

            //---- ถ้ายังไม่มีรายการในออเดอร์
            if( $this->orders_model->is_exists_detail($order_code, $code) === FALSE )
            {
              //---- คำนวณ ส่วนลดจากนโยบายส่วนลด
              $discount 	= $this->discount_model->get_item_discount($item->code, $order->customer_code, $qty, $order->payment_code, $order->channels_code, $order->date_add);


              $arr = array(
                      "order_code"	=> $order_code,
                      "style_code"		=> $item->style_code,
                      "product_code"	=> $item->code,
                      "product_name"	=> addslashes($item->name),
                      "cost"  => $item->cost,
                      "price"	=> $item->price,
                      "qty"		=> $qty,
                      "discount1"	=> $discount['discLabel1'],
                      "discount2" => $discount['discLabel2'],
                      "discount3" => $discount['discLabel3'],
                      "discount_amount" => $discount['amount'],
                      "total_amount"	=> ($item->price * $qty) - $discount['amount'],
                      "id_rule"	=> $discount['id_rule'],
                      "is_count" => $item->count_stock
                    );

              if( $this->orders_model->add_detail($arr) === FALSE )
              {
                $result = FALSE;
                $error = "Error : Insert fail";
                $err_qty++;
              }

            }
            else  //--- ถ้ามีรายการในออเดอร์อยู่แล้ว
            {
              $detail 	= $this->orders_model->get_order_detail($order_code, $item->code);
              $qty			= $qty + $detail->qty;
              //---- คำนวณ ส่วนลดจากนโยบายส่วนลด
              $discount 	= $this->discount_model->get_item_discount($item->code, $order->customer_code, $qty, $order->payment_code, $order->channels_code, $order->date_add);

              $arr = array(
                        "qty"		=> $qty,
                        "discount1"	=> $discount['discLabel1'],
                        "discount2" => $discount['discLabel2'],
                        "discount3" => $discount['discLabel3'],
                        "discount_amount" => $discount['amount'],
                        "total_amount"	=> ($item->price * $qty) - $discount['amount'],
                        "id_rule"	=> $discount['id_rule'],
                        "valid" => 0,
                        "is_saved" => 0
                        );

              if( $this->orders_model->update_detail($detail->id, $arr) === FALSE )
              {
                $result = FALSE;
                $error = "Error : Update Fail";
                $err_qty++;
              }

            }	//--- end if isExistsDetail
          }
          else 	// if getStock
          {
            $result = FALSE;
            $error = "Error : สินค้าไม่เพียงพอ";
          } 	//--- if getStock
        }	//--- if qty > 0
      }
    }

    echo $result === TRUE ? 'success' : ( $err_qty > 0 ? $error.' : '.$err_qty.' item(s)' : $error);
  }




  public function remove_detail($id)
  {
    $rs = $this->orders_model->remove_detail($id);
    echo $rs === TRUE ? 'success' : 'Can not delete please try again';
  }



  public function edit_order($code)
  {
    $ds = array();
    $rs = $this->orders_model->get_order($code);
    if(!empty($rs))
    {
      $rs->channels_name = $this->channels_model->get_name($rs->channels_code);
      $rs->payment_name  = $this->payment_methods_model->get_name($rs->payment_code);
      $rs->customer_name = $this->customers_model->get_name($rs->customer_code);
      $rs->total_amount  = $this->orders_model->get_order_total_amount($rs->code);
      $rs->user          = $this->user_model->get_name($rs->user);
      $rs->state_name    = get_state_name($rs->state);
    }

    $state = $this->order_state_model->get_order_state($code);

    $ost = array();

    if(!empty($state))
    {
      foreach($state as $st)
      {
        $ost[] = $st;
      }
    }
    $ds['state'] = $ost;
    $ds['order'] = $rs;
    $this->load->view('orders/order_edit', $ds);
  }




  public function edit_detail($code)
  {
    $this->load->helper('product_tab');
    $ds = array();
    $rs = $this->orders_model->get_order($code);
    if($rs->state <= 3)
    {
      $rs->customer_name = $this->customers_model->get_name($rs->customer_code);
      $ds['order'] = $rs;

      $details = $this->orders_model->get_order_details($code);
      $ds['details'] = $details;
      $this->load->view('orders/order_edit_detail', $ds);
    }

  }




  public function get_product_order_tab()
  {
    $ds = "";
  	$id_tab = $this->input->post('id');
  	$qs     = $this->product_tab_model->getStyleInTab($id_tab);
  	if( $qs->num_rows() > 0 )
  	{
  		foreach( $qs->result() as $rs)
  		{
        $style = $this->product_style_model->get($rs->style_code);

  			if( $style->active == 1 && $this->products_model->is_disactive_all($style->code) === FALSE)
  			{
  				$ds 	.= 	'<div class="col-lg-2 col-md-2 col-sm-3 col-xs-4"	style="text-align:center;">';
  				$ds 	.= 		'<div class="product" style="padding:5px;">';
  				$ds 	.= 			'<div class="image">';
  				$ds 	.= 				'<a href="javascript:void(0)" onClick="getOrderGrid(\''.$style->code.'\')">';
  				$ds 	.=					'<img class="img-responsive" src="'.get_cover_image($style->code, 'default').'" />';
  				$ds 	.= 				'</a>';
  				$ds	.= 			'</div>';
  				$ds	.= 			'<div class="description" style="font-size:10px; min-height:50px;">';
  				$ds	.= 				'<a href="javascript:void(0)" onClick="getOrderGrid(\''.$style->code.'\')">';
  				$ds	.= 			$style->code.'<br/>'. number($style->price,2);
  				$ds 	.=  		$style->count_stock == 1 ? ' | <span style="color:red;">'.$this->stock_model->get_style_sell_stock($style->code).'</span>' : '';
  				$ds	.= 				'</a>';
  				$ds 	.= 			'</div>';
  				$ds	.= 		'</div>';
  				$ds 	.=	'</div>';
  			}
  		}
  	}
  	else
  	{
  		$ds = "no_product";
  	}

  	echo $ds;
  }




  public function get_order_grid()
  {
    //----- Attribute Grid By Clicking image
    $style_code = $this->input->get('style_code');
  	$sc = 'not exists';
    $view = FALSE;
  	$sc = $this->getOrderGrid($style_code, $view);
  	$tableWidth	= $this->products_model->countAttribute($style_code) == 1 ? 600 : $this->getOrderTableWidth($style_code);
  	$sc .= ' | '.$tableWidth;
  	$sc .= ' | ' . $style_code;
  	$sc .= ' | ' . $style_code;
  	echo $sc;
  }



  public function getOrderGrid($style_code, $view = FALSE)
	{
		$sc = '';
    $style = $this->product_style_model->get($style_code);
		$isVisual = $style->count_stock == 1 ? FALSE : TRUE;
		$attrs = $this->getAttribute($style->code);

		if( count($attrs) == 1  )
		{
			$sc .= $this->orderGridOneAttribute($style, $attrs[0], $isVisual, $view);
		}
		else if( count( $attrs ) == 2 )
		{
			$sc .= $this->orderGridTwoAttribute($style, $isVisual, $view);
		}
		return $sc;
	}



  public function showStock($qty)
	{
		return $this->filter == 0 ? $qty : ($this->filter < $qty ? $this->filter : $qty);
	}



  public function orderGridOneAttribute($style, $attr, $isVisual, $view, $id_branch = 0)
	{
		$sc 		= '';
		$data 	= $attr == 'color' ? $this->getAllColors($style->code) : $this->getAllSizes($style->code);
		$items	= $this->products_model->get_style_items($style->code);
		$sc 	 .= "<table class='table table-bordered'>";
		$i 		  = 0;

    foreach($items as $item )
    {
      $id_attr	= $item->size_code === NULL OR $item->size_code === '' ? $item->color_code : $item->size_code;
      $sc 	.= $i%2 == 0 ? '<tr>' : '';
      $active	= $item->active == 0 ? 'Disactive' : ( $item->can_sell == 0 ? 'Not for sell' : ( $item->is_deleted == 1 ? 'Deleted' : TRUE ) );
      $stock	= $isVisual === FALSE ? ( $active == TRUE ? $this->showStock( $this->stock_model->get_stock($item->code) )  : 0 ) : 0; //---- สต็อกทั้งหมดทุกคลัง
			$qty 		= $isVisual === FALSE ? ( $active == TRUE ? $this->showStock( $this->stock_model->get_sell_stock($item->code) ) : 0 ) : FALSE; //--- สต็อกที่สั่งซื้อได้
			$disabled  = $isVisual === TRUE  && $active == TRUE ? '' : ( ($active !== TRUE OR $qty < 1 ) ? 'disabled' : '');

      if( $qty < 1 && $active === TRUE )
			{
				$txt = '<p class="pull-right red">Sold out</p>';
			}
			else if( $qty > 0 && $active === TRUE )
			{
				$txt = '<p class="pull-right green">'. $qty .'  in stock</p>';
			}
			else
			{
				$txt = $active === TRUE ? '' : '<p class="pull-right blue">'.$active.'</p>';
			}

      $limit		= $qty === FALSE ? 1000000 : $qty;
      $code = $attr == 'color' ? $item->color_code : $item->size_code;

			$sc 	.= '<td class="middle" style="border-right:0px;">';
			$sc 	.= '<strong>' .	$code.' ('.$data[$code].')' . '</strong>';
			$sc 	.= '</td>';

			$sc 	.= '<td class="middle" class="one-attribute">';
			$sc 	.= $isVisual === FALSE ? '<center><span class="font-size-10 blue">('.($stock < 0 ? 0 : $stock).')</span></center>':'';

      if( $view === FALSE )
			{
			$sc 	.= '<input type="number" class="form-control input-sm order-grid display-block" name="qty[0]['.$item->code.']" id="qty_'.$item->code.'" onkeyup="valid_qty($(this), '.($qty === FALSE ? 1000000 : $qty).')" '.$disabled.' />';
			}

      $sc 	.= 	'<center>';
      $sc   .= '<span class="font-size-10">';
      $sc   .= $qty === FALSE && $active === TRUE ? '' : ( ($qty < 1 || $active !== TRUE ) ? $txt : $qty);
      $sc   .= '</span></center>';
			$sc 	.= '</td>';

			$i++;

			$sc 	.= $i%2 == 0 ? '</tr>' : '';

    }


		$sc	.= "</table>";

		return $sc;
	}





  public function orderGridTwoAttribute($style, $isVisual, $view)
	{

		$colors	= $this->getAllColors($style->code);
		$sizes 	= $this->getAllSizes($style->code);
		$sc 		= '';
		$sc 		.= '<table class="table table-bordered">';
		$sc 		.= $this->gridHeader($colors);

		foreach( $sizes as $size_code => $size )
		{
			$sc 	.= '<tr style="font-size:12px;">';
			$sc 	.= '<td class="text-center middle" style="width:70px;"><strong>'.$size_code.'</strong></td>';

			foreach( $colors as $color_code => $color )
			{
        $item = $this->products_model->get_item_by_color_and_size($style->code, $color_code, $size_code);

				if( !empty($item) )
				{
					$active	= $item->active == 0 ? 'Disactive' : ( $item->can_sell == 0 ? 'Not for sell' : ( $item->is_deleted == 1 ? 'Deleted' : TRUE ) );
					$stock	= $isVisual === FALSE ? ( $active == TRUE ? $this->showStock( $this->stock_model->get_stock($item->code) )  : 0 ) : 0; //---- สต็อกทั้งหมดทุกคลัง
					$qty 		= $isVisual === FALSE ? ( $active == TRUE ? $this->showStock( $this->get_sell_stock($item->code) ) : 0 ) : FALSE; //--- สต็อกที่สั่งซื้อได้
					$disabled  = $isVisual === TRUE  && $active == TRUE ? '' : ( ($active !== TRUE OR $qty < 1 ) ? 'disabled' : '');
					if( $qty < 1 && $active === TRUE )
					{
						$txt = '<span class="font-size-12 red">Sold out</span>';
					}
					else
					{
						$txt = $active === TRUE ? '' : '<span class="font-size-12 blue">'.$active.'</span>';
					}

					$available = $qty === FALSE && $active === TRUE ? '' : ( ($qty < 1 || $active !== TRUE ) ? $txt : $qty);
					$limit		= $qty === FALSE ? 1000000 : $qty;


					$sc 	.= '<td class="order-grid">';
					$sc 	.= $isVisual === FALSE ? '<center><span class="font-size-10 blue">('.$stock.')</span></center>' : '';
					if( $view === FALSE )
					{
						$sc 	.= '<input type="number" min="1" max="'.$limit.'" class="form-control order-grid" name="qty['.$item->color_code.']['.$item->code.']" id="qty_'.$item->code.'" onkeyup="valid_qty($(this), '.$limit.')" '.$disabled.' />';
					}
					$sc 	.= $isVisual === FALSE ? '<center>'.$available.'</center>' : '';
					$sc 	.= '</td>';
				}
				else
				{
					$sc .= '<td class="order-grid">Not Available</td>';
				}
			} //--- End foreach $colors

			$sc .= '</tr>';
		} //--- end foreach $sizes
	$sc .= '</table>';
	return $sc;
	}







  public function getAttribute($style_code)
  {
    $sc = array();
    $color = $this->products_model->count_color($style_code);
    $size  = $this->products_model->count_size($style_code);
    if( $color > 0 )
    {
      $sc[] = "color";
    }

    if( $size > 0 )
    {
      $sc[] = "size";
    }
    return $sc;
  }





  public function gridHeader(array $colors)
  {
    $sc = '<tr class="font-size-12"><td>&nbsp;</td>';
    foreach( $colors as $code => $name )
    {
      $sc .= '<td class="text-center middle"><strong>'.$code . '<br/>'. $name.'</strong></td>';
    }
    $sc .= '</tr>';
    return $sc;
  }





  public function getAllColors($style_code)
	{
		$sc = array();
    $colors = $this->products_model->get_all_colors($style_code);
    if($colors !== FALSE)
    {
      foreach($colors as $color)
      {
        $sc[$color->code] = $color->name;
      }
    }

    return $sc;
	}




  public function getAllSizes($style_code)
	{
		$sc = array();
		$sizes = $this->products_model->get_all_sizes($style_code);
		if( $sizes !== FALSE )
		{
      foreach($sizes as $size)
      {
        $sc[$size->code] = $size->name;
      }
		}
		return $sc;
	}



  public function getOrderTableWidth($style_code)
  {
    $sc = 800; //--- ชั้นต่ำ
    $tdWidth = 70;  //----- แต่ละช่อง
    $padding = 100; //----- สำหรับช่องแสดงไซส์
    $color = $this->products_model->count_color($style_code);
    if($color > 0)
    {
      $sc = $color * $tdWidth + $padding;
    }

    return $sc;
  }



  public function get_new_code($date)
  {
    $date = $date == '' ? date('Y-m-d') : $date;
    $Y = date('y', strtotime($date));
    $M = date('m', strtotime($date));
    $prefix = getConfig('PREFIX_ORDER');
    $run_digit = getConfig('RUN_DIGIT_ORDER');
    $pre = $prefix .'-'.$Y.$M;
    $code = $this->orders_model->get_max_code($pre);
    if(! is_null($code))
    {
      $run_no = mb_substr($code, ($run_digit*-1), NULL, 'UTF-8') + 1;
      $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', $run_no);
    }
    else
    {
      $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', '001');
    }

    return $new_code;
  }



  public function get_sell_stock($item_code)
  {
    $sell_stock = $this->stock_model->get_sell_stock($item_code);
    $reserv_stock = $this->orders_model->get_reserv_stock($item_code);
    $availableStock = $sell_stock - $reserv_stock;
		return $availableStock < 0 ? 0 : $availableStock;
  }




  public function get_detail_table($order_code)
  {
    $sc = "no data found";
    $order = $this->orders_model->get_order($order_code);
    $details = $this->orders_model->get_order_details($order_code);
    if($details != FALSE )
    {
      $no = 1;
      $total_qty = 0;
      $total_discount = 0;
      $total_amount = 0;
      $total_order = 0;
      $ds = array();
      foreach($details as $rs)
      {
        $arr = array(
                "id"		=> $rs->id,
                "no"	=> $no,
                "imageLink"	=> get_product_image($rs->product_code, 'mini'),
                "productCode"	=> $rs->product_code,
                "productName"	=> $rs->product_name,
                "cost"				=> $rs->cost,
                "price"	=> number_format($rs->price, 2),
                "qty"	=> number_format($rs->qty),
                "discount"	=> ($order->role == 'C' ? $rs->gp .' %' : discountLabel($rs->discount1, $rs->discount2, $rs->discount3)),
                "amount"	=> number_format($rs->total_amount, 2)
                );
        array_push($ds, $arr);
        $total_qty += $rs->qty;
        $total_discount += $rs->discount_amount;
        $total_amount += $rs->total_amount;
        $total_order += $rs->qty * $rs->price;
        $no++;
      }

      $netAmount = ( $total_amount - $order->bDiscAmount ) + $order->shipping_fee + $order->service_fee;

      $arr = array(
            "total_qty" => number($total_qty),
            "order_amount" => number($total_order, 2),
            "total_discount" => number($total_discount, 2),
            "shipping_fee"	=> number($order->shipping_fee,2),
            "service_fee"	=> number($order->service_fee, 2),
            "total_amount" => number($total_amount, 2),
            "net_amount"	=> number($netAmount,2)
          );
      array_push($ds, $arr);
      $sc = json_encode($ds);
    }
    echo $sc;

  }

  public function clear_filter()
  {
    $filter = array(
      'code',
      'customer',
      'user',
      'reference',
      'shipCode',
      'channels',
      'payment',
      'fromDate',
      'toDate'
    );

    clear_filter($filter);
  }
}
?>
