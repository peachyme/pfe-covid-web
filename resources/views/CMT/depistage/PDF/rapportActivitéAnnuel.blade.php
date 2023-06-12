<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rapport d'activité</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style>
        table
        {
            font-size: 12px;
        }
        table.table-bordered > tbody > tr > th{
            border:1px solid #1a1a1a;
        }
        table.table-bordered > tbody > tr > td{
            border:1px solid #1a1a1a;
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
    <table class="table table-bordered text-center">
        <tr>
            <th rowspan="3"><br><br><br>Month</th>
            <th colspan="{{count($types_test)*2+2}}">Couverture Oragnique SH</th>
            <th colspan="{{count($types_test)*2+2}}">Couverture Sous-Traitants</th>
        </tr>
        <tr>
            @foreach ($types_test as $type_test)
            <th colspan="2">{{ $type_test }}</th>
            @endforeach
            <th rowspan="2"><br>Total dépisté</th>
            <th rowspan="2"><br>Total (+)</th>
            @foreach ($types_test as $type_test)
                <th colspan="2">{{ $type_test }}</th>
            @endforeach
            <th rowspan="2"><br>Total dépisté</th>
            <th rowspan="2"><br>Total (+)</th>
        </tr>
        <tr class="divide">
            @foreach ($types_test as $type_test)
                <th>Nbr dépisté</th>
                <th>Nbr (+)</th>
            @endforeach
            @foreach ($types_test as $type_test)
                <th>Nbr dépisté</th>
                <th>Nbr (+)</th>
            @endforeach
        </tr>
        @foreach ($months as $month)
            <tr>
                <td><span style="text-transform: uppercase ;"><strong>{{ \Carbon\Carbon::parse($month)->translatedFormat('F') }}</strong></span></td>
                @foreach ($couvertures as $couverture)
                    @foreach ($types_test as $type_test)
                        <td>{{ $report[$month][$type_test][$couverture]['depistage'] ?? '0' }}</td>
                        <td>{{ $report_positif[$month][$type_test][$couverture]['depistage_positif'] ?? '0' }}</td>
                    @endforeach
                    <td><strong>{{ $report_total[$month][$couverture]['total'] ?? '0' }}</strong></td>
                    <td><strong>{{ $report_total_positif[$month][$couverture]['total_positif'] ?? '0' }}</strong></td>
                @endforeach
            </tr>
        @endforeach
    </table>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>
