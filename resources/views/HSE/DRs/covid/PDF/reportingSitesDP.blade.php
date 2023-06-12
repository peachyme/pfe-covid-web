<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>REPORTING SITES DP COVID-19</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style>
        table {
            font-size: 12px;
        }

        table.table-bordered>thead>tr>th {
            border: 1px solid #1a1a1a;
        }

        table.table-bordered>tbody>tr>th {
            border: 1px solid #1a1a1a;
        }

        table.table-bordered>tbody>tr>td {
            border: 1px solid #1a1a1a;
        }
    </style>
</head>

<body>
    <table width="100%" class="mb-4">
        <tr>
            <td valign="top">
                <span><img src="{{ public_path("/images/logo.png") }}" alt="" width="80" height="120"></span>
            </td>
            <td align="right" class="px-3" style="line-height: 150%">
                Activité Exploration-Production <br>
                Division Production <br>
                Département HSE <br>
                Activité Prévention et Santé <br> <br>
                Le : {{$date}}
            </td>
        </tr>
    </table>
    <div class="text-center bg-light py-3 mb-4">
        <strong>REPORTING <span style="text-transform: uppercase ;">{{$code}}</span> DES SITES-DP RELATIF AU COVID-19 </strong>
    </div>
    <table class="table table-hover table-bordered text-center">
        <thead>
            <tr>
                <th scope="col">Sites <br> DP</th>
                <th scope="col">Nbr de cas confirmés positifs</th>
                <th scope="col">Nombre de cas guéris</th>
                <th scope="col">Nombre de cas en quarantaine</th>
                <th scope="col">Nombre de cas hospitalisés</th>
                <th scope="col">Nombre de décès</th>
                <th scope="col">Observations</th>
                <th scope="col">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($regions as $region)
            <tr>
                <th scope="row">{{$region->code_region}}</th>
                <td>{{abs(($report_pos[$region->id]['cas_positifs'] ?? 0) - ($report_pro[$region->id]['cas_pro'] ?? 0) - ($report_deces[$region->id]['cas_deces'] ?? 0))}}</td>
                <td>{{abs($report_gueris[$region->id]['cas_gueris'] ?? 0)}}</td>
                <td>{{abs((($report_bdv[$region->id]['cas_bdv'] ??  0) + ($report_dom[$region->id]['cas_dom'] ?? 0) + ($report_hosp[$region->id]['cas_hosp'] ?? 0)) - ($report_rt[$region->id]['cas_rt'] ?? 0) - ($report_d[$region->id]['cas_d'] ?? 0) - ($report_p[$region->id]['cas_p'] ?? 0))}}</td>
                <td>{{abs($report_hosp[$region->id]['cas_hosp'] ?? 0)}}</td>
                <td>{{abs($report_deces[$region->id]['cas_deces'] ?? 0)}}</td>
                <td>{{$observations[$region->id]}}</td>
                <td><strong>{{abs($report_total[$region->id]['total'] ?? 0)}}</strong></td>
            </tr>
            @endforeach
            <tr>
                <th scope="row"><strong>Total</strong></th>
                <td><strong>{{abs($cas_positif_total)}}</strong></td>
                <td><strong>{{abs($cas_gueris_toal)}}</strong></td>
                <td><strong>{{abs($cas_conf_total)}}</strong></td>
                <td><strong>{{abs($cas_hosp_total)}}</strong></td>
                <td><strong>{{abs($cas_deces_total)}}</strong></td>
                <td><strong> </strong></td>
                <td><strong>{{abs($cas_total)}}</strong></td>
            </tr>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>
