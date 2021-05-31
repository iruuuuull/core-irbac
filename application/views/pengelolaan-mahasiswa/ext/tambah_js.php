<script type="text/javascript">

    $(document).ready(function(){
        let student_id = $("#student_id").val();

        if(student_id){
            setTimeout(() => {
                let id_institusi = $('.id_institusi').val();
                if(id_institusi){
                    setSelectedDropdownUnit_id(id_institusi,'selectData');
                }

                let id_provinsi = $('.id_provinsi').val();
                if(id_provinsi){
                    setSelectedDropdownKabupaten(id_provinsi,'selectData');
                }
           }, 500);

            setTimeout(() => {
                let id_kampus = $('.id_kampus').val();
                if(id_kampus){
                    setSelectedDropdownId_product(id_kampus,'selectData');
                }

                let id_kabupaten = $('.id_kabupaten').val();
               if(id_kabupaten){
                setSelectedDropdownKecamatan(id_kabupaten,'selectData');
               }
           }, 800);

             setTimeout(() => {
               let id_kecamatan = $('.id_kecamatan').val();
               if(id_kecamatan){
                setSelectedDropdownKelurahan(id_kecamatan,'selectData');
               }

           }, 1000);

        }

       
    });

     $(document).on('change', '.id_institusi', function(event) {
        event.preventDefault();
        /* Act on the event */
        let id = $(this).val();
        setSelectedDropdownUnit_id(id,'changeData');
    });

     $(document).on('change', '.id_kampus', function(event) {
        event.preventDefault();
        /* Act on the event */
        let id = $(this).val();
        setSelectedDropdownId_product(id,'changeData');

    });


    /* alamat db */

	$(document).on('change', '.id_provinsi', function(event) {
        event.preventDefault();
        /* Act on the event */
        let id = $(this).val();
        setSelectedDropdownKabupaten(id,'changeData');

    });

    $(document).on('change', '.id_kabupaten', function(event) {
        event.preventDefault();
        /* Act on the event */
        let id = $(this).val();
        setSelectedDropdownKecamatan(id,'changeData');

    });


    $(document).on('change', '.id_kecamatan', function(event) {
        event.preventDefault();
        /* Act on the event */
        let id = $(this).val();
        setSelectedDropdownKelurahan(id,'changeData');

    });


    function setSelectedDropdownUnit_id(id,operation){
         /* Act on the event */
         let exist_unit_id = '';
         if(operation == 'selectData'){
            let exist_unit_id = $('.id_kampus').attr('exist_unit_id');
            var url_action = '<?= site_url('pengelolaan-mahasiswa/get-kampus/') ?>'+ id;
        }else{
            var url_action = '<?= site_url('pengelolaan-mahasiswa/get-kampus/') ?>'+ id;
        }

         $.ajax({
            url: url_action,
            type: 'GET',
            dataType: 'json',
        })
         .done(function(data) {
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

     function setSelectedDropdownId_product(id,operation){
         /* Act on the event */
            let exist_product_id = '';
         if(operation == 'selectData'){
            let exist_product_id = $('.id_product').attr('exist_product_id');
            var url_action = '<?= site_url('pengelolaan-mahasiswa/get-product/') ?>'+ id;
        }else{
            var url_action = '<?= site_url('pengelolaan-mahasiswa/get-product/') ?>'+ id;
        }

        $.ajax({
             url: url_action,
             type: 'GET',
             dataType: 'json',
         })
         .done(function(data) {
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



    function setSelectedDropdownKabupaten(id,operation){
         /* Act on the event */
            let exist_student_kabupaten = '';
         if(operation == 'selectData'){
            let exist_student_kabupaten = $('.id_kabupaten').attr('exist_student_kabupaten');
            var url_action = '<?= site_url('pengelolaan-mahasiswa/get-kabupaten/') ?>'+ id;
        }else{
            var url_action = '<?= site_url('pengelolaan-mahasiswa/get-kabupaten/') ?>'+ id;
        }

         $.ajax({
             url: url_action,
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

    function setSelectedDropdownKecamatan(id,operation){
        /* Act on the event */
            let exist_student_kecamatan = '';
        if(operation == 'selectData'){
            let exist_student_kecamatan = $('.id_kecamatan').attr('exist_student_kecamatan');
            var url_action = '<?= site_url('pengelolaan-mahasiswa/get-kecamatan/') ?>'+ id;
        }else{
            var url_action = '<?= site_url('pengelolaan-mahasiswa/get-kecamatan/') ?>'+ id;
        }
       

        $.ajax({
            url: url_action,
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

     function setSelectedDropdownKelurahan(id,operation){
        /* Act on the event */
        
                let exist_student_kelurahan = '';
        if(operation == 'selectData'){
             let exist_student_kelurahan = $('.id_kelurahan').attr('exist_student_kelurahan');
             var url_action = '<?= site_url('pengelolaan-mahasiswa/get-kelurahan/') ?>'+ id;
         }else{
            var url_action = '<?= site_url('pengelolaan-mahasiswa/get-kelurahan/') ?>'+ id;
        }

        $.ajax({
            url: url_action,
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

     $(document).on('change','#id_image_mahasiswa',function(){
       Preview_produk(this);
   });




</script>