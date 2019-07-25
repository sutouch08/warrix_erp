<?php
class Product_tab_model extends CI_Model
{

  public $id;
	public $name;
	public $id_parent;


  public function __construct($id='')
  {
    parent::__construct();

    if( $id != "" )
		{
			$qs = $this->db->where('id', $id)->get('product_tab');
      if($qs->num_rows() == 1)
      {
        $this->id = $qs->row()->id;
        $this->name = $qs->row()->name;
        $this->id_parent = $qs->row()->id_parent;
      }
		}
  }



	public function add(array $ds = array())
	{
		$sc = FALSE;
		if( !empty($ds) )
		{
			$fields	= "";
			$values	= "";
			$i			= 1;
			foreach( $ds as $field => $value )
			{
				$fields	.= $i == 1 ? $field : ", ".$field;
				$values	.= $i == 1 ? "'". $value ."'" : ", '". $value ."'";
				$i++;
			}

			$sc = $this->db->query("INSERT INTO product_tab (".$fields.") VALUES (".$values.")");
		}

		return $sc;
	}



	public function update($id, array $ds = array())
	{
		$sc = FALSE;
		if( !empty( $ds ) )
		{
			$set 	= "";
			$i		= 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field . " = '" . $value . "'" : ", ".$field . " = '" . $value . "'";
				$i++;
			}
			$sc = $this->db->query("UPDATE product_tab SET " . $set . " WHERE id = '".$id."'");
		}
		return $sc;
	}



	public function updateChild($id, $id_parent)
	{
		return $this->db->query("UPDATE product_tab SET id_parent = ".$id_parent." WHERE id_parent = ".$id);
	}



	public function delete($id)
	{
		return $this->db->query("DELETE FROM product_tab WHERE id = '".$id."'");
	}



	public function updateTabsProduct($style_code, array $ds = array())
	{
		if( !empty($ds))
		{
			$this->db->trans_start();
      $this->db->where('style_code', $style_code)->delete('product_tab_style');
      foreach( $ds as $id)
      {
        $this->db->insert('product_tab_style', array('style_code' => $style_code, 'id_tab' => $id));
      }
			$this->db->trans_complete();

      if($this->db->trans_status() === FALSE)
      {
        return FALSE;
      }
      else
      {
        return TRUE;
      }
		}

		return FALSE;
	}



  //
	// public function addTabsProduct($style_code, $id_tab)
	// {
	// 	return dbQuery("INSERT INTO product_tab_style (style_code, id_product_tab) VALUES ('".$style_code."', '".$id_tab."')");
	// }




	// public function dropTabsProduct($style_code)
	// {
	// 	return dbQuery("DELETE FROM product_tab_style WHERE style_code = '".$style_code."'");
	// }



	public function isExists($field, $val, $id='')
	{
		$sc = FALSE;
		if( $id != '' )
		{
			$qs = $this->db->query("SELECT id FROM product_tab WHERE ".$field." = '".$val."' AND id != ".$id);
		}
		else
		{
			$qs = $this->db->query("SELECT id FROM product_tab WHERE ".$field." = '".$val."'");
		}

		if( $qs->num_rows() > 0)
		{
			$sc = TRUE;
		}

		return $sc;
	}



	public function getName($id)
	{
		$sc = "TOP LEVEL";
		$qs = $this->db->select('name')->where('id', $id)->get('product_tab');
		if( $qs->num_rows() == 1 )
		{
			return $qs->row()->name;
		}

		return $sc;
	}



	public function getParentId($id)
	{
		$sc = 0;
		$qs = $this->db->select('id_parent')->where('id', $id)->get('product_tab');
		if( $qs->num_rows() == 1 )
		{
			return $qs->row()->id_parent;
		}

		return $sc;
	}


	public function getAllParent($id)
	{
		$sc = array();
		$id_parent = $this->getParentId($id);
		while( $id_parent > 0 )
		{
			$sc[$id_parent] = $id_parent;
			$id_parent = $this->getParentId($id_parent);
		}
		return $sc;
	}



  //-------- เอารายการใน product_tab_style มา
  public function getStyleTabsId($code)
  {
    $sc = array();
    $qs = $this->db->select('id_tab')->where('style_code', $code)->get('product_tab_style');
    if($qs->num_rows() > 0)
    {
      foreach($qs->result() as $rs)
      {
        $sc[$rs->id_tab] = $rs->id_tab;
      }
    }

    return $sc;
  }



	//-------- เอารายการใน product_tab_style มา
	public function getParentTabsId($style_code)
	{
		$sc = array();
		$ds = $this->getStyleTabsId($style_code);
		if( !empty( $ds ))
		{
			foreach( $ds as $id )
			{
				$id_tab = $this->getParentId($id);
				while( $id_tab > 0 )
				{
					$sc[$id_tab] = $id_tab;
					$id_tab = $this->getParentId($id_tab);
				}
			}
			return $sc;
		}

		$qs = $this->db->select('id_tab')->where('style_code', $style_code)->get('product_tab_style');

		if( $qs->num_rows() > 0 )
		{
      foreach($qs->result() as $rs)
      {
        $sc[$rs->id_tab] = $rs->$id_tab;
      }
		}

		return $sc;
	}





	public function getParentList($id = 0)
	{
		//----- Parent cannot be yoursalfe
		return $this->db->where('id !=', $id)->get('product_tab');
	}





	//-----------------  Search Result
	public function getSearchResult($txt)
	{
		return $this->db->like('name', $txt)->get('product_tab');

	}






	public function countMember($id)
	{
		$qs = $this->db->select('id_tab')->where('id_tab', $id)->get('product_tab_style');
		return $qs->num_rows();
	}





	public function getStyleInTab($id)
	{
		$qr = "SELECT t.style_code FROM product_tab_style AS t ";
		$qr .= "JOIN product_style AS p ON t.style_code = p.code ";
		$qr .= "WHERE p.active = 1 AND p.can_sell = 1 AND is_deleted = 0 ";
		$qr .= "AND id_tab = ".$id;

		return $this->db->query($qr);
	}


  public function get_style_in_tab($id)
  {
    $qr = "SELECT t.style_code FROM product_tab_style AS t ";
		$qr .= "JOIN product_style AS p ON t.style_code = p.id ";
		$qr .= "WHERE p.active = 1 AND p.can_sell = 1 AND is_deleted = 0 ";
		$qr .= "AND id_tab = ".$id;

    $rs = $this->db->query($qr);
    if($rs->num_rows() > p)
    {
      return $rs->result();
    }

    return array();
  }





	public function getStyleInSaleTab($id)
	{
		$qr = "SELECT t.style_code FROM product_tab_style AS t ";
		$qr .= "JOIN product_style AS p ON t.style_code = p.id ";
		$qr .= "WHERE p.active = 1 AND p.can_sell = 1 AND p.is_deleted = 0 AND p.show_in_sale = 1 ";
		$qr .= "AND id_tab = ".$id;

		return $this->db->query($qr);
	}




} //--- end class

?>
