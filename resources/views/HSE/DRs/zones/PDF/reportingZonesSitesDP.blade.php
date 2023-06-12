<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>REPORTING SITES DP COVID-19</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

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
                <span><img src="{{ public_path('/images/logo.png') }}" alt="" width="80"
                        height="120"></span>
            </td>
            <td align="right" class="px-3" style="line-height: 150%">
                Activité Exploration-Production <br>
                Division Production <br>
                Département HSE <br>
                Activité Prévention et Santé <br> <br>
                Le : {{ $date }}
            </td>
        </tr>
    </table>
    <div class="text-center bg-light py-3 mb-4">
        <strong><span style="text-transform: uppercase ;">Reporting de la situation actuelle des zones de confinement
                des sites DP</span></strong>
    </div>
    @foreach ($regions as $region)
        <table class="table table-hover table-bordered text-center"  style="page-break-before: always;">
            <tr>
                <th scope="col" colspan="5">{{ $region->code_region }}</th>
            </tr>
            <tr>
                <th scope="col" colspan="5">Nombre de zones de confinement du site</th>
            </tr>
            <tr>
                <td scope="col" colspan="5">{{ $region->zones()->count() }} zones de confinement</td>
            </tr>
            <tr>
                <th scope="col" colspan="5">Capacité des zones de confinement</th>
            </tr>
            @foreach ($region->zones as $zone)
                <tr>
                    <th>{{ $zone->libelle_zone }}</th>
                    <td colspan="4">{{ $zone->capacité_zone }}</td>
                </tr>
            @endforeach
            <tr>
                <th scope="col" colspan="5">Responsables des zones de confinement</th>
            </tr>
            @foreach ($region->zones as $zone)
                <tr>
                    <th>{{ $zone->libelle_zone }}</th>
                    <td colspan="4">{{ $zone->employeOrganique->nom }} {{ $zone->employeOrganique->prenom }}</td>
                </tr>
            @endforeach
            <tr>
                <th scope="col" colspan="5">Effectif opérationnel du personnel médical</th>
            </tr>
            @foreach ($region->zones as $zone)
                <tr>
                    <th>{{ $zone->libelle_zone }}</th>
                    <th>Médecins</th>
                    <td>{{ $zone->effectif_medecins }}</td>
                    <th>Infermiers</th>
                    <td>{{ $zone->effectif_infermiers }}</td>
                </tr>
            @endforeach
        </table>
    @endforeach
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
</body>
