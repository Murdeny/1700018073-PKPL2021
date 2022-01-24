<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<?php
$v = "?v=1";

if ($this->input->post('category')){
    $v .= "&category=".$this->input->post('category');
}
if ($this->input->post('start_date')){
    $v .= "&start_date=".$this->input->post('start_date');
}
if ($this->input->post('end_date')) {
    $v .= "&end_date=".$this->input->post('end_date');
}

?>

<script type="text/javascript">
    $(document).ready(function() {

        function image(n) {
            if (n !== null) {
                return '<div style="width:32px; margin: 0 auto;"><a href="<?=base_url();?>uploads/'+n+'" class="open-image"><img src="<?=base_url();?>uploads/thumbs/'+n+'" alt="" class="img-responsive"></a></div>';
            }
            return '';
        }

        function method(n) {
            return (n == 0) ? '<span class="label label-primary"><?= lang('inclusive'); ?></span>' : '<span class="label label-warning"><?= lang('exclusive'); ?></span>';
        }

        var table = $('#PrRData').DataTable({

            'ajax' : { url: '<?=site_url('reports/get_products/'. $v);?>', type: 'POST', "data": function ( d ) {
                d.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash()?>";
            }},
            "buttons": [
            { extend: 'copyHtml5', 'footer': true, exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ] } },
            { extend: 'excelHtml5', 'footer': true, exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ] } },
            { extend: 'csvHtml5', 'footer': true, exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ] } },
            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'A4', 'footer': true,
            exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ] } },
            { extend: 'colvis', text: 'Columns'},
            ],
            "columns": [
            { "data": "id", "visible": false },
			
            { "data": "code" },
            { "data": "name" },
            { "data": "sold", "searchable": false },
            { "data": "tax", "searchable": false, "render": currencyFormat },
            { "data": "cost", "searchable": false, "render": currencyFormat },
            { "data": "income", "searchable": false, "render": currencyFormat },
            { "data": "profit", "searchable": false, "render": currencyFormat }
            ],
            "footerCallback": function (  tfoot, data, start, end, display ) {
                var api = this.api(), data;
                $(api.column(3).footer()).html( (api.column(3).data().reduce( function (a, b) { return pf(a) + pf(b); }, 0)) );
                $(api.column(4).footer()).html( cf(api.column(4).data().reduce( function (a, b) { return pf(a) + pf(b); }, 0)) );
                $(api.column(5).footer()).html( cf(api.column(5).data().reduce( function (a, b) { return pf(a) + pf(b); }, 0)) );
                $(api.column(6).footer()).html( cf(api.column(6).data().reduce( function (a, b) { return pf(a) + pf(b); }, 0)) );
                $(api.column(7).footer()).html( cf(api.column(7).data().reduce( function (a, b) { return pf(a) + pf(b); }, 0)) );
            }

        });

        $('#search_table').on( 'keyup change', function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (((code == 13 && table.search() !== this.value) || (table.search() !== '' && this.value === ''))) {
                table.search( this.value ).draw();
            }
        });

        table.columns().every(function () {
            var self = this;
            $( 'input.datepicker', this.footer() ).on('dp.change', function (e) {
                self.search( this.value ).draw();
            });
            $( 'input:not(.datepicker)', this.footer() ).on('keyup change', function (e) {
                var code = (e.keyCode ? e.keyCode : e.which);
                if (((code == 13 && self.search() !== this.value) || (self.search() !== '' && this.value === ''))) {
                    self.search( this.value ).draw();
                }
            });
            $( 'select', this.footer() ).on('change', function (e) {
                self.search( this.value ).draw();
            });
        });

    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#form').hide();
        $('.toggle_form').click(function(){
            $("#form").slideToggle();
            return false;
        });
    });
</script>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <a href="#" class="btn btn-default btn-sm toggle_form pull-right"><?= lang("show_hide"); ?></a>
                    <h3 class="box-title"><?= lang('customize_report'); ?></h3>
                </div>
                <div class="box-body">
                    <div id="form" class="panel panel-warning">
                        <div class="panel-body">
                            <?= form_open("reports/products");?>

                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label class="control-label" for="category"><?= lang("category"); ?></label>
                                        <?php
                                        $pr[0] = lang("select")." ".lang("category");
                                        foreach($products as $category){
                                            $pr[$category->id] = $category->name;
                                        }
                                        echo form_dropdown('category', $pr, set_value('category'), 'class="form-control select2" style="width:100%" id="category"');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label class="control-label" for="start_date"><?= lang("start_date"); ?></label>
                                        <?= form_input('start_date', set_value('start_date'), 'class="form-control" id="start_date"');?>
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label class="control-label" for="end_date"><?= lang("end_date"); ?></label>
                                        <?= form_input('end_date', set_value('end_date'), 'class="form-control" id="end_date"');?>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary"><?= lang("submit"); ?></button>
                                </div>
                            </div>
                            <?= form_close();?>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="PrRData" class="table table-striped table-bordered table-hover" style="margin-bottom:5px;">
                                    <thead>
                                        <tr class="active">
                                            <th style="max-width:30px;"><?= lang("id"); ?></th>
                                            <th><?= lang("code"); ?></th>
                                            <th class="col-xs-2"><?= lang("name"); ?></th>
                                            <th class="col-xs-1"><?= lang("sold"); ?></th>
                                            <th class="col-xs-1"><?= lang("tax"); ?></th>
                                            <th class="col-xs-1"><?= lang("cost"); ?></th>
                                            <th class="col-xs-1"><?= lang("income"); ?></th>
                                            <th class="col-xs-1"><?= lang("profit"); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="8" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="active">
                                            <th style="max-width:30px;"><input type="text" class="text_filter" placeholder="[<?= lang('id'); ?>]"></th>
                                            <th><input type="text" class="text_filter" placeholder="[<?= lang('name'); ?>]"></th>
                                            <th class="col-sm-2"><input type="text" class="text_filter" placeholder="[<?= lang('code'); ?>]"></th>
                                            <th class="col-xs-1"><?= lang("sold"); ?></th>
                                            <th class="col-xs-1"><?= lang("tax"); ?></th>
                                            <th class="col-xs-1"><?= lang("cost"); ?></th>
                                            <th class="col-xs-1"><?= lang("income"); ?></th>
                                            <th class="col-xs-1"><?= lang("profit"); ?></th>
                                        </tr>
                                        <tr>
                                            <td colspan="8" class="p0"><input type="text" class="form-control b0" name="search_table" id="search_table" placeholder="<?= lang('type_hit_enter'); ?>" style="width:100%;"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
