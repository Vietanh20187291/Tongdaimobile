
<div id="column_right">
    <div id="action_top">
        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; float: left;">
            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
            <span></span> <b class="caret">>></b>
        </div>
        <h1 style="float: left;">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Thống kê bài viết kích hoạt') ?></h1>
    </div>
    <div id="content">
        <div id="chart"></div>
    </div>
</div>
<?php //pr(date('d/m/y',1537237924));die; ?>
<script type="text/javascript">
    function load_data(start,end){
        $.ajax({
            type: 'post',
            url: '<?php echo $this->Html->url(array('action'=>'ajaxViewStatusPosts'));?>',
            data:'start='+start+'&end='+end,
            beforeSend:function(){
                $("#loading").show();
            },
            success: function(result){
                $("#loading").hide();
                $("#chart").html(result);
            }
        })
    }
    $(document).ready(function() {
        init_daterangepicker();
    });
</script>