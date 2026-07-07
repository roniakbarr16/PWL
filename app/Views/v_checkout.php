<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-6">
        <?= form_open('buy', 'class="row g-3"') ?>

        <?= form_hidden('username', session()->get('username')) ?>

        <div class="col-12">
            <?= form_label('Nama', 'nama', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'     => 'nama',
                'id'       => 'nama',
                'class'    => 'form-control',
                'value'    => session()->get('username'),
                'readonly' => true]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Alamat', 'alamat', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'  => 'alamat',
                'id'    => 'alamat',
                'class' => 'form-control']) ?>
        </div> 
        <div class="col-12"> 
            <?= form_label('Kelurahan', 'kelurahan', ['class' => 'form-label']) ?>
            <?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-control']) ?>
        </div>
        <div class="col-12"> 
            <?= form_label('Layanan', 'layanan', ['class' => 'form-label']) ?> 
            <?= form_dropdown('layanan', [], '', ['id' => 'layanan', 'class' => 'form-control']) ?>
        </div>
        <div class="col-12">
            <?= form_label('Ongkir', 'ongkir', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'     => 'ongkir',
                'id'       => 'ongkir',
                'class'    => 'form-control',
                'readonly' => true]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Kode Kupon', 'kupon_code', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'        => 'kupon_code',
                'id'          => 'kupon_code',
                'class'       => 'form-control',
                'placeholder' => 'Masukkan kode kupon (HEMAT / SUPER)']) ?>
            <small class="text-muted">Tersedia: HEMAT (15%), SUPER (20%)</small>
        </div>
        <div class="col-12">
            <?= form_submit(
                'submit',
                'Buat Pesanan',
                ['class' => 'btn btn-primary']) ?>
        </div>

        <?= form_close() ?> 
    </div>
    <div class="col-lg-6">
        <table class="table">
        <thead>
            <tr>
                <th scope="col">Nama</th>
                <th scope="col">Harga</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (!empty($items)) :
                foreach ($items as $index => $item) :
            ?>
                    <tr>
                        <td><?= $item['name'] ?></td>
                        <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
                    </tr>
            <?php
                endforeach;
            endif;
            ?>
            <tr>
                <td colspan="3">Total Harga</td>
                <td><span id="display_subtotal"><?= number_to_currency($total, 'IDR') ?></span></td>
            </tr>
            <tr>
                <td colspan="3">Diskon Kupon</td>
                <td><span id="display_diskon">Rp 0</span></td>
            </tr>
            <tr>
                <td colspan="3">Biaya Admin</td>
                <td><span id="display_biaya_admin">Rp 0</span></td>
            </tr>
            <tr>
                <td colspan="3">Cashback</td>
                <td><span id="display_cashback">Rp 0</span></td>
            </tr>
            <tr>
                <td colspan="3">Subtotal</td>
                <td><span id="display_subtotal_after"><?= number_to_currency($total, 'IDR') ?></span></td>
            </tr>
            <tr>
                <td colspan="3">Ongkir</td>
                <td><span id="display_ongkir">Rp 0</span></td>
            </tr>
            <tr>
                <td colspan="3"><strong>Grand Total</strong></td>
                <td><strong><span id="display_grand_total"><?= number_to_currency($total, 'IDR') ?></span></strong></td>
            </tr>
        </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
$(document).ready(function() {
    let ongkir = 0;
    let subtotal = <?= $total ?>;
    hitungTotal();

    function formatRupiah(angka) {
        return `Rp ${Math.round(angka).toLocaleString('id-ID')}`;
    }

    function hitungTotal() {
        let kuponCode = $('#kupon_code').val().trim().toUpperCase();
        let diskonPersen = 0;

        if (kuponCode === 'HEMAT') diskonPersen = 0.15;
        else if (kuponCode === 'SUPER') diskonPersen = 0.20;

        let diskonKupon = subtotal * diskonPersen;
        let biayaAdmin = subtotal <= 20000000 ? subtotal * 0.005 : subtotal * 0.0075;
        let cashback = subtotal > 10000000 ? subtotal * 0.02 : 0;
        let subtotalAfter = subtotal - diskonKupon + biayaAdmin;
        let grandTotal = subtotalAfter + ongkir;

        $('#display_subtotal').text(formatRupiah(subtotal));
        $('#display_diskon').text(`- ${formatRupiah(diskonKupon)}`);
        $('#display_biaya_admin').text(`+ ${formatRupiah(biayaAdmin)}`);
        $('#display_cashback').text(formatRupiah(cashback));
        $('#display_subtotal_after').text(formatRupiah(subtotalAfter));
        $('#display_ongkir').text(formatRupiah(ongkir));
        $('#display_grand_total').text(formatRupiah(grandTotal));

        $("#ongkir").val(ongkir);
    }

    $('#kupon_code').on('input', function() {
        hitungTotal();
    });

	$('#kelurahan').select2({
	    placeholder: 'Cari daerah tujuan',
	    minimumInputLength: 3,
        ajax: {
            url: '<?= site_url('ajax/destinations') ?>',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return data;
            },
            cache: true
        } 
	});

    $("#kelurahan").on('change', function () {
        let id_kelurahan = $(this).val();

        $("#layanan").empty();
        ongkir = 0;
        hitungTotal(); 

        $.ajax({
            url: "<?= site_url('ajax/costs') ?>", 
            dataType: "json",
            data: {
                destination: id_kelurahan
            },
            success: function (data) { 
                data.forEach(function (item) {
                    $("#layanan").append(
                        $('<option>', {
                            value: item.cost,
                            text: `${item.description} (${item.service}) : estimasi ${item.etd}`
                        })
                    );
                });
            }
        });
    });

    $("#layanan").on('change', function() {
        ongkir = parseInt($(this).val());
        hitungTotal();
    }); 

});
</script>
<?= $this->endSection() ?>  
