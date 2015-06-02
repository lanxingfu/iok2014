<?php 
/**************
 * @Model:CartModel.class.php
 * @Description:Shopping cart logic processing
 * @Author:lanxingfu
 * @Createtime:2013/12/03
 * 
 * */
class CartModel {
	public $product_id = array();
	public $product_title = array();
	public $price  = array();
	public $inventory = array();
	public $moq = array();
	public $thumb = array();
	public $quantity = array();  //购买数量
	public $buy_order = 0; //购买顺序
	public function addProducts($productid,$quantity,$userid,$title,$price,$inventory,$moq,$thumb='') {
		if(!in_array($productid , $this->product_id)) {
			$this->product_id[$this->buy_order]       = $productid;
			$this->product_title[$this->buy_order]       = $title;
			$this->price[$this->buy_order]     = $price;
			$this->inventory[$this->buy_order]   = $inventory;
			$this->moq[$this->buy_order]     = $moq;
			$this->thumb[$this->buy_order]      = $thumb;
			$this->quantity[$this->buy_order]    = $quantity;
			$this->buy_order ++;  
		} else {
			$arr_temp = array_flip($this->product_id);
			//获取本件商品的购买顺序。目的是为了增加该商品的购买数量。
			$product_buy_order = $arr_temp[$productid];
			$this->quantity[$product_buy_order] += 1;
		}
	}      
	/*/遍历购物车
	//返回值：数组（包含四个数组元素。）
	*/
	public function foreachCart() {
		$cart_product_data = array();//购物车中的商品信息
		$cart_data = array();      //除商品信息外，还包含商品总价等的多种信息
		$product_price_total = 0;   //商品价格合计
		$product_type_count = 0;     //商品种类数量
		for($i=0; $i<$this->buy_order; $i++) {
			if($this->product_id[$i] != '') {
				$cart_product_data[$i]['id'] = $this->product_id[$i];
				$cart_product_data[$i]['title'] = $this->product_title[$i];
				$cart_product_data[$i]['price'] = $this->price[$i];
				$cart_product_data[$i]['inventory'] = $this->inventory[$i];
				$cart_product_data[$i]['moq']     = $this->moq[$i];
				$cart_product_data[$i]['thumb'] = $this->thumb[$i];
				$cart_product_data[$i]['quantity'] = $this->quantity[$i];
				$product_price_total += $this->quantity[$i]*$this->price[$i];
				$product_type_count++;
			}
		}
		$cart_data['cart_product_data'] = $cart_product_data;
		$cart_data['product_price_total'] = number_format($product_price_total, 2, '.', '');
		$cart_data['product_type_count'] = $product_type_count;
		return $cart_data;
	}
	
	/**
	 * 删除购物车中商品
	 * */
	function deleteProducts($id) {
		//删除指定的ID 位置
		unset($this->product_id[array_search($id , $this->product_id)]);
		unset($this->product_title[array_search($id , $this->product_id)]);
		unset($this->price[array_search($id , $this->product_id)]);
		unset($this->inventory[array_search($id , $this->product_id)]);
		unset($this->moq[array_search($id , $this->product_id)]);
		unset($this->thumb[array_search($id , $this->product_id)]);
		unset($this->quantity[array_search($id , $this->product_id)]);
		unset($this->note[array_search($id , $this->product_id)]);
	}
	
	//更新购物车
	function updateCart($id,$quantity) {
		if( is_numeric($quantity) ) {
			if($quantity==0) {
				$this->deleteProducts($id);
			} else {
				$this->quantity[array_search($id , $this->product_id)] = $quantity;
			}
		} else {
			$this->quantity[array_search($id , $this->product_id)] = $this->moq[array_search($id , $this->product_id)];
		}
	}
	
	
	
}




?> 