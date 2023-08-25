<script>
	function deleteData(dt){
		if (confirm("Apakah anda yakin mau menghapus data ini?")) {
			$.ajax({
				type:"DELETE",
				url:$(dt).data("url"),
				data: {
					"_token": "{{ csrf_token() }}"
				},				
				success:function(response){
					if(response.status){
						location.reload();
					}
				},
                error: function(response){
                    //console.log(response);
                }
			});
		}
		return false;
	}
	
	$(document).ready(function() {
		$('body').on('click', '.btn-view', function() {
			$(".btn-view").colorbox({
				'width'				: '600px',
				'maxWidth'			: '90%',
				'maxHeight'			: '90%',
				'transition'		: 'elastic',
				'scrolling'			: true,
			});
		});
		
	})

	$(document).ready(function() {
		$('body').on('click', '.btn-keep', function() {
			$(".btn-keep").colorbox({
				'width'				: '800px',
				'maxWidth'			: '90%',
				'maxHeight'			: '90%',
				'transition'		: 'elastic',
				'scrolling'			: true,
			});
		});
		
	})

	

	$('body').on('click', '.browse-keep', function (e) {
		$.colorbox({
            'width'				: '90%',
            'height'			: '95%',
            'maxWidth'			: '75%',
            'maxHeight'			: '95%',
            'transition'		: 'elastic',
            'scrolling'			: true,
            'href'              : $(this).attr('href')
        });
		e.preventDefault();
	});
	
	function received(dt){
		if (confirm("Apakah anda yakin mau mengubah status PO ini menjadi terima ?")) {
			$.ajax({
				type:"POST",
				url:$(dt).data("url"),
				data: {
					"_token": "{{ csrf_token() }}"
				},				
				success:function(response){
					if(response.status){
						location.reload();
					}
				},
			});
		}
		return false;
	}

	function pulled(dt){
		if (confirm("Apakah anda yakin mau pull barang?")) {
			$.ajax({
				type:"POST",
				url:$(dt).data("url"),
				data: {
					"_token": "{{ csrf_token() }}"
				},				
				success:function(response){
					if(response.status){
						location.reload();
					}
				},
			});
		}
		return false;
	}
</script>