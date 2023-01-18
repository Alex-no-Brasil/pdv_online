<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports_model extends CI_Model
{


	public function __construct()
	{
		parent::__construct();

	}

	public function getAllProducts()
	{
		$q = $this->db->get('products');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}

			return $data;
		}
	}

	public function getAllCustomers()
	{
		$q = $this->db->get('customers');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	public function topProducts()
	{
		$m = date('Y-m');
		$this->db->select($this->db->dbprefix('products').".code as product_code, ".$this->db->dbprefix('products').".name as product_name, sum(".$this->db->dbprefix('sale_items').".quantity) as quantity")
		->join('products', 'products.id=sale_items.product_id', 'left')
		->join('sales', 'sales.id=sale_items.sale_id', 'left')
		->order_by("sum(".$this->db->dbprefix('sale_items').".quantity)", 'desc')
		->group_by('sale_items.product_id')
		->limit(10)
		->like('sales.date', $m, 'both');
		$q = $this->db->get('sale_items');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	public function topProducts1()
	{
		$m = date('Y-m', strtotime('first day of last month'));
		$this->db->select($this->db->dbprefix('products').".code as product_code, ".$this->db->dbprefix('products').".name as product_name, sum(".$this->db->dbprefix('sale_items').".quantity) as quantity")
		->join('products', 'products.id=sale_items.product_id', 'left')
		->join('sales', 'sales.id=sale_items.sale_id', 'left')
		->order_by("sum(".$this->db->dbprefix('sale_items').".quantity)", 'desc')
		->group_by('sale_items.product_id')
		->limit(10)
		->like('sales.date', $m, 'both');
		$q = $this->db->get('sale_items');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	public function topProducts3()
	{
		$this->db->select($this->db->dbprefix('products').".code as product_code, ".$this->db->dbprefix('products').".name as product_name, sum(".$this->db->dbprefix('sale_items').".quantity) as quantity")
		->join('products', 'products.id=sale_items.product_id', 'left')
		->join('sales', 'sales.id=sale_items.sale_id', 'left')
		->order_by("sum(".$this->db->dbprefix('sale_items').".quantity)", 'desc')
		->group_by('sale_items.product_id')
		->limit(10)
		->where($this->db->dbprefix('sales').'.date >= last_day(now()) + interval 1 day - interval 3 month', NULL, FALSE);
		$q = $this->db->get('sale_items');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	public function topProducts12()
	{
		$this->db->select($this->db->dbprefix('products').".code as product_code, ".$this->db->dbprefix('products').".name as product_name, sum(".$this->db->dbprefix('sale_items').".quantity) as quantity")
		->join('products', 'products.id=sale_items.product_id', 'left')
		->join('sales', 'sales.id=sale_items.sale_id', 'left')
		->order_by("sum(".$this->db->dbprefix('sale_items').".quantity)", 'desc')
		->group_by('sale_items.product_id')
		->limit(10)
		->where($this->db->dbprefix('sales').'.date >= last_day(now()) + interval 1 day - interval 12 month', NULL, FALSE);
		$q = $this->db->get('sale_items');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}

			return $data;
		}
	}

	public function getDailySales($year, $month)
	{

		$myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, COALESCE(sum(total), 0) as total, COALESCE(sum(grand_total), 0) as grand_total,
		COALESCE(sum(total_tax), 0) as tax, COALESCE(sum(total_discount), 0) as discount FROM (".$this->db->dbprefix('sales').")
		WHERE DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
		GROUP BY DATE_FORMAT( date,  '%e' )";
		$q = $this->db->query($myQuery, false);
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}


	public function getMonthlySales($year)
	{

		$myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, COALESCE(sum(total), 0) as total, COALESCE(sum(grand_total), 0) as grand_total,
		COALESCE(sum(total_tax), 0) as tax, COALESCE(sum(total_discount), 0) as discount
		FROM (".$this->db->dbprefix('sales').")
		WHERE DATE_FORMAT( date,  '%Y' ) =  '{$year}'
		GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
		$q = $this->db->query($myQuery, false);
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}

	public function getTotalSalesforCustomer($customer_id, $user = NULL, $start_date = NULL, $end_date = NULL)
	{
		if($start_date && $end_date) {
			$this->db->where('date >=', $start_date);
			$this->db->where('date <=', $end_date);
		}
		if($user) {
			$this->db->where('created_by', $user);
		}
		 $q=$this->db->get_where('sales', array('customer_id' => $customer_id));
		 return $q->num_rows();

	}

	public function getTotalSalesValueforCustomer($customer_id, $user = NULL, $start_date = NULL, $end_date = NULL)
	{
		$this->db->select('sum(total) as total');
		if($start_date && $end_date) {
			$this->db->where('date >=', $start_date);
			$this->db->where('date <=', $end_date);
		}
		if($user) {
			$this->db->where('created_by', $user);
		}
		 $q=$this->db->get_where('sales', array('customer_id' => $customer_id));
		 if( $q->num_rows() > 0 )
		  {
			$s = $q->row();
			return $s->total;
		  }
		return FALSE;
	}

	public function getAllStaff()
    {

        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

	public function getTotalSales($start, $end)
    {
        $this->db->select('count(id) as total, sum(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid, SUM(COALESCE(total_tax, 0)) as tax', FALSE)
            ->where("date >= '{$start}' and date <= '{$end}'", NULL, FALSE);
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalPurchases($start, $end)
    {
        $this->db->select('count(id) as total, sum(COALESCE(total, 0)) as total_amount', FALSE)
            ->where("date >= '{$start}' and date <= '{$end}'", NULL, FALSE);
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalExpenses($start, $end)
    {
        $this->db->select('count(id) as total, sum(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where("date >= '{$start}' and date <= '{$end}'", NULL, FALSE);
        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }


    public function dailySales($mes) {
        
        $date_start = date("$mes-01 00:00:00");
        
        $date_end = date("$mes-31 23:59:59");

        $sql = "SELECT "
                . "DATE_FORMAT(date, '%Y-%m-%d') AS dia, "
                . "cod_loja, SUM(total_quantity) as pecas, "
                . "COUNT(1) as vendas "
                . "FROM tec_sales "
                . "WHERE date > '$date_start' "
                . "AND date < '$date_end' "
                . "AND status = 'paid'"
                . "GROUP BY dia, cod_loja";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function monthlySales() {
        $date = date("Y-01-01 00:00:00");

        $sql = "SELECT "
                . "DATE_FORMAT(date, '%Y-%m') AS month, "
                . "cod_loja, SUM(total_quantity) as pecas, "
                . "COUNT(1) as vendas "
                . "FROM tec_sales "
                . "WHERE date > '$date' "
                . "AND status = 'paid'"
                . "GROUP BY month, cod_loja";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function dailyCustomers($mes) {
        $date_start = date("$mes-01 00:00:00");
        
        $date_end = date("$mes-31 23:59:59");

        $sql = "SELECT "
                . "DATE_FORMAT(createdAt, '%Y-%m-%d') AS dia, "
                . "cod_loja, COUNT(1) as cad "
                . "FROM tec_customers "
                . "WHERE createdAt > '$date_start' "
                . "AND createdAt < '$date_end' "
                . "AND phone !='' "
                . "GROUP BY dia, cod_loja";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function monthlyCustomers() {
        $date = date("Y-01-01 00:00:00");

        $sql = "SELECT "
                . "DATE_FORMAT(createdAt, '%Y-%m') AS month, "
                . "cod_loja, COUNT(1) as cad "
                . "FROM tec_customers "
                . "WHERE createdAt > '$date' "
                . "AND phone !='' "
                . "GROUP BY month, cod_loja";

        $query = $this->db->query($sql);

        return $query->result();
    }
    
    public function productsPerformance($item, $date_start, $date_end) {

        $sql = "SELECT p.$item AS label, "
                . "SUM(i.quantity) as pecas, "
                . "SUM(i.subtotal) as valor "
                . "FROM tec_sales s, tec_sale_items i, tec_products p "
                . "WHERE s.date >= '$date_start 00:00:00' "
                . "AND s.date <= '$date_end 23:59:59' "
                . "AND s.status = 'paid' "
                . "AND i.sale_id = s.id "
                . "AND p.id = i.product_id "
                . "GROUP BY p.$item";

        $query = $this->db->query($sql);

        return $query->result();
    }
    
    public function lojaPerformanceVendas($date_start, $date_end) {

        $sql = "SELECT l.cod AS label, COUNT(1) as vendas, "
                . "SUM(s.grand_total) as valor "
                . "FROM tec_sales s, tec_lojas l "
                . "WHERE s.date > '$date_start 00:00:00' "
                . "AND s.date < '$date_end 23:59:59' "
                . "AND s.status = 'paid' "
                . "AND s.cod_loja = l.cod "
                . "GROUP BY label";

        $query = $this->db->query($sql);

        return $query->result();
    }
    
    public function lojaPerformancePecas($date_start, $date_end) {

        $sql = "SELECT l.cod AS label, SUM(i.quantity) AS pecas "
                . "FROM tec_sales s, tec_sale_items i, tec_lojas l "
                . "WHERE s.date > '$date_start 00:00:00' "
                . "AND s.date < '$date_end 23:59:59' "
                . "AND s.status = 'paid' "
                . "AND i.sale_id = s.id "
                . "AND s.cod_loja = l.cod "
                . "GROUP BY label";

        $query = $this->db->query($sql);

        return $query->result();
    }
    
    public function sellersPerformance($date_start, $date_end) {

        $sql = "SELECT v.name AS label, COUNT(DISTINCT s.id) as vendas, "
                . "SUM(i.quantity) as pecas, "
                . "SUM(i.subtotal) as valor "
                . "FROM tec_sales s, tec_sale_items i, tec_sellers v "
                . "WHERE s.date >= '$date_start 00:00:00' "
                . "AND s.date <= '$date_end 23:59:59' "
                . "AND s.status = 'paid' "
                . "AND i.sale_id = s.id "
                . "AND s.seller_id = v.id "
                . "GROUP BY label";

        $query = $this->db->query($sql);

        return $query->result();
    }
       
    public function conferencia_produto($cod_loja, $categoria_id, $valor, $like) {

        $sql = "SELECT prod.code, prod.name, prod.price, rel.quantity as estoque "
                . "FROM tec_products prod, tec_products_estoque_lojas rel "
                . "WHERE prod.category_id = $categoria_id ";
        
        if (!empty($valor)) {
            $sql .= "AND prod.price = $valor ";
        }
        
        if (!empty($like)) {
            $sql .= "AND prod.name LIKE '$like' ";
        }
        
        $sql .= "AND rel.code = prod.code AND rel.cod_loja = '$cod_loja' AND rel.quantity > 0 "
                . "ORDER BY prod.code ASC";

        $query = $this->db->query($sql);

        return $query->result();
        
    }

}
