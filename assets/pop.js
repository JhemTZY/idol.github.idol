<script>
function get_revision(model_id) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('index.php/task/get_revision'); ?>",
        data: {
            modelID: model_id
        },
        success: (data) => {
            d = JSON.parse(data);
            $('#revis').html(d[0].model);
        }
    })
}
</script>

