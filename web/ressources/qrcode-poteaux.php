<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <style>
body {
    margin: 0;
    padding: 0;
    background-color: #EEEEEE;
    font: 12pt "Tahoma";
}
* {
    box-sizing: border-box;
    -moz-box-sizing: border-box;
}
.page {
    width: 21cm;
    min-height: 29.7cm;
    padding: 0cm;
    margin: 1cm auto;
    border-radius: 5px;
    background: white;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}
.subpage {
    padding: 5mm;
    height: 297mm;
}
.label {
    padding: 5mm;
    height: 140mm;
}

@page {
    size: A4;
    margin: 0;
}
@media print {
      html, body {
        width: 210mm;
        height: 297mm;
      }
    .page {
        margin: 0;
        border: initial;
        border-radius: initial;
        width: initial;
        min-height: initial;
        box-shadow: initial;
        background: initial;
        page-break-after: always;
    }
}
    </style>
  </head>
  <body>
    <div class="book">
        <div class="page">
            <div class="subpage">
    <?php
        class sql
        {
            private $connexion_sql;
            
            function __construct()
            {
                $this->connexion_bdd = new PDO('odbc:Driver={SQL Server};Server=PROST\BDR_POUTRE;Database=KP1_BASE_CLIK; Uid=clik;Pwd=clik;');

                $this->connexion_bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            
            public function requete($requete)
            {
                $prepare = $this->connexion_bdd->prepare($requete);
                $prepare->execute();
                
                return $prepare;
            }
        }

        $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $parts = parse_url($url);
        parse_str($parts['query'], $params);
        if ($params['banc']=='') {
            $banc = '999999';
            print_r("<h4>Veuillez renseigner le n° du programme de fabrication en paramètre de l'adresse de la page web...</h4>");
        } else {
            $banc = $params['banc'];
        }
        
        $sql = new sql();
        
        $req = $sql->requete("select 
                                eof.IdEof,
                                e.Affaire + '-' + a.RefInterne + '/' + convert(varchar(2),(lof.NumLEof+1)) as idUnique,
                                a.LibTech,
                                convert(varchar(2),b.Fc28) as Fc28
                            from
                                clik.EOF eof
                                inner join clik.LOF lof on eof.IdEof = lof.IdEof
                                inner join dbo.SITE s on eof.IdSite = s.IdSite 
                                inner join clik.LCOMEXE le on lof.IdArtDem = le.IdArt
                                inner join clik.ARTICLE a on le.IdArt = a.IdArt
                                inner join dbo.BETON b on eof.IdArtBet = b.IdArtC
                                inner join clik.ECOMCLI e on le.IdComCli = e.IdComCli
                            where
                                e.Affaire = '3170368' and eof.IdSite = 32 and eof.IdEof = " . $banc . "
                            order by
                                lof.NumLEof");
    $i = 0;
    while ($r = $req->fetch())
    {
        $qrcode = $r[0].';'.$r[1].';'.$r[3];
        print_r('<div class="label"><table style="width:100%;height:130mm";vertical-align:middle;"><tr>');

        print_r('<td style="width:50%;text-align:center"><h3>'.$r[0].'&nbsp;&nbsp;&nbsp;&nbsp;'.$r[1].'</h3><h3>'.$r[3].'</h3><img style="width:75%;" src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl='.$qrcode.'&choe=UTF-8" title="'.$r[1].'" /><h3>'.$r[2].'</h3></td>');
        print_r('<td style="width:50%;text-align:center"><h3>'.$r[0].'&nbsp;&nbsp;&nbsp;&nbsp;'.$r[1].'</h3><h3>'.$r[3].'</h3><img style="width:75%;" src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl='.$qrcode.'&choe=UTF-8" title="'.$r[1].'" /><h3>'.$r[2].'</h3></td>');

        print_r('</tr></table></div>');

        $i = $i + 1;
        if ($i % 2 == 0) {
            print_r('</div></div><div class="page"><div class="subpage">');
        }
    }
?>
            </div>
        </div>
    </div>
	</body>
</html>
    
