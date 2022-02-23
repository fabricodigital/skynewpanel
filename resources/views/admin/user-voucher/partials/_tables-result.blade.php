<div class="row">
    <div class="col-md-12">
        <h3>Risultati della ricerca</h3>
        <table class="table table-bordered table-striped">
            <tr>
                <th>NOME E COGNOME</th>
                <th>EMAIL</th>
                <th>TENURE ENTRA</th>
                <th>C.F.</th>
            </tr>
            <tr>
                <th>{{$uservouch->nome.' '.$uservouch->cognome }}</th>
                <th>{{$uservouch->email}}</th>
                <th> TEST </th>
                <th>{{$uservouch->codicefiscale}}</th>
            </tr>                    
        </table>
    </div>
</div>
@isset($getPromotions)
    <div class="row">
        <div class="col-md-12">
            <h3>Promozioni dedicate</h3>
            <table class="table table-bordered table-striped">
                <tr>
                    <th>OFFERTE DISPONIBILI</th>
                    <th>GENERA CODICE PROMOZIONE</th>
                    <th>CODICI GENERATI</th>
                    <th>PROMOZIONI UPSELLING</th>
                </tr>
                @foreach ($getPromotions as $promotion)
                    <tr>
                        <td>{{ $promotion->nome }}</td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                    </tr>
                @endforeach                    
            </table>
        </div>
    </div> 
@endisset
