<?php

/**
 * Get fulfillable orders
 *
 * The class read and sorts orders by date, priority and then compares stock to fulfillment.
 *
 */ 
class GetFulfillableOrders
{
	/**
	 * 
	 * Run get fulfillable orders main instance.
	 *
	 * @param string $stock json format
	 * @param string $orders_file filename
	 *
	 * @return array
	 */
	public function process($stock, $orders_file)
	{
		$stock = json_decode($stock);
		
		if($stock == null){
			return [
				'error' => true, 
				'message' => 'Invalid json!'
			];
		}
		
		$orders = $this->getOrders($orders_file);
		
		if($orders['error']){
			return $orders;
		}
		
		$orders_col = $orders['orders_columns'];
		
		$orders = $this->sortOrders($orders['orders']);
		
		$result_text = '';
		
		foreach ($orders_col as $col) {
			$result_text .= str_pad($col, 20);
		}
		
		$result_text .= "\n";
		
		foreach ($orders_col as $col) {
			$result_text .= str_repeat('=', 20);
		}
		
		$result_text .= "\n";
		
		foreach ($orders as $item) {
			if($stock->{$item['product_id']} >= $item['quantity']){
				foreach($orders_col as $col){
					if ($col == 'priority'){
						
						if($item['priority'] == 1){
							$text = 'low';
						}else{
							if($item['priority'] == 2){
								$text = 'medium';
							}else{
								$text = 'high';
							}
						}
						
						$result_text .= str_pad($text, 20);
						
					}else{
						$result_text .= str_pad($item[$col], 20);
					}
				}
				
				$result_text .= "\n";
			}
		}
		
		return[
			'error' => false, 
			'result_text' => $result_text
		];
	}
	
	/**
	 * 
	 * Load orders from csv file.
	 *
	 * @param string $orders_file filename
	 *
	 * @return array
	 */
	private function getOrders($filename)
	{
		$orders = [];
		$orders_col = [];
		$row = 1;
		
		if(($handle = fopen($filename, 'r')) === false){
			return [
				'error' => true,
				'message' => 'Error opening file!'
			];
		}
		
		while(($data = fgetcsv($handle)) !== false){
			if($row == 1) {
				$orders_col = $data;
			}else{
				$o = [];
				
				for ($i = 0; $i < count($orders_col); $i++) {
					$o[$orders_col[$i]] = $data[$i];
				}
				
				$orders[] = $o;
			}
			
			$row++;
		}
		
		fclose($handle);
		
		
		return [
			'error' => false,
			'orders_columns' => $orders_col, 
			'orders' => $orders
		];
	}
	
	/**
	 * 
	 * Sort orders by created_at and priority.
	 *
	 * @param array $orders list of orders
	 *
	 * @return array
	 */
	private function sortOrders($orders)
	{
		usort($orders, function ($a, $b) {
			$pc = -1 * ($a['priority'] <=> $b['priority']);
			return $pc == 0 ? $a['created_at'] <=> $b['created_at'] : $pc;
		});
		
		return $orders;
	}
}