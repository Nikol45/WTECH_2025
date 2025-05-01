<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $descriptions = [
            // Ovocie
            'Evelina' => 'Odroda jablka s jemne sladkou chuťou, ideálna na konzum aj čerstvý mošt.',
            'Gala' => 'Chrumkavé jablko s šťavnatou dužinou, ideálne pre deti.',
            'Golden Delicious' => 'Klasika medzi jablkami – sladké, šťavnaté a univerzálne použiteľné.',
            'Granny Smith' => 'Kyselkavé zelené jablko vhodné na koláče aj do šalátov.',
            'Red Delicious' => 'Veľmi sladké jablko s hladkou červenou šupkou a krásnym vzhľadom.',
            'Pink Lady' => 'Moderná odroda s osviežujúcou chuťou a výbornou skladovateľnosťou.',
            'Hrušky' => 'Šťavnaté a sladké hrušky dozrievajúce na slnku bez chemického ošetrenia.',
            'Broskyne' => 'Sladké, voňavé broskyne vhodné na džem aj priamy konzum.',
            'Čerešne' => 'Tmavé čerešne plné chuti, výborné na kompót aj do koláčov.',
            'Černice' => 'Lesné černice z našich kríkov – šťavnaté a zdravé.',
            'Čučoriedky' => 'Ručne zbierané čučoriedky vhodné do koláčov aj mrazničky.',
            'Ružové' => 'Jemne kyselkavé egreše vhodné na džem alebo sirup.',
            'Zelené' => 'Svieže egreše na koláče a zaváranie.',
            'Figy' => 'Dozrievajú prirodzene, sladké a plné energie.',
            'Granátové jablká' => 'Exotické ovocie bohaté na antioxidanty, pestované v skleníku.',
            'Hrášok' => 'Mladý hrášok zo záhrady, sladký a chrumkavý.',
            'Zelené' => 'Zelené hrozno bez kôstok, sladké a šťavnaté.',
            'Ružové' => 'Ružové hrozno s jemnou chuťou, ideálne na stôl.',
            'Modré' => 'Modré hrozno vhodné na mušt a víno.',
            'Jahody' => 'Sladké jahody zo slnečnej strany záhrady.',
            'Kiwi' => 'Pestované pod fóliou, s jemnou chuťou a veľa vitamínmi.',
            'Maliny' => 'Plné chuti a vitamínu C, vhodné aj na mrazenie.',
            'Marhule' => 'Vyzreté na strome, ideálne na lekvár.',
            'Melóny' => 'Sladké a šťavnaté, pestované v teplom prostredí.',
            'Čierne' => 'Čierne ríbezle plné vitamínu C a antioxidantov.',
            'Červené' => 'Červené ríbezle do koláčov aj na šťavu.',
            'Slivky' => 'Slivky dozreté na strome, ideálne na povidly.',

            // Zelenina
            'Mozart' => 'Zemiaky s hladkou šupkou, ideálne na varenie.',
            'Sunita' => 'Varný typ zemiakov s pevným vnútrom.',
            'Agria' => 'Veľmi vhodná odroda na pečenie aj hranolky.',
            'Cibuľa' => 'Cibuľa s výraznou chuťou, pestovaná bez postrekov.',
            'Mrkva' => 'Naša mrkva rastie v piesočnatých pôdach a je prirodzene sladká a chrumkavá.',
            'Tekvica' => 'Muškátová tekvica s jemnou orechovou chuťou.',
            'Reďkovky' => 'Chrumkavé reďkovky z jarného výsevu.',
            'Červené' => 'Sladká červená paprika vhodná na plnenie aj do leča.',
            'Žlté' => 'Jemná žltá paprika do šalátov.',
            'Zelené' => 'Zelená paprika s výraznou arómou.',
            'Cherry' => 'Malé paradajky s intenzívnou chuťou.',
            'Roma' => 'Mäsité paradajky ideálne na pretlak.',
            'Býčie srdce' => 'Veľké paradajky vhodné na krájanie a do šalátov.',
            'Šalátové' => 'Uhorky pestované bez chémie, vhodné na čerstvý konzum.',
            'Kyslé' => 'Menšie uhorky určené na zaváranie.',

            // Mliečne výrobky
            'Kravské' => 'Čerstvé mlieko od kráv z voľného chovu.',
            'Kozie' => 'Mlieko s jemnou chuťou z domáceho chovu kôz.',
            'Ovčie' => 'Ovčie mlieko vhodné na výrobu syra.',
            'Vajcia' => 'Vajcia od sliepok z voľného výbehu.',
            'Syr' => 'Polotvrdý syr z nášho mlieka, zrejúci 4 týždne.',
            'Jogurt' => 'Domáci jogurt z kravského mlieka, fermentovaný bez prídavných látok.',
            'Tvaroh' => 'Ručne vyrábaný tvaroh s jemnou konzistenciou a plnou chuťou.',

            // Mäsové výrobky
            'Prsia' => 'Kuracie prsia z voľného chovu.',
            'Stehná' => 'Kuracie stehná šťavnaté a mäkké.',
            'Sviečkovica' => 'Hovädzia sviečkovica vhodná na minútky.',
            'Steaky' => 'Steaky z mladého býčka, jemné a šťavnaté.',
            'Bravčové mäso' => 'Poctivé bravčové z domácich podmienok.',

            // Pečivo a obilniny
            'Pšeničná' => 'Múka z pšeničného zrna vhodná na pečenie.',
            'Špaldová' => 'Celozrnná špaldová múka s orechovou chuťou.',
            'Ražná' => 'Ražná múka vhodná na kváskový chlieb.',
            'Kváskový' => 'Kváskový chlieb pečený v hlinenej peci.',
            'Ražný' => 'Tmavší chlieb s výraznou chuťou.',
            'Bagety' => 'Bagety z pšeničnej múky, chrumkavé a voňavé.',

            // Domáce nápoje
            'Pomarančový' => 'Džús z čerstvých pomarančov.',
            'Jablkový' => 'Jablkový džús lisovaný za studena.',
            'Broskyňový' => 'Sladký broskyňový džús vhodný pre deti.',
            'Bazový' => 'Sirup z ručne zbieraných kvetov bazy, sladený trstinovým cukrom.',
            'Malinový' => 'Sirup zo sladkých malín z našej farmy.',

            // Včelie produkty
            'Agátový' => 'Svetlý med s jemnou chuťou z agátových kvetov.',
            'Kvetový' => 'Med zo zmiešaných kvetov lúčnych porastov v okolí farmy.',
            'Lesný' => 'Tmavší med z lesných bylín a stromov.',
            'Vlašské' => 'Vlašské orechy zo starých stromov na našom pozemku.',
            'Lieskové' => 'Lieskové oriešky ručne zbierané na jeseň.',
            'Vosk' => 'Včelí vosk vhodný na sviečky aj balzamy.',
            'Propolis' => 'Prírodný propolis zo zdravých včelstiev.',
            'Peľ' => 'Sušený peľ plný bielkovín a živín.',

            // Bylinky
            'Bazalka' => 'Bazalka z domáceho skleníka, silne aromatická a čerstvá.',
            'Tymián' => 'Tymián vhodný do mäsa aj čajov.',
            'Oregano' => 'Voňavé oregano sušené na slnku.',
            'Rozmarín' => 'Rozmarín z južného svahu, ideálny k zemiakom.',
            'Medvedí cesnak' => 'Zberaný v marci, silne aromatický.',
            'Mäta' => 'Osviežujúca mäta vhodná na čaj aj limonády.',
            'Harmanček' => 'Harmanček na upokojenie a zdravý spánok.',
            'Medovka' => 'Medovka na nervy a dobrý čaj.',

            // Domáce zaváraniny
            'Marhuľový' => 'Džem z prezretých marhúľ, varený tradičným spôsobom.',
            'Jahodový' => 'Džem zo sladkých poctivo vyzretých jahôd.',
            'Malinový' => 'Džem zo sladkých malín s kúskami ovocia.',
            'Broskyňový' => 'Kompót zo sladkých broskýň, jemne sladený.',
            'Slivkový' => 'Slivkový kompót s klinčekom a škoricou.',

            // Pestovanie
            'Ovocie' => 'Semienka vybraných ovocných odrôd s vysokou klíčivosťou.',
            'Zelenina' => 'Semienka sezónnej zeleniny vhodné do záhrady.',
            'Bylinky' => 'Semienka aromatických byliniek na celý rok.',
            'Kvetiny' => 'Zmes semien poľných kvetov pre farebný záhon.',
            'Hnojivo' => 'Organické hnojivo vhodné na zeleninu aj kvety.',
            'Sadenica' => 'Zdravé sadenice pripravené na výsadbu.'
        ];

        Category::with('subcategories.subsubcategories')->get()->each(function ($category) use (&$descriptions) {
            foreach ($category->subcategories as $subcategory) {
                if ($subcategory->subsubcategories->count()) {
                    foreach ($subcategory->subsubcategories as $subsubcategory) {
                        $productName = "{$subcategory->name} " . lcfirst($subsubcategory->name);
                        Product::create([
                            'name' => $productName,
                            'description' => $descriptions[$subsubcategory->name] ?? fake()->sentence(3),
                            'category_id' => $category->id,
                            'subcategory_id' => $subcategory->id,
                            'subsubcategory_id' => $subsubcategory->id,
                        ]);
                    }
                } else {
                    Product::create([
                        'name' => $subcategory->name,
                        'description' => $descriptions[$subcategory->name] ?? fake()->paragraph(5),
                        'category_id' => $category->id,
                        'subcategory_id' => $subcategory->id,
                        'subsubcategory_id' => null,
                    ]);
                }
            }
        });
    }
}
