@component('mail::message')
<h4>Réunion annulée</h4>
    <p>
        @if ($info['sexe'] == 'F')@if ($info['situation_familiale'] == 'C')Mademoiselle @else Madame @endif
        @else Monsieur @endif {{$info['nom']}} {{$info['prenom']}}, Nous vous informons que la réunion du comité Ad-Hoc (COVID-19) de la division production prévu le {{$info['date_reunion']}} de {{$info['heure_deb']}} à
         {{$info['heure_fin']}}, au niveau de la salle de réunion N°175 est annulée.
    </p>
Merci,<br>
{{ config('app.name') }}
@endcomponent
