<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Etat Nominatif COVID-19</title>
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
                Direction Gestion du Personnel <br>
                Département Gestion <br>
                Coordination Gestion Social <br> <br>
                Le : {{$date}}
            </td>
        </tr>
    </table>
    <div class="text-center bg-light py-2 mb-4">
        <strong>REPORTING <span style="text-transform: uppercase ;">{{$code}}</span> COVID-19 DU CMT-{{$code_cmt}}</strong>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Date consultation</th>
                <th scope="col">Nom et Prénom</th>
                <th scope="col">Structure/Type</th>
                <th scope="col">Symptomes</th>
                <th scope="col">Test de dépistage</th>
                <th scope="col">Modalités de prise en charge</th>
            </tr>
        </thead>
        <tbody>
            @if (count($consultations) > 0)
                @foreach ($consultations as $consultation)
                    <tr>
                        <td>{{date('d-m-Y', strtotime($consultation->date_consultation));}}</td>
                        @if (!empty($consultation->sousTraitant_id))
                            <td>{{$consultation->sousTraitant->nom}} {{$consultation->sousTraitant->prenom}}</td>
                            <td>{{ $consultation->sousTraitant->type}}</td>
                        @endif
                        @if (!empty($consultation->organique_id))
                            <td>{{$consultation->employeOrganique->nom}} {{$consultation->employeOrganique->prenom}}</td>
                            <td>{{ $consultation->employeOrganique->structure}}</td>
                        @endif
                        <td>
                            @if ($consultation->symptomes == 'O')symptomatique @endif
                            @if ($consultation->symptomes == 'N')asymptomatique @endif
                        </td>
                        <td>{{$consultation->depistage->type_test}} {{$consultation->depistage->resultat_test}}</td>
                        <td>
                            @if ($consultation->modalités_priseEnCharge == 'BDV')Confinement Base de vie @endif
                            @if ($consultation->modalités_priseEnCharge == 'D')Confinement Domicile @endif
                            @if ($consultation->modalités_priseEnCharge == 'H')Hospitalisation @endif
                            @if ($consultation->modalités_priseEnCharge == 'RT')Reprise de travail @endif
                        </td>
                    </tr>
                @endforeach
                <tr class="bg-light">
                    <th colspan="6">
                        Total Consultations : {{count($consultations)}} <br>
                        Total Cas Positifs : {{$consultations_pos}}
                    </th>
                </tr>
            @else
                <tr>
                    <td colspan="6">Rien à afficher.</td>
                </tr>
            @endif
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>
