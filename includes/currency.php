<?php
function evntpg_currency_table_query (){
	global $wpdb;
	//$custom_table_prefix = get_custom_table_prefix();
	$currency_rst = "Select * from ".$wpdb->prefix."evntgen_currency_list ORDER BY country ASC";
	$currency_table_rst = $wpdb->get_results($currency_rst);
	$currency_data = $currency_table_rst;
	return $currency_data;
}