<?php
require_once '../core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Logs::addError("Unauthorization access!");
        Redirect::to('../index.php');
    }

    if(!$user->hasPermission('admin_charts_approval', 'read')) {
        Logs::addError('User '. $user->data()->ID .' dont have permission to this page! Permission admin_charts_approval/read');
        Session::flash('warning', 'Nie masz uprawnień!');
        Redirect::to('home.php');
    }

?>
<!DOCTYPE html>
<HTML>
<HEAD>

    <?php include_once Config::get('includes/second_index'); ?>

    <script>

        function wykres(id = 'piechart', titlee = 'My Daily Activities', val1 = 33, val2 = 42, val3 = 25 ) {
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var data = google.visualization.arrayToDataTable([
                    ['Task', 'Hours per Day'],
                    ['Nie zaakceptowane',  val1],
                    ['Zaakceptowane',      val2],
                    ['Nie uzupełnione',    val3]
                ]);

                var options = {
                    title: titlee,
                    is3D: true,
                    pieStartAngle: 100,
                    colors: ['#f7453f', '#3ece42', '#4d5360'],
                    backgroundColor: '#808891',
                    legend: {position: 'bottom'},
            };

                var chart = new google.visualization.PieChart(document.getElementById(id));

                chart.draw(data, options);
            }

        }


        <?php

            $div_html = '';
            $db = DBB::getInstance();
            $agreements = $db->query('SELECT `IDagreementsConfiguration`, ac.Title, ac.Version, COUNT(IDUsers) SumAgreement 
                                            FROM `agreements` a LEFT JOIN agreements_configuration ac ON a.IDagreementsConfiguration=ac.ID 
                                            GROUP BY IDagreementsConfiguration ASC 
                                            ORDER BY ac.Title ASC, ac.Version DESC');

            foreach($agreements->results() as $agreement) {
                $details = $db->query('SELECT AcceptAgreement, COUNT(IDUsers) SumUsers
                                            FROM `agreements`
                                            WHERE IDagreementsConfiguration = '. (int)$agreement->IDagreementsConfiguration .'
                                            GROUP BY AcceptAgreement ASC');
                //Values for chart
                $yes = $no = $null = 0;

                //Values for agreement
                foreach($details->results() as $detail) {
                    if($detail->AcceptAgreement == NULL) {
                        $null = (int)$detail->SumUsers;
                    }else if($detail->AcceptAgreement == '0') {
                        $no = (int)$detail->SumUsers;
                    }else if($detail->AcceptAgreement == '1') {
                        $yes = (int)$detail->SumUsers;
                    }
                }

                //js listeren
                $js = "window.addEventListener('load', (event) => {\n";
                $js .= "\twykres('chart_{$agreement->IDagreementsConfiguration}', '{$agreement->Title} - wersja 0.{$agreement->Version}', {$no}, {$yes}, {$null});\n";
                $js .= "});\n";
                // div in html on charts
                $div_html .= '<div id="chart_'. (int)$agreement->IDagreementsConfiguration .'" style="width: 100%; height: 350px; margin-bottom: 10px;"></div>' . "\n";

                echo $js;
            }

        ?>

    </script>

</HEAD>
<BODY class="bg-secondary">

<div class="container">
    <div class="row mt-5">
        <div class="col-1 col-md-2 col-lg-2">

            <?php include_once Config::get('includes/second_admin_menu'); ?>

        </div>
        <div class="col-10 col-md-8 col-lg-8">

            <h2 class="text-warning">Wykresy dla zgód!</h2>

            <?php
                echo $div_html;
            ?>

            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        </div>
        <div class="col-1 col-md-2 col-lg-2"></div>
    </div>
</div>

</BODY>
</HTML>