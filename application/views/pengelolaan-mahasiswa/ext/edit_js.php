<script type="text/javascript">
	$(document).ready(function(){
        let student_id = $("#student_id").val();

        if(student_id){
            setTimeout(() => {
            	let id = $('.id_institusi').val();
            	setSelectedDropdownUnit_id(id);

            	let id_provinsi = $('.id_provinsi').val();
            	setSelectedDropdownKabupaten(id_provinsi);
           }, 500);

            setTimeout(() => {
               let id_kampus = $('.id_kampus').val();
               setSelectedDropdownId_product(id_kampus);

               let id_kabupaten = $('.id_kabupaten').val();
               setSelectedDropdownKecamatan(id_kabupaten);
           }, 800);

             setTimeout(() => {
               let id_kecamatan = $('.id_kecamatan').val();
               setSelectedDropdownKelurahan(id_kecamatan);

           }, 1000);

        }

       
    });

     function setSelectedDropdownUnit_id(id){
         /* Act on the event */

         let exist_unit_id = $('.id_kampus').attr('exist_unit_id');

         $.ajax({
            url: '<?= site_url('pengelolaan-mahasiswa/get-kampus/') ?>'+ id,
            type: 'GET',
            dataType: 'json',
        })
         .done(function(data) {
             console.log(data);
             let dropdown_kampus = $(".id_kampus");
             dropdown_kampus.empty();

             dropdown_kampus.append(`<option value="">- Pilih Kampus -</option>`);

             $.each(data, function(index, val) {

                if(exist_unit_id == index){
                    var select = "selected";
                }else{
                    var select = "";
                }

                dropdown_kampus.append(`<option ${select} value="${index}">${val}</option>`);
            });
         })
         .fail(function() {
            console.error("Get data kampus failed");
        });

     }

      function setSelectedDropdownId_product(id){
         /* Act on the event */
         let exist_product_id = $('.id_product').attr('exist_product_id');

         $.ajax({
             url: '<?= site_url('pengelolaan-mahasiswa/get-product/') ?>'+ id,
             type: 'GET',
             dataType: 'json',
         })
         .done(function(data) {
            console.log(data);
             let dropdown_product = $(".id_product");
             dropdown_product.empty();

             dropdown_product.append(`<option value="">- Pilih Jurusan -</option>`);

             $.each(data, function(index, val) {
                
                if(exist_product_id == index){
                    var select = "selected";
                }else{
                    var select = "";
                }

                 dropdown_product.append(`<option ${select} value="${index}">${val}</option>`);
             });
         })
         .fail(function() {
             console.error("Get data Jurusan failed");
         });

     }

      function setSelectedDropdownKabupaten(id){
         /* Act on the event */
         let exist_student_kabupaten = $('.id_kabupaten').attr('exist_student_kabupaten');

		   $.ajax({
             url: '<?= site_url('pengelolaan-mahasiswa/get-kabupaten/') ?>'+ id,
             type: 'GET',
             dataType: 'json',
         })
         .done(function(data) {
             let dropdown_kabupaten = $(".id_kabupaten");
             dropdown_kabupaten.empty();

             dropdown_kabupaten.append(`<option value="">- Pilih Kabupaten -</option>`);

             $.each(data, function(index, val) {
             	if(exist_student_kabupaten == index){
             		var select = "selected";
             	}else{
             		var select = "";
             	}

                 dropdown_kabupaten.append(`<option ${select} value="${index}">${val}</option>`);
             });
         })
         .fail(function() {
             console.error("Get data kabupaten failed");
         });
     }

     function setSelectedDropdownKecamatan(id){
     	/* Act on the event */
     	let exist_student_kecamatan = $('.id_kecamatan').attr('exist_student_kecamatan');

     	$.ajax({
     		url: '<?= site_url('pengelolaan-mahasiswa/get-kecamatan/') ?>'+ id,
     		type: 'GET',
     		dataType: 'json',
     	})
     	.done(function(data) {
     		let dropdown_kecamatan = $(".id_kecamatan");
     		dropdown_kecamatan.empty();

     		dropdown_kecamatan.append(`<option value="">- Pilih Kecamatan -</option>`);

     		$.each(data, function(index, val) {
     			
     			if(exist_student_kecamatan == index){
     				var select = "selected";
     			}else{
     				var select = "";
     			}

     			dropdown_kecamatan.append(`<option ${select} value="${index}">${val}</option>`);
     		});
     	})
     	.fail(function() {
     		console.error("Get data kecamatan failed");
     	});
     }

     function setSelectedDropdownKelurahan(id){
     	/* Act on the event */
     	let exist_student_kelurahan = $('.id_kelurahan').attr('exist_student_kelurahan');

     	$.ajax({
     		url: '<?= site_url('pengelolaan-mahasiswa/get-kelurahan/') ?>'+ id,
     		type: 'GET',
     		dataType: 'json',
     	})
     	.done(function(data) {
     		let dropdown_kelurahan = $(".id_kelurahan");
     		dropdown_kelurahan.empty();

     		dropdown_kelurahan.append(`<option value="">- Pilih Kelurahan -</option>`);

     		$.each(data, function(index, val) {
     			
     			if(exist_student_kelurahan == index){
     				var select = "selected";
     			}else{
     				var select = "";
     			}

     			dropdown_kelurahan.append(`<option ${select} value="${index}">${val}</option>`);
     		});
     	})
     	.fail(function() {
     		console.error("Get data kelurahan failed");
     	});
     }




</script>