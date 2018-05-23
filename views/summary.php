<div id="summary_container">
	<table id="summary">
		<thead>
			<tr>
				<th>IP</th>
				<th>Hostname</th>
				<th>In</th>
				<th>Out</th>
				<th>Total</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th colspan="2">Totals</th>
				<td><?php echo Format::decimal_size($this->data['totals']['bytes_in']); ?></td>
				<td><?php echo Format::decimal_size($this->data['totals']['bytes_out']); ?></td>
				<td><?php echo Format::decimal_size($this->data['totals']['bytes_total']); ?></td>
			</tr>
		</tfoot>
		<tbody>
<?php
foreach ($this->data['data'] as $ip=>$row)
{
    $row['bytes_total'] = (int) ($row['bytes_in'] ?? 0) + ($row['bytes_out'] ?? 0);
	echo '
			<tr data-in="', $row['bytes_in'] ?? 0, '" data-out="', $row['bytes_out'] ?? 0, '" data-total="', $row['bytes_total'], '">
				<td><a href="day_host.php?date=', date('Y-m-d', $this->date), '&ip=', urlencode($ip) , '">', $ip, '</a></td>
				<td><a href="', date('Y-m-d', $this->date), '/', $ip , '/">', gethostbyaddr($ip), '</a></td>
				<td>', Format::decimal_size($row['bytes_in'] ?? 0), '</td>
				<td>', Format::decimal_size($row['bytes_out'] ??0), '</td>
				<td>', Format::decimal_size($row['bytes_total'] ?? 0), '</td>
			</tr>';
}
?>

		</tbody>
	</table>

	<div id="pie"></div>
</div>