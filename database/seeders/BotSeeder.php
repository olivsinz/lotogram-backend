<?php

namespace Database\Seeders;

use App\Enum\UserType;
use App\Models\Title;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ini_set('memory_limit', '1024M');

        // 5000 bot users

        $usernames = ["skulled", "3bahar", "adrianmutu44", "Galir35", "Furkan_1907_yavuz", "Deliceriq", "blntcskn", "osmn67600", "MehmetYilmaz", "robertomy", "demhatdemhat99", "1000gol", "Mahmut38", "Erzurum2575", "Efe1111", "Huseyin007bond", "serdalturk1985", "FreeCh", "Aykutaa57", "Webamca", "Sezz38", "Oktay2121", "celiko31", "ahm1071", "yasemintm", "Enderrrr", "notingham34", "Farukbey84", "Arif", "ahmettt888", "AliEkberCesim", "olcaysndkc", "Nzsk1010", "Furkangcr1", "sirmetal1907", "emrah86", "2104ssss2104", "seroo", "Voice", "huda2009", "arsi1907", "Redlabell", "mabolla", "abdurrahmankara", "Oktay1975", "mehmetoglu", "Holegos", "Sezgin7867", "MehmetUnal", "imperyal", "Goztepeli91", "Ramazantan", "Oduncu06", "volkanmucu6", "Cobra191", "ahmetGuzeller", "mahon82", "Aytac1071", "lases46", "Nurcink", "aliriza3016", "Thraki22", "yusufnyr", "Ayzselin", "Recep", "mevlut92", "Falcata06", "kerimalbayrak", "MMSTAFA14", "yeteneklisamet", "burak.onder87", "dodocom", "1236eren1236", "mht7474", "batix", "Ali272723", "Saglam43", "AytacAtakan", "HasanGeyik", "Ykpyksl55", "Sahin6117", "sevgi", "osman.osmanli4242", "Askeri04", "hayta7116", "qraliceli", "Nesta1010", "Seto45", "Canomer42", "Leylazehra", "gokdenizkoc", "Ysrkrc", "Birhat45454521", "ozcancakal0", "miscallenous", "Bensucan", "flatron5", "ferhat000", "Yl1995mz", "mehmetdevrim", "Acars.73200", "pedalizahc", "Yunus1047", "gecegozlum", "cenkkarahan", "ademdemir64", "Sofular20", "Ea19071995", "Kayahan3", "Koksalomer0640", "Ahmetyykaya", "MUHAMMEDBARANUMIT", "beldenkirma3535", "Celo44", "Atakanaydn88", "puzzlezine", "yusufatik", "juve10", "salihygn", "BK4378402", "mhmet03", "Yasireis07", "Parvenist39", "palaabdibaba46", "oaoaoa355", "ridvanakis33", "hazarortk1", "mak0619", "sertanoyun1540", "mstfyrlmz07", "Demoo", "cardbook16", "Orhan68", "Omermert75", "Anlozkn7", "soldatgaming", "Blctrk", "Esmanur2209", "Sinop4375", "zeronimo35", "bfmetin", "aizenabys", "Dln2756", "erdemdemirsoy_57", "CEKOKO", "mercan27", "Akfatih", "Cina4241", "Eren6767", "ibocan192014", "Serkantro", "mali27", "Hsnlubas", "serdardevran", "Gscihat45", "Motorcu3737", "Ctobdr", "muralperen", "Bedir19", "Seherefe", "Ezgicinaar", "erso45", "Zeus3428", "antepadana", "ibrahimhakki90", "mert0011", "azizoglu65", "Sedatccs", "Enes496", "mcgayver", "karabela612", "atilla555", "emirbalta1967", "aliemreyasar", "Ens6161", "Glshshsh", "Modena", "Omerknk5858", "Feridehavli", "Mine88", "demircix", "Gakemba7", "mertock1", "ortak1234", "Nihos", "Maperten", "Mdemir", "Kubra39", "Emre_7398", "apoday_38", "hlm1414", "ozavarufuk", "exempt_paed", "bypatik1", "yigitersarii", "jammerhammer1453", "gmzorhn", "mevlutarabaci44", "emre0636123", "Serdin2252", "Nsrna", "Umutcank", "husi_44", "Arif191919", "Anil6792", "atis_zeze", "ibrahimars12", "Mustafa", "Ykptmknr", "sempatik44", "ali5310kilic", "haciturgut63", "conqueror5922", "omererensayin147", "EvrenAkay", "OmerCaglin", "EmrahKoc", "burakqw2", "Halil1666", "gogo42", "Canguneri", "gokhannar17", "QuZGuN22", "Abdurrahman28", "ersin608", "Mkurkun01", "Crazymekan10", "Ud6116", "sonerbaytar14613561", "Alfonso1953", "krdmn645", "brkztrk25", "phyton", "Manhaime", "gitarist52", "kaya59316", "Zeze8080", "mehmetaliatak1035", "Kasap8439", "Mukerrem08800", "babatsavass", "mesigyna25", "Tazmanya23", "epikk", "emreeoy", "ekolak", "Bankobeyi25", "reis24", "M44s55", "Frknn21", "BaharRezanYildirim", "Bircankarahan", "EnesDuran", "Evren2259", "serdarucgurz", "beratmlt", "erencetinsoy6", "rahim6060", "behiye12", "mucahitksknn", "Akrep2222", "ayazyaso", "Muhamedfg", "abdullahakkoyun81", "deadnuker00", "Viktoryus", "Forger", "35tilki353", "sahin345", "mrtkylp56", "Cengiz958", "tark4747", "Humman123", "Salih_37", "VE45li35", "falcao44", "okanaldatmis006", "Etkin5922", "bymhmet", "alper01k1", "enesselber", "Zeki0001_", "cagrikcb", "bozdemir044", "SCARFACE79", "HULK79", "FurkanOzkalkan", "Samet0708", "Ugur1435", "SametUsta", "ufukhmrc28", "Halil", "Ryuneo", "Crayz01", "ardapasha13", "Sonerwhite", "Lapielo02", "Ahtapot717135", "Butig7575", "SadikSenol", "Baran1579", "Aykut3675", "Ramazi54", "Ufuk0541", "Yusufulger7272", "Fuat78", "siyambjk", "Bensizhh", "deryakrtt", "Vys123", "Htl67", "tamturk0180", "ramazan63", "apranax17", "Ramazan113", "Cekufrench34", "cceemm", "Sifoncu59", "Sibel1992", "ErzurumluBoys", "Sopinarso", "bekcan66", "huseyinayaz", "Sahanoglu35", "apsekur", "erenselcuk16", "Rossi4545", "Rcp1212", "ertanergin9", "Beyefendy", "baris3465", "Ravza5454", "Dodo14", "Salihli45", "tgrl58", "emircemir", "Tugi006", "Srdgn", "fatiho1907", "unalaykut0", "av.serhat.yildirim", "isoo87", "Cinopi19", "muhammedbaran", "Demireli", "Ahmetcan2424", "Basarr35", "cihan353535", "tuncay", "Arif2834", "fabregas1453", "Evrensel60", "Totti19051905", "MikdatArafat", "cemilkahraman1978", "Barisxan001", "Abdulsametyilmaz2525", "Efe11111", "ayhan56", "Zelis1986", "kadirakbas", "fatihhsb", "Tunahan2020", "BahadiremreGuler", "slckygn", "tunahan7070", "ozancanbay4457", "carpar1907", "halilkaygusuz82", "seckinyurtseven", "COSKUN71", "can5459117289", "abdrrhmn.909192", "vasconunez", "Hitaf1903", "sametgel", "Albayrakcr0118", "yucel01", "cwuye", "karaca006", "Ugurtaskinfurat", "Habibbilek", "Hakan5335", "senerabdoo83", "Sanzar35", "Beno9911", "Ativacom", "Smlzgn010", "Semih1020", "dgndgr1", "senfonii", "hakanbdn", "arfylm2", "Batuhan4455", "Bahis01", "hyrtndmr", "mrtagcabay", "Nurada53", "Batu1231", "Hknyksk", "protogores", "Handan01", "piyanist468", "hakki2816", "Kadir3525", "okan01", "reis18", "samedbaba17", "Melih412006", "Zlaty", "Eraytamer", "ozqahmt07", "Cumhaks", "Orhan.71.71", "kerem0910", "Farukusta123", "selenolgun21", "BarisSerdar", "fatihkilicoglu", "ugurcan", "HayrettinSancar", "Wural85", "Sefa677", "veysel37", "Tekeerdem", "Sadettin", "Shaq", "Bertovski", "yagiz20", "Emrebykbgci42", "Kelokan", "muratk5589", "Ahmed24", "enescan1", "Manitou", "lectra", "Ckkorkmaz", "yunus3131", "emirhan6145", "TarikKilicarslan", "Tuna3469", "Newzo1717", "Ert021", "erdemkucuk05", "Nese5252", "Kaynakk01", "Mami3540", "Ferhatozden", "Deryaarpaz", "olaola34", "Hkngzl", "tolusuko", "mustafadurgut910", "yildizalican", "Sonsuz55", "yasarok", "serkansunal43", "koc75", "Arnvt09", "Cengiz008", "Kivi54", "geovanni", "ugur5252", "anilfidan", "BatuhanCanbolat", "Mkeskin61", "SertacAkman", "Kadife55", "halilcanbuyuk", "temurosman5871", "Bababa00", "sananelan112233112", "Melih9809", "ogorkem207", "mert456", "3967544ag", "ahmetcan5569", "Tiego66", "rubar65", "Bekex", "UmutOzz", "Okannsezer", "Ormi", "Mazlum", "Pascal1905", "babacancanan71", "alper87alper", "aras0438", "bekozinn", "tekinaktas38", "Egemir", "Kornamm", "nisali.efe.06", "beno.9911", "janyabj", "Serhatoymak", "Sumo35", "DidemErk86", "Emrahyk", "dekici", "Levo28", "Ebraremrekeskin", "Tkznc", "ERblgn", "ssonggul", "Aylak69", "coskunboya", "rebell1ous26", "2107166897", "HY001", "kayrakkk", "smlzgn010", "ozkanemre3519", "maf-lover", "Nejatkrmz", "Levoo22", "seben63", "Ragnarok13", "fblackeagle", "nakre10", "winergk", "Murokara05", "Semih2239", "Nazarioserkan7", "Anyon13", "Mehmet1346", "Abdulbari", "Klc0808", "alp123can", "ahmet8938", "OZGURBABA", "esmer8888", "eminekarakullukcu777", "MehmetKurter", "abdullah0614", "Poyrazakman", "RecepYILDIZ", "Muratsahinss", "Ibram17", "Bekexx", "Rencex", "Aytek608", "erengroup99", "Eren09", "Secopp2234", "halil_krn999", "Jhonny", "Enokar", "fsi_28", "gamzeli5", "AmeDLi", "emrealtn60", "frknnn", "Bozgen48", "robot7441", "emrekrmndev", "Omer145307", "Proff007", "mehmetcan4999", "Ceyhunn2210", "dilek3436", "serkoc123", "Tunci47", "oguzz1453", "hidayetakgun0063", "Enesemir", "cezali", "Gkhns032", "Mustafakk12", "TubaA", "mete01", "Doruk111", "tekin_20_87", "Bahis11", "onur900", "Plmng258", "Shadowfurkan", "sabrioduncu06", "babas35921638", "Nazan1658", "gavuz5361", "Lazkopat1628", "Degirmenci", "haydut0303", "Kehribar4871", "farukdlk", "Komd_ozgur86", "Comofoko", "Muhittin4747", "Burak2219", "Hasan1907", "ladre", "Barkintelo", "yakamoz6767", "OLCAYsss", "Tugce", "MehmetCanTokur", "OzgurCanEce", "harunacarr16", "bcipa11", "Cancan1233", "Dgneyyup93"];

        foreach ($usernames as $username)
        {
            $userData[] = [
                'uuid' => Str::uuid(),
                'first_name' => $username,
                'last_name' => $username,
                'username' => $username,
                'email' => $username . '@local',
                'type' => UserType::Bot,
                'password' => 'x',
                'email_verified_at' => now(),
                'password_change_required' => false,
                'created_at' => now(),
                'updated_at' => now(),
                'language' => 1,
            ];
        }

        User::insert($userData);

    }
}
