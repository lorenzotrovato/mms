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
    $values = $mysqli->querySelect('SELECT DISTINCT YEAR(datePurchase) AS year FROM biglietto ORDER BY year DESC');
    if(count($values) > 0){
        $anniHTML .= '<option value="all">Tutto</option>';
        foreach($values as $year){
            $anniHTML .= '<option value="'.$year['year'].'">'.$year['year'].'</option>';
        }
    }else{
        $anniHTML.='<option value="none" disabled>Nessun biglietto</option>';
    }
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="mb-0">Grafico Finanze <small class="text-muted">Entrate Mensili</small></h1>
    <hr>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="mr-2">
            <select id="year" class="form-control custom-select">
                <?php echo $anniHTML; ?>
            </select>
        </div>
    </div>
</div>

<canvas class="my-4" id="myChart" width="900" height="380"></canvas>

<table id="tableTrans" class="table d-none">
    <thead class="thead-dark">
        <th scope="col">#</th>
        <th scope="col">Utente</th>
        <th scope="col">Data Acquisto</th>
        <th scope="col">Categoria</th>
        <th scope="col">Sconto [%]</th>
        <th scope="col">Totale</th>
    </thead>
    <tbody id="trList">
    </tbody>
</table>

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
            $('#tableTrans').fadeOut(200);
        });
    });
    
    function loadChart() {
        var graphData = [];
        var ctx = $('#myChart');
        $.ajax({
            type: 'GET',
            cache: false,
            url: './includes/router.php',
            data : {
                year : $('#year').val(),
                action : 'finGraph',
            },
            success : function(data) {
                ctx.html('');
                console.log(data);
                graphData = Object.values(JSON.parse(data));
                myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
                        datasets: [{
                            data: graphData,
                            
                            backgroundColor: [
                                'rgb(0, 97, 255, 0.2)',
                                'rgb(0, 97, 255, 0.2)',
                                'rgba(41, 178, 0, 0.2)',
                                'rgba(41, 178, 0, 0.2)',
                                'rgba(41, 178, 0, 0.2)',
                                'rgba(255, 55, 25, 0.2)',
                                'rgba(255, 55, 25, 0.2)',
                                'rgba(255, 55, 25, 0.2)',
                                'rgba(255, 172, 0, 0.2)',
                                'rgba(255, 172, 0, 0.2)',
                                'rgba(255, 172, 0, 0.2)',
                                'rgb(0, 97, 255, 0.2)'
                            ],
                            borderColor: [
                                'rgb(0, 97, 255, 1)',
                                'rgb(0, 97, 255, 1)',
                                'rgba(41, 178, 0, 1)',
                                'rgba(41, 178, 0, 1)',
                                'rgba(41, 178, 0, 1)',
                                'rgba(255, 55, 25, 1)',
                                'rgba(255, 55, 25, 1)',
                                'rgba(255, 55, 25, 1)',
                                'rgba(255, 172, 0, 1)',
                                'rgba(255, 172, 0, 1)',
                                'rgba(255, 172, 0, 1)',
                                'rgb(0, 97, 255, 1)'
                            ],
                            borderWidth: 4
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        },
                        legend: {
                            display: false,
                        }
                    }
                });
                loadTable();
            },
            error : function(e) {
                console.log(e)
            }
        });
    }
    
    function loadTable() {
        $.ajax({
            type: 'GET',
            cache: false,
            url: './includes/router.php',
            data : {
                year : $('#year').val(),
                action : 'finTable'
            },
            success : function(data) {
                $('#tableTrans').removeClass('d-none');
                $('#trList').html(data);
                $('#tableTrans').fadeIn(200);
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