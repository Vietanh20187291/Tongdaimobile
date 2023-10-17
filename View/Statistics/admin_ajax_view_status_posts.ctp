<canvas id="myChart" height="150"></canvas>

<h2 class="text-color-black m-t-15">
    <?php
    echo __('Tổng cộng: ').$sum_total_post.' số bài đã kích hoạt ';
    ?>
</h2>

<h2 class="text-color-red m-t-15">
    <?php
    echo __('Tổng bài viết: ').$count_query;
    ?>
</h2>

<h2 class="text-color-red m-t-15">
    <?php
    echo __('Tổng bài viết kích hoat: ').$count_query_active;
    ?>
</h2>
<h2 class="text-color-red m-t-15">
    <?php
    echo __('Tổng bài chưa kích hoạt: ').$sum_non_active_post;
    ?>
</h2>
<script type="text/javascript">
    $(document).ready(function() {
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo $title_posted ?>,
                datasets: [
                    {
                        label: "<?php echo 'Tổng bài chưa kích hoạt ('.$sum_non_active_post.')'?>",
                        data: <?php echo $non_active ?>,
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        borderColor: 'rgba(0, 0, 0, 1)',
                        borderWidth: 2,
                        fill: false
                    },
                    {
                        label: "<?php echo 'Số bài đã kích hoạt ('.$sum_total_post.')'?>",
                        data: <?php echo $active_project ?>,
                        backgroundColor:'rgba(197,0,43,0.78)' ,
                        borderColor:'rgb(186,0,51)' ,
                        borderWidth: 2,
                        fill: false
                    }

                ]
            },
            options: {
                elements: {
                    line: {
                        tension: 0.000001
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                },
            }
        });
    });
</script>
<br>
