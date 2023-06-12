<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reporting sites DP</title>
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
        <strong>REPORTING <span style="text-transform: uppercase ;">{{$situation}}</span> DES SITES-DP RELATIF A L'OPERATION DE VACCINATION </strong>
    </div>
    <table class="table table-hover table-bordered text-center">
        <thead>
            <tr>
                <th scope="col" class="col-2">Sites <br> DP</th>
                <th scope="col" class="col-2">Effectif <br> Agents SH</th>
                <th scope="col" class="col">Total des vaccinés {{ $situation }}</th>
                <th scope="col" class="col-2">Cumul <br> vaccination</th>
                <th scope="col" class="col-2">% <br> vaccination</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($regions as $region)
            <tr>
                <th scope="row">{{ $region->code_region }}</th>
                <td>{{ $report_effectif_agents[$region->id]['total'] ?? 0 }}</td>
                <td>{{ $report_total_vacc_agents[$region->id]['total'] ?? 0 }}</td>
                <td>{{ $report_cumul_agents[$region->id]['total'] ?? 0 }}</td>
                <td>{{ $report_pourcentage_agents[$region->id]['total'] ?? 0 }} %</td>
            </tr>
            @endforeach
            <tr>
                <th scope="row">Total</th>
                <td><strong>{{$total_effectif_agents}}</strong></td>
                <td><strong>{{$total_vacc_agents_regions}}</strong></td>
                <td><strong>{{$cumul_vacc_agents_regions}}</strong></td>
                <td><strong>{{$pourcentage_agents_regions}} %</strong></td>
            </tr>
        </tbody>
    </table>
    <table class="table table-hover table-bordered text-center" style="page-break-before: always;">
        <thead>
            <tr>
                <th scope="col" class="col-2">Sites <br> DP</th>
                <th scope="col" class="col-2">Effectif <br> Sous-Traitans</th>
                <th scope="col" class="col">Total des vaccinés {{ $situation }}</th>
                <th scope="col" class="col-2">Cumul <br> vaccination</th>
                <th scope="col" class="col-2">% <br> vaccination</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($regions as $region)
            <tr>
                <th scope="row">{{ $region->code_region }}</th>
                <td>{{ $report_effectif_st[$region->id]['total'] ?? 0 }}</td>
                <td>{{ $report_total_vacc_st[$region->id]['total'] ?? 0 }}</td>
                <td>{{ $report_cumul_st[$region->id]['total'] ?? 0 }}</td>
                <td>{{ $report_pourcentage_st[$region->id]['total'] ?? 0 }} %</td>
            </tr>
            @endforeach
            <tr>
                <th scope="row">Total</th>
                <td><strong>{{$total_effectif_st}}</strong></td>
                <td><strong>{{$total_vacc_st_regions}}</strong></td>
                <td><strong>{{$cumul_vacc_st_regions}}</strong></td>
                <td><strong>{{$pourcentage_st_regions}} %</strong></td>
            </tr>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>
