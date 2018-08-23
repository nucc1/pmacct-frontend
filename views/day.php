<?php
$this->page_id = 'summary-day';
?>

<div class="page-header">
    <h1>Statistics for <?php echo date('Y-m-d', $this->date); ?></h1>
</div>
<div id="summary_container">

    <table id="day-summary" class="datatable">
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
            <td><?php echo $this->data['totals']['bytes_in']; ?></td>
            <td><?php echo $this->data['totals']['bytes_out']; ?></td>
            <td><?php echo $this->data['totals']['bytes_total']; ?></td>
        </tr>
        </tfoot>
        <tbody>
        <?php
        foreach ($this->data['data'] as $ip=>$row)
        {
            //var_dump($row);
            $b_in = $row['bytes_in'] ?? 0 ;
            $b_out = $row['bytes_out'] ?? 0;
            $b_t = $b_in + $b_out;
            echo '
			<tr data-in="', $b_in, '" data-out="', $b_out, '" data-total="', $b_t, '">
				<td><a href="day_host.php?date=', date('Y-m-d', mktime()), '&ip=', urlencode($ip) , '">', $ip, '</a></td>
				<td><a href="', date('Y-m-d', $this->date), '/', $ip , '/">', gethostbyaddr($ip), '</a></td>
				<td>', $b_in, '</td>
				<td>', $b_out, '</td>
				<td>', $b_t, '</td>
			</tr>';
        }
        ?>

        </tbody>
    </table>

    <div id="pie"></div>
</div>
<script>
    $(function() {
        var opts = {
            columns: [
                {data: 'IP'},
                {data: 'Hostname'},
                {
                    data: 'in',
                    render: function(data, type, row) {
                        if (type === "display") {
                            return formatBytes(data);
                        }else {
                            return data;
                        }

                    }
                },
                {
                    data: 'out',
                    render: function(data, type, row) {
                        if (type === "display") {
                            return formatBytes(data);
                        }else {
                            return data;
                        }

                    }
                },
                {
                    data: 'total',
                    render: function(data, type, row) {
                        if (type === "display") {
                            return formatBytes(data);
                        }else {
                            return data;
                        }

                    }
                }
            ],
            'footerCallback': function(row, data, start, end, display) {
                var api = this.api();
                var inTotal = api.column(2).footer().innerText;
                $(api.column(2).footer()).html(formatBytes(inTotal));

                var outTotal = api.column(3).footer().innerText;
                $(api.column(3).footer()).html(formatBytes(outTotal));

                var sumTotal = api.column(4).footer().innerText;
                $(api.column(4).footer()).html(formatBytes(sumTotal));
            }
        }
        $('#day-summary').DataTable(opts);
    })
</script>