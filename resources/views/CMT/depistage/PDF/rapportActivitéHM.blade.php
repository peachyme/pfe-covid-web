<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rapport d'activité</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style>
        table {
            font-size: 12px;
        }

        table.table-bordered>tbody>tr>th {
            border: 1px solid #1a1a1a;
        }

        table.table-bordered>tbody>tr>td {
            border: 1px solid #1a1a1a;
        }
    </style>

<body>
    <table width="100%" class="mb-4">
        <tr>
            <td valign="top">
                <span><img src="{{ public_path('/images/logo.png') }}" alt="" width="80" height="120"></span>
            </td>
            <td align="right" class="px-3" style="line-height: 150%">
                Activité Exploration-Production <br>
                Division Production <br>
                Direction Gestion du Personnel <br>
                Département Gestion <br>
                Coordination Gestion Social <br> <br>
                Le : {{$date}}
            </td>
        </tr>
    </table>
    <div class="text-center bg-light py-2 mb-4">
        <strong>RAPPORT D'ACTIVITE <span style="text-transform: uppercase ;">{{$code}}</span> DU CMT-{{$code_cmt}}</strong>
    </div>
    <table class="table table-bordered text-center mb-4">
        <tr>
            <th colspan="8">Couverture Oragnique SH</th>
        </tr>
        <tr>
            <th colspan="2">PCR</th>
            <th colspan="2">Antigénique</th>
            <th colspan="2">Sérologie</th>
            <th rowspan="2" colspan="col" class="pt-3">TOTAL <br> dépisté</th>
            <th rowspan="2" colspan="col" class="pt-3">TOTAL <br> (+)</th>
        </tr>
        <tr class="divide">
            <th>Dépistage</th>
            <th>Nbr (+)</th>
            <th>Dépistage</th>
            <th>Nbr (+)</th>
            <th>Dépistage</th>
            <th>Nbr (+)</th>
        </tr>
        <tr class="divide">
            <td>{{ $report_depistage['O']['PCR']['total'] ?? '0' }}</td>
            <td>{{ $report_depistage_pos['O']['PCR']['total'] ?? '0'  }}</td>
            <td>{{ $report_depistage['O']['Antigénique']['total'] ?? '0'  }}</td>
            <td>{{ $report_depistage_pos['O']['Antigénique']['total'] ?? '0'  }}</td>
            <td>{{ $report_depistage['O']['Sérologique']['total'] ?? '0'  }}</td>
            <td>{{ $report_depistage_pos['O']['Sérologique']['total'] ?? '0'  }}</td>
            <td>{{ $report_depistage_total['O']['total'] ?? '0'  }}</td>
            <td>{{ $report_depistage_pos_total['O']['total'] ?? '0'  }}</td>
        </tr>
    </table>
    <table class="table table-bordered text-center mb-0">
        <tr>
            <th colspan="8">Couverture Sous-Traitants</th>
        </tr>
        <tr>
            <th colspan="2">PCR</th>
            <th colspan="2">Antigénique</th>
            <th colspan="2">Scanner</th>
            <th rowspan="2" colspan="col" class="pt-3">TOTAL <br> dépisté</th>
            <th rowspan="2" colspan="col" class="pt-3">TOTAL <br> (+)</th>
        </tr>
        <tr class="divide">
            <th>Dépistage</th>
            <th>Nbr (+)</th>
            <th>Dépistage</th>
            <th>Nbr (+)</th>
            <th>Dépistage</th>
            <th>Nbr (+)</th>
        </tr>
        <tr class="divide">
            <td>{{ $report_depistage['ST']['PCR']['total'] ?? '0'  }}</td>
            <td>{{ $report_depistage_pos['ST']['PCR']['total'] ?? '0'  }}</td>
            <td>{{ $report_depistage['ST']['Antigénique']['total']['total'] ?? '0'  }}</td>
            <td>{{ $report_depistage_pos['ST']['Antigénique']['total'] ?? '0'  }}</td>
            <td>{{ $report_depistage['ST']['Sérologique']['total'] ?? '0'  }}</td>
            <td>{{ $report_depistage_pos['ST']['Sérologique']['total'] ?? '0'  }}</td>
            <td>{{ $report_depistage_total['ST']['total'] ?? '0'  }}</td>
            <td>{{ $report_depistage_pos_total['ST']['total'] ?? '0'  }}</td>
        </tr>
    </table>
</body>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
