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
        <strong>REPORTING <span style="text-transform: uppercase ;">{{$code}}</span> COVID-19 DU CMT-DP/Siège</strong>
    </div>
    <table class="table table-hover table-bordered">
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
            @if (count($consultations_siege) > 0)
                @foreach ($consultations_siege as $consultation_siege)
                    <tr>
                        <td>{{date('d-m-Y', strtotime($consultation_siege->date_consultation));}}</td>
                        @if (!empty($consultation_siege->sousTraitant_id))
                            <td>{{$consultation_siege->sousTraitant->nom}} {{$consultation_siege->sousTraitant->prenom}}</td>
                            <td>{{ $consultation_siege->sousTraitant->type}}</td>
                        @endif
                        @if (!empty($consultation_siege->organique_id))
                            <td>{{$consultation_siege->employeOrganique->nom}} {{$consultation_siege->employeOrganique->prenom}}</td>
                            <td>{{ $consultation_siege->employeOrganique->structure}}</td>
                        @endif
                        <td>
                            @if ($consultation_siege->symptomes == 'O')symptomatique @endif
                            @if ($consultation_siege->symptomes == 'N')asymptomatique @endif
                        </td>
                        <td>{{$consultation_siege->depistage->type_test}} {{$consultation_siege->depistage->resultat_test}}</td>
                        <td>
                            @if ($consultation_siege->modalités_priseEnCharge == 'BDV')Confinement Base de vie @endif
                            @if ($consultation_siege->modalités_priseEnCharge == 'D')Confinement Domicile @endif
                            @if ($consultation_siege->modalités_priseEnCharge == 'H')Hospitalisation @endif
                            @if ($consultation_siege->modalités_priseEnCharge == 'RT')Reprise de travail @endif
                        </td>
                    </tr>
                @endforeach
                <tr class="bg-light">
                    <th colspan="6">
                        Total Consultations : {{count($consultations_siege)}} <br>
                        Total Cas Positifs : {{$consultations_siege_pos}}
                    </th>
                </tr>
            @else
                <tr>
                    <td colspan="6">Rien à afficher.</td>
                </tr>
            @endif
        </tbody>
    </table>
    <div class="text-center bg-light py-2 mb-4"  style="page-break-before: always;">
        <strong>REPORTING <span style="text-transform: uppercase ;">{{$code}}</span> COVID-19 DU CMT-DP/Oued S'Mar</strong>
    </div>
    <table class="table table-hover table-bordered">
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
            @if (count($consultations_os) > 0)
                @foreach ($consultations_os as $consultation_os)
                    <tr>
                        <td>{{date('d-m-Y', strtotime($consultation_os->date_consultation));}}</td>
                        @if (!empty($consultation_os->sousTraitant_id))
                            <td>{{$consultation_os->sousTraitant->nom}} {{$consultation_os->sousTraitant->prenom}}</td>
                            <td>{{ $consultation_os->sousTraitant->type}}</td>
                        @endif
                        @if (!empty($consultation_os->organique_id))
                            <td>{{$consultation_os->employeOrganique->nom}} {{$consultation_os->employeOrganique->prenom}}</td>
                            <td>{{ $consultation_os->employeOrganique->structure}}</td>
                        @endif
                        <td>
                            @if ($consultation_os->symptomes == 'O')symptomatique @endif
                            @if ($consultation_os->symptomes == 'N')asymptomatique @endif
                        </td>
                        <td>{{$consultation_os->depistage->type_test}} {{$consultation_os->depistage->resultat_test}}</td>
                        <td>
                            @if ($consultation_os->modalités_priseEnCharge == 'BDV')Confinement Base de vie @endif
                            @if ($consultation_os->modalités_priseEnCharge == 'D')Confinement Domicile @endif
                            @if ($consultation_os->modalités_priseEnCharge == 'H')Hospitalisation @endif
                            @if ($consultation_os->modalités_priseEnCharge == 'RT')Reprise de travail @endif
                        </td>
                    </tr>
                @endforeach
                <tr class="bg-light">
                    <th colspan="6">
                        Total Consultations : {{count($consultations_os)}} <br>
                        Total Cas Positifs : {{$consultations_os_pos}}
                    </th>
                </tr>
            @else
                <tr>
                    <td colspan="6">Rien à afficher.</td>
                </tr>
            @endif
        </tbody>
    </table>
    <div class="text-center bg-light py-2 mb-4" style="page-break-before: always;">
        <strong>REPORTING <span style="text-transform: uppercase ;">{{$code}}</span> COVID-19 COVID-19 DU CMT-DP/Rue de Sahara</strong>
    </div>
    <table class="table table-hover table-bordered">
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
            @if (count($consultations_rds) > 0)
                @foreach ($consultations_rds as $consultation_rds)
                    <tr>
                        <td>{{date('d-m-Y', strtotime($consultation_rds->date_consultation));}}</td>
                        @if (!empty($consultation_rds->sousTraitant_id))
                            <td>{{$consultation_rds->sousTraitant->nom}} {{$consultation_rds->sousTraitant->prenom}}</td>
                            <td>{{ $consultation_rds->sousTraitant->type}}</td>
                        @endif
                        @if (!empty($consultation_rds->organique_id))
                            <td>{{$consultation_rds->employeOrganique->nom}} {{$consultation_rds->employeOrganique->prenom}}</td>
                            <td>{{ $consultation_rds->employeOrganique->structure}}</td>
                        @endif
                        <td>
                            @if ($consultation_rds->symptomes == 'O')symptomatique @endif
                            @if ($consultation_rds->symptomes == 'N')asymptomatique @endif
                        </td>
                        <td>{{$consultation_rds->depistage->type_test}} {{$consultation_rds->depistage->resultat_test}}</td>
                        <td>
                            @if ($consultation_rds->modalités_priseEnCharge == 'BDV')Confinement Base de vie @endif
                            @if ($consultation_rds->modalités_priseEnCharge == 'D')Confinement Domicile @endif
                            @if ($consultation_rds->modalités_priseEnCharge == 'H')Hospitalisation @endif
                            @if ($consultation_rds->modalités_priseEnCharge == 'RT')Reprise de travail @endif
                        </td>
                    </tr>
                @endforeach
                <tr class="bg-light">
                    <th colspan="6">
                        Total Consultations : {{count($consultations_rds)}} <br>
                        Total Cas Positifs : {{$consultations_rds_pos}}
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
