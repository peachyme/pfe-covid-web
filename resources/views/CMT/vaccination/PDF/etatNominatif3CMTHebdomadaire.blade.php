<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Etat Nominatif Vaccination 3CMT</title>
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
        <strong>REPORTING <span style="text-transform: uppercase ;">{{$code}}</span> VACCINATION COVID-19 DU CMT-DP/Siège</strong>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Date Vaccination</th>
                <th scope="col">Nom et Prénom</th>
                <th scope="col">Structure/Type</th>
                <th scope="col">Dose</th>
                <th scope="col">Type vaccin</th>
            </tr>
        </thead>
        <tbody>
            @if (count($vaccinations_siege) > 0)
                @foreach ($vaccinations_siege as $vaccination_siege)
                    <tr>
                        <td>{{date('d-m-Y', strtotime($vaccination_siege->date_vaccination));}}</td>
                        @if (!empty($vaccination_siege->sousTraitant_id))
                            <td>{{$vaccination_siege->sousTraitant->nom}} {{$vaccination_siege->sousTraitant->prenom}}</td>
                            <td>{{ $vaccination_siege->sousTraitant->type}}</td>
                        @endif
                        @if (!empty($vaccination_siege->organique_id))
                            <td>{{$vaccination_siege->employeOrganique->nom}} {{$vaccination_siege->employeOrganique->prenom}}</td>
                            <td>{{ $vaccination_siege->employeOrganique->structure}}</td>
                        @endif
                        <td>
                            @if ($vaccination_siege->dose_vaccination == '1') 1 <sup>ère</sup> @endif
                            @if ($vaccination_siege->dose_vaccination == '2') 2 <sup>ème</sup> @endif
                        </td>
                        <td>{{$vaccination_siege->type_vaccin}}</td>
                    </tr>
                @endforeach
                <tr class="bg-light">
                    <th colspan="5">Total vaccinés : {{count($vaccinations_siege)}}</th>
                </tr>
            @else
                <tr>
                    <td colspan="5">Rien à afficher.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="text-center bg-light py-2 mb-4"  style="page-break-before: always;">
        <strong>REPORTING <span style="text-transform: uppercase ;">{{$code}}</span> VACCINATION COVID-19 DU CMT-DP/Oued S'Mar</strong>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Date Vaccination</th>
                <th scope="col">Nom et Prénom</th>
                <th scope="col">Structure/Type</th>
                <th scope="col">Dose</th>
                <th scope="col">Type vaccin</th>
            </tr>
        </thead>
        <tbody>
            @if (count($vaccinations_os) > 0)
                @foreach ($vaccinations_os as $vaccination_os)
                    <tr>
                        <td>{{date('d-m-Y', strtotime($vaccination_os->date_vaccination));}}</td>
                        @if (!empty($vaccination_os->sousTraitant_id))
                            <td>{{$vaccination_os->sousTraitant->nom}} {{$vaccination_os->sousTraitant->prenom}}</td>
                            <td>{{ $vaccination_os->sousTraitant->type}}</td>
                        @endif
                        @if (!empty($vaccination_os->organique_id))
                            <td>{{$vaccination_os->employeOrganique->nom}} {{$vaccination_os->employeOrganique->prenom}}</td>
                            <td>{{ $vaccination_os->employeOrganique->structure}}</td>
                        @endif
                        <td>
                            @if ($vaccination_os->dose_vaccination == '1') 1 <sup>ère</sup> @endif
                            @if ($vaccination_os->dose_vaccination == '2') 2 <sup>ème</sup> @endif
                        </td>
                        <td>{{$vaccination_os->type_vaccin}}</td>
                    </tr>
                @endforeach
                <tr class="bg-light">
                    <th colspan="5">Total vaccinés : {{count($vaccinations_os)}}</th>
                </tr>
            @else
                <tr>
                    <td colspan="5">Rien à afficher.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="text-center bg-light py-2 mb-4"  style="page-break-before: always;">
        <strong>REPORTING <span style="text-transform: uppercase ;">{{$code}}</span> VACCINATION COVID-19 DU CMT-DP/Rue de Sahara</strong>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Date Vaccination</th>
                <th scope="col">Nom et Prénom</th>
                <th scope="col">Structure/Type</th>
                <th scope="col">Dose</th>
                <th scope="col">Type vaccin</th>
            </tr>
        </thead>
        <tbody>
            @if (count($vaccinations_rds) > 0)
                @foreach ($vaccinations_rds as $vaccination_rds)
                    <tr>
                        <td>{{date('d-m-Y', strtotime($vaccination_rds->date_vaccination));}}</td>
                        @if (!empty($vaccination_rds->sousTraitant_id))
                            <td>{{$vaccination_rds->sousTraitant->nom}} {{$vaccination_rds->sousTraitant->prenom}}</td>
                            <td>{{ $vaccination_rds->sousTraitant->type}}</td>
                        @endif
                        @if (!empty($vaccination_rds->organique_id))
                            <td>{{$vaccination_rds->employeOrganique->nom}} {{$vaccination_rds->employeOrganique->prenom}}</td>
                            <td>{{ $vaccination_rds->employeOrganique->structure}}</td>
                        @endif
                        <td>
                            @if ($vaccination_rds->dose_vaccination == '1') 1 <sup>ère</sup> @endif
                            @if ($vaccination_rds->dose_vaccination == '2') 2 <sup>ème</sup> @endif
                        </td>
                        <td>{{$vaccination_rds->type_vaccin}}</td>
                    </tr>
                @endforeach
                <tr class="bg-light">
                    <th colspan="5">Total vaccinés : {{count($vaccinations_rds)}}</th>
                </tr>
            @else
                <tr>
                    <td colspan="5">Rien à afficher.</td>
                </tr>
            @endif
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>
