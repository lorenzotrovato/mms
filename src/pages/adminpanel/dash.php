<?php
    namespace MMS;
    require_once '../../includes/autoload.php';
    use MMS\Security;
    use MMS\Database;
    Security::init();
    if(!Security::isAdmin()){
        header('Location: signin.php');
    }
    
    
    //anni dei biglietti per grafico
    $anniHTML = '';
    $mysqli = Database::init();
    $anniQuery = $mysqli->querySelect('SELECT DISTINCT YEAR(datePurchase) AS dateV FROM biglietto ORDER BY dateV DESC');
    if(count($anniQuery) > 0){
        $anniHTML .= "<option value='all'>Tutto</option>";
        foreach($anniQuery as $year){
            $anniHTML .= '<option>'.$year['dateV'].'</option>';
        }
    }else{
        $anniHTML .= '<option value="none" disabled>Nessun biglietto</option>';
    }
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1>Dashboard <small class="text-muted">Biglietti mensili</small></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="mr-2">
            <select id="year" class="form-control custom-select" value="Nessun biglietto" omnchange="graphyear()">
                <?php echo $anniHTML; ?>
            </select>
        </div>
    </div>
</div>

<canvas class="my-4" id="myChart" width="900" height="380"></canvas>

<!-- Graphs -->
<script src="./js/Chart.min.js"></script>
<script>
    var myChart;
    $(document).ready(function() {
        loadChart();
        
        $('#year').change(function() {
            $('#myChart').animate({
                'opacity' : '0',
            }, 200, function(){
                $(this).css({
                    'visibility' : 'hidden'
                });
                myChart.destroy();
                loadChart();
            });
        });
    });
    
    function loadChart() {
        var graphData = [];
        var ctx = document.getElementById("myChart");
        $.ajax({
            type: 'GET',
            cache: false,
            url: './includes/router.php',
            data : {
                year : $('#year').val(),
                action: 'dashGraph'
            },
            success : function(data) {
                graphData = Object.values(JSON.parse(data));
                myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
                        datasets: [{
                            data: graphData,
                            lineTension: 0,
                            backgroundColor: 'transparent',
                            borderColor: '#007bff',
                            borderWidth: 4,
                            pointBackgroundColor: '#007bff'
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    userCallback: function(label, index, labels) {
                                        if (Math.floor(label) === label) {
                                            return label;
                                        }
                                    }
                                }
                            }]
                        },
                        legend: {
                            display: false,
                        }
                    }
                });
                $('#myChart').css('visibility', 'visible');
                $('#myChart').animate({
                    'opacity' : '1'
                }, 200);
            },
            error : function(e) {
                console.log(e)
            }
        });
    }
</script>