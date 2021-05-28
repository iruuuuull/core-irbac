<script type="text/javascript">

     $(document).on('change', '.id_institusi', function(event) {
        event.preventDefault();
        /* Act on the event */
        let id = $(this).val();

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
                 dropdown_kampus.append(`<option value="${index}">${val}</option>`);
             });
         })
         .fail(function() {
             console.error("Get data kampus failed");
         });

    });

     $(document).on('change', '.id_kampus', function(event) {
        event.preventDefault();
        /* Act on the event */
        let id = $(this).val();

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
                 dropdown_product.append(`<option value="${index}">${val}</option>`);
             });
         })
         .fail(function() {
             console.error("Get data Jurusan failed");
         });

    });


    /* alamat db */



	$(document).on('change', '.id_provinsi', function(event) {
        event.preventDefault();
        /* Act on the event */
        let id = $(this).val();

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
                 dropdown_kabupaten.append(`<option value="${index}">${val}</option>`);
             });
         })
         .fail(function() {
             console.error("Get data kabupaten failed");
         });

    });

    $(document).on('change', '.id_kabupaten', function(event) {
        event.preventDefault();
        /* Act on the event */
        let id = $(this).val();

         $.ajax({
             url: '<?= site_url('pengelolaan-mahasiswa/get-kecamatan/') ?>'+ id,
             type: 'GET',
             dataType: 'json',
         })
         .done(function(data) {
         	console.log(data);
             let dropdown_kecamatan = $(".id_kecamatan");
             dropdown_kecamatan.empty();

             dropdown_kecamatan.append(`<option value="">- Pilih Kecamatan -</option>`);

             $.each(data, function(index, val) {
                 dropdown_kecamatan.append(`<option value="${index}">${val}</option>`);
             });
         })
         .fail(function() {
             console.error("Get data kecamatan failed");
         });

    });

    $(document).on('change', '.id_kecamatan', function(event) {
        event.preventDefault();
        /* Act on the event */
        let id = $(this).val();

         $.ajax({
             url: '<?= site_url('pengelolaan-mahasiswa/get-kelurahan/') ?>'+ id,
             type: 'GET',
             dataType: 'json',
         })
         .done(function(data) {
         	console.log(data);
             let dropdown_kelurahan = $(".id_kelurahan");
             dropdown_kelurahan.empty();

             dropdown_kelurahan.append(`<option value="">- Pilih Kelurahan -</option>`);

             $.each(data, function(index, val) {
                 dropdown_kelurahan.append(`<option value="${index}">${val}</option>`);
             });
         })
         .fail(function() {
             console.error("Get data kelurahan failed");
         });

    });

    $(document).on('change','#id_image_mahasiswa',function(){
         Preview_produk(this);
    });


</script>