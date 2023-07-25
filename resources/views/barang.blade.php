<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ $title }}</title>
	<link href="{{ asset('bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
<table width="100%">
	<tr><th style="text-align: center;">{{ $title }}</th></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td align="center">
		<table>
			<tr><td align="center"><input class="form-control" id="nama" type="text" placeholder="Nama Barang" required></td></tr>
			<tr><td align="center"><input class="form-control" id="qty" type="number" placeholder="QTY" required></td></tr>
			<tr><td align="center"><input class="form-control" id="harga" type="number" placeholder="Harga" required></td></tr>
			<tr><td align="center">
				<button class="btn btn-info" onclick="clearForm()">Reload</button>
				<button class="btn btn-success" onclick="saveForm()">Save</button>
			</td></tr>
		</table>
	</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td align="center">
		<table id="data" class="table table-bordered" width="100%">
			<thead>
				<tr>
					<th scope="col" style="text-align: center;">No</th>
					<th scope="col" style="text-align: center;">Kode Barang</th>
					<th scope="col" style="text-align: center;">Nama Barang</th>
					<th scope="col" style="text-align: center;">Quantity</th>
					<th scope="col" style="text-align: center;">Harga Barang</th>
					<th scope="col" style="text-align: center;">Action</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</td></tr>
</table>
<script src="{{asset('jquery-3.4.1.min.js')}}"></script>
<script type="text/javascript">
	var token = $("meta[name='csrf-token']").attr("content");
	var data = [], kode = '';
	$(function () { clearForm(); });

	function saveForm() {
		var nama = $('#nama').val();
		var qty = $('#qty').val();
		var harga = $('#harga').val();
		if (nama != '' && qty != '' && harga != '') {
			$.ajax({
                url:'/barangs',
                type:'POST',
                data: {
                	act:'save',
					_token:token,
                	nama:nama,
					qty:qty,
					harga:harga,
					kode:window.btoa(kode)
                },
                cache: false,
                success: function(result) { clearForm(); },
                error: function (result) { alert(result); }
            });
		}
	}

	function editForm(i) {
		var value = data[i];
		kode = value.kode;
		$('#nama').val(value.nama);
		$('#qty').val(value.qty);
		$('#harga').val(value.harga);
	}

	function deleteForm(i) {
		if (confirm('Are you sure you want to delete item?')) {
        	$.ajax({
                url:'/barangs',
                type:'POST',
                data: {
                	act:'delete',
					_token:token,
					kode:window.btoa(data[i].kode)
                },
                cache: false,
                success: function(result) { clearForm(); },
                error: function (result) { alert(result); }
            });
        }
	}

	function clearForm() {
		$.ajax({
            url:'/barangs',
            type:'POST',
            data: {act:'get', _token:token},
            cache: false,
            success: function(result) {
            	var no = 1;
            	var tb = '';
            	data = result.data;
            	result.data.forEach(function (value,key) {
            		tb += '<tr>';
            		tb += '<th scope="row" style="text-align: center;">'+no+'</th>';
            		tb += '<td align="center">'+value.kode+'</td>';
            		tb += '<td>'+value.nama+'</td>';
            		tb += '<td align="right">'+value.qty+'</td>';
            		tb += '<td align="right">Rp '+value.harga+'</td>';
            		tb += '<td align="center">'+
            			'<button class="btn btn-primary" onclick="editForm('+key+')" style="margin-right: 5px;">Edit</button>'+
            			'<button class="btn btn-danger" onclick="deleteForm('+key+')">Delete</button></td>';
            		tb += '</tr>';
            		no++;
            	});
				$('#data tbody').html('');
            	$('#data tbody').append(tb);
				$('#nama').val('');
				$('#qty').val('');
				$('#harga').val('');
				kode = '';
            },
            error: function (result) { alert(result); }
        });
	}
</script>
</body>
</html>