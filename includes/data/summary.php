<?php
/**
 * Data model for retriving a summary of statistics
 * @author Daniel15 <daniel at dan.cx>
 */

require_once(dirname(dirname(__FILE__)) . '/ip.lib.php');
class Data_Summary
{
	/**
	 * Get the statistics for a certain day
	 * @param	date	Minimum date
	 * @return	Array of data
	 */
	public static function day($date)
	{
		// Calculate the last second of this day
		$end_date = mktime(23, 59, 59, date('m', $date), date('d', $date), date('Y', $date));
		
		return self::summary($date, $end_date);
	}
	
	/**
	 * Get the statistics for a certain month
	 * @param	date	Minimum date
	 * @return	Array of data
	 */
	public static function month($date)
	{
		// Calculate end of this month
		$end_date = mktime(23, 59, 59, date('m', $date) + 1, 0, date('Y', $date));
		
		return self::summary($date, $end_date);
	}
	
	/**
	 * Get a summary of host traffic data for a certain time period
	 * @param	date	Start of this time perioud
	 * @param	date	End of this time period
	 * @return	Array of data
	 */
	private static function summary($start_date, $end_date)
	{
        $block = IPBlock::create(Config::$localSubnet);
        $i = 0; //use this counter to avoid expanding more than 256 IP addresses.
        $addresses = array(); //hold list of IPs. we set hard limit of 256 addresses.
        foreach ($block as $ip){
            $addresses[] = (string) $ip;
            $i++;

            if ($i >=255){
                break;
            }
        }

        //format the list of IP addresses in our /24 subnet for use in mysql IN clause.
        $in = "(" . implode(",",$addresses) . ")";

        //make the table name in _mmYY format. for inbound table
        $table_in = "inbound_" . gmdate("mY", $start_date);

        var_dump($table_in);

        //get a database connection via PDO object
		$db = Database::getDB()->prepare('
			SELECT ip_dst, SUM(bytes) bytes_in, 
			FROM ' . $table_in . '
			WHERE stamp_inserted BETWEEN :start_date AND :end_date
			GROUP BY ip
			ORDER BY bytes_in DESC');
			
		$results = $db->execute(array(
			'start_date' => Database::date($start_date),
			'end_date' => Database::date($end_date),
			//'end_date' => Database::date(strtotime('midnight tomorrow - 1 second')),
		));
		
		$data = array();
		$totals = (object)array(
			'bytes_out' => 0,
			'bytes_in' => 0,
			'bytes_total' => 0,
		);
		
		foreach ($results as $row)
		{
		    var_dump($row);
		    

			// Check if this IP is on the list of IPs that should be shown
			if (!empty(Config::$include_ips) && !in_array($row->ip, Config::$include_ips))
				continue;
			
			$row->bytes_total = $row->bytes_in + $row->bytes_out;
			$data[] = $row;
			
			$totals->bytes_in += $row->bytes_in;
			$totals->bytes_out += $row->bytes_out;
			$totals->bytes_total += $row->bytes_total;
		}
		
		return (object)array(
			'data' => $data,
			'totals' => $totals
		);
	}
	
	/**
	 * Get the statistics for a certain month, grouped by day and host
	 * @param	date	Minimum date
	 * @return	Array of data
	 */
	public static function month_by_day($start_date)
	{
		// Calculate end of this month
		$end_date = mktime(23, 59, 59, date('m', $start_date) + 1, 0, date('Y', $start_date));
		
		$query = Database::getDB()->prepare('
			SELECT ip, UNIX_TIMESTAMP(date) AS date, SUM(bytes_out) bytes_out, SUM(bytes_in) bytes_in
			FROM ' . Config::$database['prefix'] . 'combined
			WHERE date BETWEEN :start_date AND :end_date
			GROUP BY ip, DAY(date)
			ORDER BY date, ip');
			
		$query->execute(array(
			'start_date' => Database::date($start_date),
			'end_date' => Database::date($end_date),
		));
		
		// Start with an empty array for all the days of the month
		$day_base = date('Y-m-', $start_date);
		$days = array();
		for ($i = 1, $count = date('t', $start_date); $i <= $count; $i++)
		{
			$days[$day_base . str_pad($i, 2, '0', STR_PAD_LEFT)] = 0;
		}

		$data = array();
		while ($row = $query->fetchObject())
		{
			// Check if this IP is on the list of IPs that should be shown
			if (!empty(Config::$include_ips) && !in_array($row->ip, Config::$include_ips))
				continue;
				
			// Does this host have a data entry yet?
			if (!isset($data[$row->ip]))
				$data[$row->ip] = $days;
			
			$row->bytes_total = $row->bytes_in + $row->bytes_out;
			$data[$row->ip][date('Y-m-d', $row->date)] =  $row->bytes_total;
		}
		
		return $data;
	}
}
?>