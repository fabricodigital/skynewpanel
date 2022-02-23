<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\UserVoucher;
use Illuminate\Support\Facades\DB;


class UserVoucherController extends Controller
{
    public function search()
    {
        return view('admin.user-voucher.index');
    }


    public function searchClient(Request $request)
    {
        $search = $request->get('infocode');
        if(!empty($search)) {
            $uservouch =  UserVoucher::where('idSky', '=', $search)->orWhere('codicefiscale', '=', $search)->first();

            $getPromotions = DB::connection('solopertedev')->select(
                "SELECT distinct sv.codice,u.lista,u.cluster,u.tempo_contratto,e.nome,tipologia_abbonamento_new.tipologia,u.`idSky`,u.promozione,pd.descrizione as checknewpromo, 
                            (select codice from codici where idSky=$uservouch->idSky and type = 14545  LIMIT 0,1) as checknewpromo3
                            
                            FROM `elencopromozioni` e
                            
                            INNER JOIN `utentivoucher` u ON e.`abbr` = u.`promozione` AND u.`idSky` = $uservouch->idSky
                            
                            left join promodescrizione pd ON e.id = pd.promozione
                            
                            left join stampavoucher sv on u.idSky = sv.idSky and sv.promozione = u.`promozione` and sv.prodotto=u.prodotto
                            
                            join tipologia_abbonamento_new  on u.prodotto = tipologia_abbonamento_new.id
                            
                            WHERE e.`attivoadmin` = 'si' AND NOW()>=e.datainizio and tipologia_abbonamento_new.datafine IS NULL  AND e.datafine >= NOW()  
                            
                            group by tipologia_abbonamento_new.id
                            
                            ORDER BY tipologia_abbonamento_new.posizione ASC,e.id desc,tipologia_abbonamento_new.nome asc"
            );

            // dd($getPromotions);
            return view('admin.user-voucher.index', compact('uservouch'));

        }
    }
}
