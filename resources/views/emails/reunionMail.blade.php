@component('mail::message')
<h4>Annonce d'une réunion</h4>
    <p>
        @if ($info['sexe'] == 'F')@if ($info['situation_familiale'] == 'C')Mademoiselle @else Madame @endif
        @else Monsieur @endif {{$info['nom']}} {{$info['prenom']}}, Une réunion du comité Ad-Hoc (COVID-19)
         de la division production aura lieu le {{$info['date_reunion']}} de {{$info['heure_deb']}} à
         {{$info['heure_fin']}}, au niveau de la salle de réunion N°175.
    </p>
    <hr>
    <h4>Objet de la réunion : </h4>
    <p>{{$info['objet']}}</p>
    <hr>
    <h4>Participants : </h4>
    <p>La réunion sera présidé par Mlle.{{auth()->user()->nom}} {{auth()->user()->prenom}}, président du
        comité Ad-Hoc. <br>
        Les membres invités sont :
        <ul>
            @foreach ($info['membres'] as $membre)
                <li>{{$membre->nom}} {{$membre->prenom}}, Membre du comité Ad-Hoc</li>
            @endforeach
        </ul>
    </p>
Merci,<br>
{{ config('app.name') }}
@endcomponent
