@component('mail::message')
<h4>Réunion modifiée</h4>
    <p>
        @if ($info_nv['sexe'] == 'F')@if ($info_nv['situation_familiale'] == 'C')Mademoiselle @else Madame @endif
        @else Monsieur @endif {{$info_nv['nom']}} {{$info_nv['prenom']}}, Nous vous informons que la réunion du comité Ad-Hoc (COVID-19) de la division production prévu le {{$info['date_reunion']}} de
        {{$info['heure_deb']}} à {{$info['heure_fin']}}, au niveau de la salle de réunion N°175 à été modifiée comme suit :
    </p>
    <hr>
    <h4>Objet de la réunion : </h4>
    <p>{{$info_nv['objet']}}</p>
    <hr>
    <h4>Date réunion : {{$info_nv['date_reunion']}}</h4>
    <hr>
    <h4>Heure début réunion : {{$info_nv['heure_deb']}}</h4>
    <hr>
    <h4>Heure fin réunion : {{$info_nv['heure_fin']}}</h4>
    <hr>
    <h4>Participants : </h4>
    <p>La réunion sera présidé par Mlle.{{auth()->user()->nom}} {{auth()->user()->prenom}}, président du comité Ad-Hoc. <br>
        Les membres invités sont :
        <ul>
            @foreach ($info_nv['membres'] as $membre)
                <li>{{$membre->nom}} {{$membre->prenom}}, Membre du comité Ad-Hoc</li>
            @endforeach
        </ul>
    </p>
Merci,<br>
{{ config('app.name') }}
@endcomponent
