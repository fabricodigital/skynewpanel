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
                @php
                    $i = 1;
                @endphp
                @foreach ($getPromotions as $promotion)
                    <tr>
                        <td>{{ $promotion->nome }}</td>
                        <td>
                            <a href="#" id="pop" data-toggle="modal" data-target="#myModal-{{$i}}">
                                <img id="imageresource" src="https://cdn2.iconfinder.com/data/icons/pointed-edge-web-navigation/130/tick-green-512.png" style="width: 50px; height: 50px;">
                            </a>
                            <!-- Modal window -->
                            <div class="modal fade" id="myModal-{{$i++}}" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">{{ $promotion->nome }}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Lista: {{ $promotion->lista }}</p>
                                            <p>Tempo Contratto: {{ $promotion->tempo_contratto }}</p>
                                            <p>Promozione: {{ $promotion->promozione }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td> - </td>
                        <td> - </td>
                    </tr>
                @endforeach                    
            </table>
        </div>
    </div> 
@endisset
