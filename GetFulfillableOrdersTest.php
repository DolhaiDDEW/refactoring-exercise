<?php 

require_once('vendor/autoload.php');
require_once('GetFulfillableOrdersClass.php');

use PHPUnit\Framework\TestCase;

final class GetFulfillableOrdersTest extends TestCase
{
	protected $fulfillable_orders;
	
	public function setUp(): void
	{
		$this->fulfillable_orders = new GetFulfillableOrders();
	}
	
	/**
	* @test
	*/
	public function processStockATest(): void
	{
		$stock = '{"1":8,"2":4,"3":5}';
		$csv_file = 'C:\xampp\htdocs\refactor_exercise\orders.csv';
		
		$result = $this->fulfillable_orders->process($stock, $csv_file);
		
		$this->assertFalse($result['error']);
		$this->assertIsString($result['result_text']);
	}
	
	/**
	* @test
	*/
	public function processStockBTest(): void
	{
		$stock = '{"1":2,"2":3,"3":1}';
		$csv_file = 'C:\xampp\htdocs\refactor_exercise\orders.csv';
		
		$result = $this->fulfillable_orders->process($stock, $csv_file);
		
		$this->assertFalse($result['error']);
		$this->assertIsString($result['result_text']);
	}
}