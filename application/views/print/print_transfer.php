<?php

  $this->load->helper('print');
  $page = '';
  $page .= $this->printer->doc_header();
	$this->printer->add_title("ใบโอนสินค้า (ระหว่างคลัง)");
	$header	= array(
    'เลขที่' => $doc->code,
    'วันที่'  => thai_date($doc->date_add, FALSE, '/'),
    'ต้นทาง' => $doc->from_warehouse_name,
    'ปลายทาง' => $doc->to_warehouse_name,
    'พนักงาน' => $this->user_model->get_name($doc->user)
	);

  if($doc->remark != '')
  {
    $header['หมายเหตุ'] = $doc->remark;
  }

	$this->printer->add_header($header);

	$total_row 	= empty($details) ? 0 : count($details);
	$config = array(
    'total_row' => $total_row,
    'font_size' => 10,
    'sub_total_row' => 1
  );

	$this->printer->config($config);

	$row 	= $this->printer->row;
	$total_page = $this->printer->total_page;
	$total_qty 	= 0;
	//**************  กำหนดหัวตาราง  ******************************//
  //------- กำหนดส่วนหัวตารางรายการที่จะพิมพ์
	$thead	= array(
						array("ลำดับ", "width:5%; text-align:center; border-top:0px; border-top-left-radius:10px;"),
						array("รหัส", "width:20%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("สินค้า", "width:25%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("ต้นทาง","width:20%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("ปลายทาง", "width:20%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("จำนวน", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px; border-top-right-radius:10px")
						);

	$this->printer->add_subheader($thead);

	//***************************** กำหนด css ของ td *****************************//
  //---	กำหนดการแสดงผลให้กับช่องแต่ละช่องในแต่ละบรรทัด
  $pattern = array(
              "text-align: center; border-top:0px;",
              "border-left: solid 1px #ccc; border-top:0px;",
              "border-left: solid 1px #ccc; border-top:0px;",
              "text-align:center; border-left: solid 1px #ccc; border-top:0px;",
              "text-align:center; border-left: solid 1px #ccc; border-top:0px;",
              "text-align:center; border-left: solid 1px #ccc; border-top:0px;",
              "text-align:right; border-left: solid 1px #ccc; border-top:0px;"
              );
	$this->printer->set_pattern($pattern);

	//*******************************  กำหนดช่องเซ็นของ footer *******************************//
	$footer	= array(
						array("ผู้จัดทำ", "", "วันที่ ............................."),
						array("ผู้ตรวจสอบ", "","วันที่ ............................."),
						array("ผู้อนุมัติ", "","วันที่ .............................")
						);

  $this->printer->set_footer($footer);

	$n = 1;

	while($total_page > 0 )
	{
		$page .= $this->printer->page_start();
			$page .= $this->printer->top_page();
			$page .= $this->printer->content_start();
				$page .= $this->printer->table_start();
				if($doc->status == 2)
				{
					$page .= '
				  <div style="width:0px; height:0px; position:relative; left:30%; line-height:0px; top:300px;color:red; text-align:center; z-index:100000; opacity:0.1; transform:rotate(-45deg)">
				      <span style="font-size:150px; border-color:red; border:solid 10px; border-radius:20px; padding:0 20 0 20;">ยกเลิก</span>
				  </div>';
				}

				$i = 0;

				while($i < $row)
        {
					$rs = isset($details[$i]) ? $details[$i] : array();
					if(!empty($rs))
          {
            $data = array(
              $n,
							inputRow($rs->product_code),
							inputRow($rs->product_name),
              inputRow($rs->from_zone),
              inputRow($rs->to_zone),
							number($rs->qty)
						);
            $total_qty += $rs->qty;
          }
          else
          {
            $data = array("", "", "", "", "", "");
          }
					$page .= $this->printer->print_row($data);
					$n++; $i++;
				}

				$page .= $this->printer->table_end();

				if($this->printer->current_page == $this->printer->total_page)
				{
					$qty = number($total_qty);
					$remark = $doc->remark;
				}else{
					$qty = "";
					$remark = "";
				}

				$sub_total = array(
          array(
          "<td style='height:".$this->printer->row_height."mm; border: solid 1px #ccc;
          border-bottom:0px; border-left:0px; text-align:right;
          width:85.1%;'>
          <strong>รวม</strong>
          </td>
          <td style='height:".$this->printer->row_height."mm; border: solid 1px #ccc;
          border-right:0px; border-bottom:0px; border-bottom-right-radius:10px;
          text-align:right;'>".number($total_qty)."</td>")

			);

			$page .= $this->printer->print_sub_total($sub_total);
			$page .= $this->printer->content_end();
			$page .= $this->printer->footer;
		  $page .= $this->printer->page_end();
		  $total_page --;
      $this->printer->current_page++;
	}

	$page .= $this->printer->doc_footer();

  echo $page;
?>
