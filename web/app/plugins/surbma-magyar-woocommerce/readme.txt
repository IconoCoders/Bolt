=== HuCommerce | Magyar WooCommerce kiegészítések ===
Contributors: Surbma, CherryPickStudios, xnagyg
Donate link: https://www.hucommerce.hu/
Tags: woocommerce, magyar, magyarország, webáruház, hungarian, hungary
Requires at least: 5.2
Tested up to: 5.4
Stable tag: 23.0
Requires PHP: 7.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Hasznos javítások a magyar WooCommerce webáruházakhoz.

== Description ==

>Hasznos javítások a magyar WooCommerce webáruházakhoz.

A WooCommerce a világ és most már Magyarország legnépszerűbb webáruház platformja is. De ez a tény a WooCommerce fejlesztőket nem érdekli annyira, hogy a magyar igényekhez igazítsák a bővítmény bizonyos funkcióit. Szerencsére adnak bőven lehetőséget a módosításokra, de ezekhez az átlag felhasználó nem ért. Ezért hoztam létre ezt a bővítményt, hogy a magyar WooCommerce webáruházak is végre rendben legyenek.

A funkciók folyamatosan bővülnek, de mindenki megírhatja a véleményét, ötletét, hogyan tudjuk egyre jobbá tenni ezt a bővítményt.

#### HuCommerce támogatói Facebook csoport

Csatlakozzatok a HuCommerce hivatalos támogatói Facebook csoportjához, ahol lehet a bővítménnyel kapcsolatban kérdezni, beszélhgetni, ötletelni. Mindenkit szívesen látunk: [HuCommerce Facebook csoport](https://www.facebook.com/groups/HuCommerce.hu/)

### A bővítmény funkciói

- Vezetéknév és keresztnév rendbetétele
- Adószám bekérése vásárlásnál
- Jogi megfelelés (Fogyasztóvédelem, GDPR, ePrivacy, stb.)
- Pénztár oldal formázása
- Megye mező elrejtése
- Plusz/minusz mennyiségi gombok a termékekhez
- Automatikus frissítés a Kosár oldalon
- Vásárlás folytatása gomb megjelenítése a Kosár és/vagy a Pénztár oldalakon
- Belépés és regisztráció utáni átirányítás
- Ingyenes szállítás értesítés
- Város automatikus kitöltése az irányítószám alapján
- Fordítási hiányosságok javítása
- WPML és Polylang kompatibilitás
- További funkciók hamarosan...

#### Vezetéknév és keresztnév rendbetétele

A keresztnév és vezetéknév sorrendjének a megfordítása, ha a webáruház magyar nyelvre van állítva. Ahogy mi szeretjük. :) Mindezt úgy, hogy reszponzív nézetben is jó legyen és a CRM, számlázó programok is tudják értelmezni.

A megjelenítés kompatibilis a WPML bővítménnyel, így többnyelvű webáruháznál is magyar sorrendben jelenik meg a név, ha magyar nyelven nézik a webáruházat a látogatók.

A név sorrendje a megrendelés visszaigazolásánál, a vásárló fiókjában és az értesítő levelekben is jól jelenik meg.

#### Adószám bekérése vásárlásnál

A Pénztár oldalon a Cégnév mező alatt már Adószámot is be lehet kérni. Az Adószám mező csak akkor jelenik meg, ha a Cégnév mezőbe lett írva. Az adószám ebben az esetben kötelező mező. Az adószám a rendelésen kívül az adott felhasználó profil adatainál is elmentésre kerül. Az adószám megjelenik mind a visszaigazoláson, mind a rendelés szerkesztésénél, valamint az értesítő levelekben is.

#### Jogi megfelelés (Fogyasztóvédelem, GDPR, ePrivacy, stb.)

Lehetőség van az Általános Szerződési Feltételek és az Adatkezelési tájékoztató aktív cselekvésen alapuló elfogadtatására, azaz ki kell pipálnia a vásárlónak ezek elfogadását, mielőtt a rendelést leadhatná. Az adatok a rendeléseknél kerülnek elmentésre és a rendelés szerkesztése oldalon megjelenik az elfogadott státusz. Ebben az esetben a vásárló profil adatainál nem kerül elmentésre az elfogadás, így azt bejelentkezve is minden vásárlás alkalmával el kell fogadnia.

Opcionális a két mező használata, így ha valakinek csak az egyik elfogadtatására és megerősítésére van szüksége, akkor egyik vagy másik kikapcsolható.

A Pénztár oldalon a Megrendelés gomb fölött és/vagy alatt közvetlenül elhelyezhető jogi szöveg, ami esetleg fontos vagy kötelező eleme a vásárlási folyamatnak. Ilyen például a távollévők közötti szerződéshez szükséges tudomásulvétel vagy a "fizetési kötelezettséggel járó megrendelés" kötelező megjelenítése a megrendelés során.

A regisztrációs űrlapnál is kérhető az Adatkezelési Tájékoztató kötelező elfogadtatása. Ez az adat már elmentésre kerül a felhasználó profil adatainál. Külön beállítható, hogy a felhasználó IP címét is elmentse a rendszer. A regisztrációs adatokat (elfogadás ténye, regisztráció dátuma, IP cím) mind az admin felületen, mind pedig a felhasználó fiókadatainál megjeleníti, de ezek a mezők nem módosíthatók sem a felhasználók, sem az adminisztrátorok részéről.

>**FIGYELEM!** A webáruház jogi megfelelése az aktuális törvényeknek és adatvédelmi rendeleteknek minden esetben a webáruház tulajdonosának a felelőssége. Ez az opció nem mentesít senkit sem az alól, hogy a megfelelést felülvizsgáltassa szakértővel vagy jogásszal. A fejlesztők nem vállalnak semmilyen felelősséget a webáruház jogi megfeleléséért.

#### Pénztár oldal formázása

Céges számlázási adatok feltételes megjelenítése. Ebben az esetben egy checkbox jelenik meg és ha a látogató bepipálja, akkor jelennek csak meg a céges számlázás mezői, mint például a Cégnév és Adószám.

A Cégnév és Adószám, az Irányítószám és Város, valamint a Telefonszám és Email cím mezőket nagy monitoron be lehet állítani, hogy egymás mellé kerüljenek. Az Irányítószám és Város mezők az Ország mező alatt jelennek meg közvetlenül, hogy logikusabb legyen a megjelenési sorrend.

Az Ország és a Rendelés jegyzetek mezőket akár ki is lehet kapcsolni, ha ezek a mezők nem relevánsak a te webáruházadnál. Ha az Ország mezőt elrejted, akkor a Bolt beállításainál kiválasztott ország lesz alapértelmezettként beállítva megrendelésnél.

#### Megye mező elrejtése

Mert ezt nálunk nem szokás használni, így csak plusz felesleges lépés. De ha valakinek nagyon kell, akkor visszaállítható.

#### Plusz/minusz mennyiségi gombok a termékekhez

A WooCommerce alapból csak egy szám típusú mezőt használ a termékek mennyiségénél, de ez felhasználói szemmel nézve nem elég. Ez a funkció a mennyiségi mező elé és után betesz egy plusz/minusz gombot, amivel a felhasználók könnyedén tudják változtatni a mennyiséget mind a termék végoldalon, mind pedig a kosár összegzés oldalán.

FIGYELEM! A gombok a különböző sablonoknál esetleg másképp vagy nem megfelelően jelenhetnek meg. Ez minden esetben javítható egy kis CSS segítségével. Én azon vagyok, hogy a lehető legnépszerűbb sablonoknál már automatikusan jól nézzen ki, illetve kap egy alap formázást is, hogy a legtöbb esetben megfelelő legyen, de biztosan lesznek olyan sablonok, ahol ez még így is igényel majd további CSS formázást. A bővítmény támogatás fórumában lehet ezeket jelezni, de nem tudom vállalni, hogy mindenkinek, gyorsan tudok segíteni.

Jelenleg ezeket a sablonokat támogatja alapból a bővítmény:

- Storefront
- Divi
- Avada

#### Automatikus frissítés a Kosár oldalon

A Kosár oldalon a termékek mennyiségének a módosításakor nincs szükség a "Kosár frissítése" gomb megnyomására a darabszám módosítása után, mert így automatikusan frissül a Kosár tartalma.

#### Vásárlás folytatása gomb megjelenítése a Kosár és/vagy a Pénztár oldalakon

A Kosár és a Pénztár oldalakon megjeleníthető egy plusz Vásárlás folytatása gomb, ami az üzlet oldalra viszi a látogatókat, hogy esetleg még tovább válogassanak a termékek között. Sokszor csak kíváncsiságból kattintanak a látogatók a Kosár gombra, de még nem fejezték be a vásárlásukat.

A gombok pozíciója mind a Kosár, mind a Pénztár oldalon beállítható, valamint van lehetőség egyedi üzenet megjelenítésére is bizonyos pozíciókban.

#### Belépés és regisztráció utáni átirányítás

Beállítható, hogy a látogatók a belépés és regisztráció után a meghatározott oldalra legyenek automatikusan átirányítva. A belépéshez és regisztrációhoz külön-külön állítható be a cél URL. A Pénztár oldalon nem veszi figyelembe az egyedi beállítást, hogy ott ne zavarja a vásárlás befejezését.

#### Ingyenes szállítás értesítés

A Kosár oldalon kijelzi, hogy mennyi vásárlási összeg hiányzik még az ingyenes szállításhoz. A szöveg módosítható és többnyelvűsíthető.

#### Város automatikus kitöltése az irányítószám alapján

A Pénztár oldalon az irányítószám mező kitöltése után automatikusan megjeleníti a várost. Ha már manuálisan lett módosítva a város, akkor nem módosítja az irányítószám alapján.

Vannak olyan irányítószámok, amikkel nem működik, mert vagy még hiányzik az indexből vagy egy irányítószám több településhez is tartozik. Igyekszem az ilyen hiányosságokat javítani.

#### Fordítási hiányosságok javítása

Ideiglenes fordítási hiányosságok javítása, amíg a hivatalos fordításban esetleg nem jelenik meg vagy nem frissíti a rendszer. Én hivatalos szerkesztője is vagyok a magyar WooCommerce fordítási csapatának, ezért ott sokmindent megcsinálok, de néha szükség van erre a kis trükkre.

#### WPML és Polylang kompatibilitás

A szöveges mezők kompatibilisek a WPML, Polylang bővítményekkel, így azok beállíthatók a különböző nyelveken is.

### Egyéb fejlesztői infók

#### Szeretnél résztvenni vagy segíteni a bővítmény fejlesztésében?

Megtalálod a teljes forráskódot a GitHub-on:
[https://github.com/CherryPickStudios/surbma-magyar-woocommerce](https://github.com/CherryPickStudios/surbma-magyar-woocommerce)

#### További bővítményeket és projekteket is találsz a GitHub oldalainkon:

- [Surbma GitHub](https://github.com/Surbma)
- [Cherry Pick Studios GitHub](https://github.com/CherryPickStudios)

Nyugodtan segíthetsz a bővítmények, sablonok és egyéb projektek fejlesztésében.

#### Szeretnél többet tudni rólunk és a szolgáltatásainkról?

Nézd meg a weboldalunkat: [HuCommerce.hu](https://www.hucommerce.hu/)

== Installation ==

### Automatikus telepítés

1. A "Bővítmények -> Új hozzáadása" menüpont alatt keress rá a *HuCommerce | Magyar WooCommerce kiegészítések* bővítményre.
2. A bővítmény dobozában kattints a "Telepítés most" gombra.
3. Telepítés után ugyanebben a dobozban kattints a "Bekapcsol" gombra, hogy aktiváld a *HuCommerce | Magyar WooCommerce kiegészítések* bővítményt.
4. A "CPS Plugins -> HuCommerce" menüpont alatt állítsd be, hogy melyik modult szeretnéd használni.
5. Ennyi az egész. :)

### Manuális telepítés az admin felületen

1. Töltsd le a bővítményt: [HuCommerce | Magyar WooCommerce kiegészítések](https://downloads.wordpress.org/plugin/surbma-magyar-woocommerce.zip)
2. Töltsd fel a `surbma-magyar-woocommerce.zip` fájlt a "Bővítmények -> Új hozzáadása" menüpont alatt a "Bővítmény feltöltése" gombra kattintva.
3. Aktiváld a *HuCommerce | Magyar WooCommerce kiegészítések* bővítményt a feltöltés után.
4. A "CPS Plugins -> HuCommerce" menüpont alatt állítsd be, hogy melyik modult szeretnéd használni.
5. Ennyi az egész. :)

### Manuális telepítés FTP használatával

1. Töltsd le a bővítményt: [HuCommerce | Magyar WooCommerce kiegészítések](https://downloads.wordpress.org/plugin/surbma-magyar-woocommerce.zip)
2. Tömörítsd ki a zip fájlt a számítógépeden.
3. Töltsd fel a `surbma-magyar-woocommerce` mappát a `/wp-content/plugins/` mappába.
4. Aktiváld a *HuCommerce | Magyar WooCommerce kiegészítések* bővítményt a "Bővítmények" menüpont alatt a WordPress admin felületen.
5. A "CPS Plugins -> HuCommerce" menüpont alatt állítsd be, hogy melyik modult szeretnéd használni.
6. Ennyi az egész. :)

== Frequently Asked Questions ==

= Hol találom a bővítmény beállításait? =

A *HuCommerce | Magyar WooCommerce kiegészítések* bővítmény beállításait a "CPS Plugins -> HuCommerce" menüpont alatt éred el.

= Nem cserélődtek meg a nevek a Pénztár oldalon. =

Először töröld a szerver oldali és a böngésző gyorsítótárát és frissítsd az oldalt! Győződj meg róla, hogy esetleg más bővítmény nem okoz-e konfliktust! Ha a fordítást módosítottad, az is lehet probléma. Illetve a sablonok is tartalmazhatnak olyan egyedi kódokat, amivel ez a funkció felülírható.

Figyelem! A nevek cseréje csak akkor történik meg, ha magyar nyelvre van állítva a webáruház.

= Mit jelent az, hogy Surbma? =

A vezetéknevem visszafelé. ;)

== Changelog ==

= 23.0 =

Kiadás dátuma: 2020-04-13

Új funkció nincs a frissítéssel, de a Pénztár oldalon jelentős módosítások történtek, ami növeli a kompatibilitást más bővítményekkel, igazodik a WooCommerce és a HuCommerce különböző variációjú beállításaihoz és optimalizálja a kódokat, csökkenti a lekérések számát. Frissítése biztonségos.

MÓDOSÍTÁSOK

- Az Adószám mező kezelésének a teljeskörű újragondolása. Elvileg most már kompatibilis minden beállítási variációval és helyesen kezeli a feltételes kötelező jellegét.
- Az adószám mező kezeléséhez szükséges javascript kód inline beillesztése, amivel csökkent a lekérések száma, valamint a beállításoktól függően jelennek csak meg a kódok.
- A Pénztár oldal kódjainak jelentős optimalizálása a különböző beállításokkal való kompatibilitás érdekében.
- A Pénztár oldal kezeléséhez szükséges javascript kód inline beillesztése, amivel csökkent a lekérések száma, valamint a beállításoktól függően jelennek csak meg a kódok.

JAVÍTÁSOK

- További ellenőrzések a Pénztár mezők módosításainál, hogy kompatibilis legyen más bővítményekkel, amik szintén módosítják a Pénztár mezőket. (Köszönet: Viszt Péter - @passatgt)

= 22.1 =

Kiadás dátuma: 2020-03-30

Kicsi, de fontos javításokat tartalmazó frissítés. Mindenképpen ajánlott a frissítés, de minimum a WooCommerce 4.x főverzió esetén!

JAVÍTÁSOK

- Ország elrejtése esetén fontos javítás, hogy ne módosítsa a többi mezőt.
- Mezők sorrendjének a javítása, hogy minden ország esetén rendben legyen a megjelenés.

EGYÉB

- Kompatibilitás ellenőrzése a WordPress 5.4 főverzióval.
- CPS SDK frissítése az 5.10 verzióra.

= 22.0 =

Kiadás dátuma: 2020-03-12

FONTOS! Csak akkor frissíts erre a verzióra, ha a WooCommerce is frissítve lett legalább a 4.0 verzióra!

MÓDOSÍTÁSOK

- A quantity-input.php template fájl törölve lett, mivel a WooCommerce 4.0 verzióban új hook-ok lettek hozzáadva.
- A plusz/minusz gombok az új hook-okkal lettek hozzáadva. Csak a WooCommerce 4.0 verziótól működik!

EGYÉB

- Kód optimalizálás.
- Kompatibilitás ellenőrzése a WooCommerce 4.0 verzióval.
- A minimum PHP követelmény módosítása 7.0 verzióra.

= 21.6 =

Kiadás dátuma: 2020-03-09

Kisebb módosításokat és hibajavítást tartalmaz ez a verzió. A frissítése biztonságos és mindenkinek ajánlott.

MÓDOSÍTÁSOK

- Kódok átírása.

JAVÍTÁSOK

- Fordítás textdomain módosítása egy-két helyen.
- Beállítások globális változóinak a korai hívása, hogy betöltésnél már rendelkezésre álljanak a választások.

= 21.5 =

Kiadás dátuma: 2020-02-20

MÓDOSÍTÁSOK

- Pénztár oldalon a város automatikus kitöltése esetén a mező validálása.

= 21.4 =

Kiadás dátuma: 2020-02-19

Kisebb módosítás, hogy a számlázási és a szállítási adatoknál az irányítószám megadása esetén frissüljön a Pénztár.

MÓDOSÍTÁSOK

- Pénztár frissítése az irányítószám megadása után, az automatikus kitöltés esetén.

= 21.3 =

Kiadás dátuma: 2020-02-19

JAVÍTÁSOK

- Verziószám frissítés.

= 21.2 =

Kiadás dátuma: 2020-02-19

JAVÍTÁSOK

- A Céges számlázás mezőnél a "Nem kötelező" szöveg törlése.
- Felesleges JS kódok törlése.
- Nyelvi mappa törlése, mivel már elérhető a hivatalos forrásból.

= 21.1 =

Kiadás dátuma: 2020-02-19

JAVÍTÁSOK

- Az Adószám mező külön kötelezővé tétele már felesleges, mert alapból is kötelező.

= 21.0 =

Kiadás dátuma: 2020-02-18

Az előző verzióból kimaradt egy fontos fejlesztés, amit most bepótoltam. Ha nem feltételesen jelennek meg a céges számlázási mezők, akkor is csak a Cégnév látszik alapból és az Adószám csak akkor, ha kitölti a Cégnevet. Az Adószám innentől kezdve kötelező mező lett.

ÚJDONSÁGOK

- Adószám kötelező lett, illetve a Cégnév kitöltésétől függően jelenik meg alapból.

JAVÍTÁSOK

- Kisebb javítás a leírásban.

= 20.0 =

Kiadás dátuma: 2020-02-18

A legújabb főverzió igazán nagy és régóta várt újításokat tartalmaz. Mostantól megjeleníthető egy "Céges számlázás" checkbox, ami elrejti vagy megjeleníti a céges számlázási adatokat (Cégnév, Adószám). Illetve egy régi "hibát" is javítottam: az Ország mező elrejtése esetén a megrendelés során alapértelmezettként a Bolt beállított országa lesz az aktuális Ország. Így nem lesz konfliktusban a szállítási és fizetési modulokkal.

ÚJDONSÁGOK

- Céges számlázási adatok feltételes megjelenítése. Aktív állapotban a céges adatok kitöltése kötelező.
- Cégnév és Adószám mezők egymás mellé rendezése.

JAVÍTÁSOK

- Ha az "Ország mező elrejtése" opció aktív, akkor az ország mező csak rejtve van, nem törölve és alapértelmezettként megkapja a Bolt beállított országának az értékét.
- JS fájlok kiegészítése verziószámmal és a kód átmozgatása a footer-be.
- Kisebb kód igazítások.

= 19.1 =

Kiadás dátuma: 2020-02-15

Fontos javítások a fordítási lehetőségekkel kapcsolatban.

JAVÍTÁSOK

- A text domain módosítása a bővítmény slug-jára, mert csak azzal működik együtt a wp.org.
- Két modul javascript hivatkozásának a későbbi meghívása, hogy lehetőleg ne legyen konfliktusban más optimalizáló bővítményekkel.

EGYÉB

- Ideiglenesen a bővítmény fordítását mellékeltem, amíg a hivatalos forrásból nem lesz elérhető.

= 19.0 =

Kiadás dátuma: 2020-02-11

Az irányítószám automatikus kitöltése most már a szállítási címnél is működik. A bővítmény elő lett készítve a fordíthatóságra. Viszont emiatt mindent át kell majd írni angolra, aztán lefordítani magyarra. Ez biztos hosszabb folyamat lesz, de megéri. :)

ÚJDONSÁGOK

- A bővítmény fordíthatóságának előkészítése.

MÓDOSÍTÁSOK

- Autofill beállítása a szállítási címhez.

EGYÉB

- Kompatibilitás ellenőrzése a WordPress 5.3 főverzióval.
- Kompatibilitás ellenőrzése a WooCommerce 3.9 főverzióval.
- Új frissítési mód bevezetése, hogy automatikusan feltöltse a wp.org repo-ba.

= 18.7 =

Kiadás dátuma: 2019-11-03

Mostantól megjelenít egy figyelmeztető szöveget az Adószám megjelenítése opciónál, ha a Viszt Péter által írt Szamlazz.hu vagy Billingo bővítményeknél is be van kapcsolva az adószám mező. Ebben az esetben két adószám mező jelenik meg, így az egyik bővítménynél ki kell ezt az opciót kapcsolni.

EGYÉB

- Kompatibilitás javítása a Viszt Péter által írt Szamlazz.hu és Billingo bővítményekhez.

= 18.6 =

Kiadás dátuma: 2019-11-03

Ez a frissítés az irányítószám adatbázis frissítését tartalmazza. Így most már Budapest és a nagyvárosok is szerepelnek az adatbázisban.

JAVÍTÁSOK

- Irányítószámok frissítése.
- Apró javítás a leírásban, hogy az admin menüpontot helyesen írja le.

= 18.5 =

Kiadás dátuma: 2019-08-08

Ebben a kiadásban a HuCommerce menüpont átkerült a CPS Plugins főmenüpont alá, illetve néhány apró javítás és fejlesztés található. A frissítése biztonságos éles oldalakon is.

JAVÍTÁSOK

- A Plusz/minusz mennyiségi gombok új HTML kódot kaptak, hogy bizonyos bővítményekkel ne legyen konfliktusban a megjelenésük.
- A Product quantity inputs template frissítve lett a 3.6.0 verzióra.
- A Fiók oldalon javítva lett az új logika szerint a nevek megjelenítése, így ott is akkor cserélődik meg a keresztnév és vezetéknév sorrendje, ha magyar nyelven nézi a látogató a weboldalt.

EGYÉB

- CPS SDK frissítése az 5.3 verzióra.
- A HuCommerce menüpont átkerült a CPS Plugins menüpont alá.

= 18.4 =

Kiadás dátuma: 2019-07-25

Semmi funkcionális változtatás most, csak egy kis rendrakás. Kompatibilitás javítása a többi Cherry Pick Studios bővítménnyel.

JAVÍTÁSOK

- Az új CPS SDK kompatibilitás javítása.
- Admin header filterek csak a HuCommerce admin oldalán futnak le.

EGYÉB

- CPS SDK frissítése az 5.2 verzióra.
- Új assets mappa, hogy logikusabb legyen a mappa struktúra.

= 18.3 =

Kiadás dátuma: 2019-07-18

JAVÍTÁSOK

- WooCommerce bővítmény aktív állapotának az ellenőrzése most már minden esetben jól működik.

= 18.2 =

Kiadás dátuma: 2019-07-18

Betettem egy ellenőrzést, hogy a WooCommerce bővítmény aktív-e. Ha nem, akkor a kódok nem futnak le, csak egy figyelmeztetés jelenik meg az admin felületen.

JAVÍTÁSOK

- WooCommerce bővítmény ellenőrzése, hogy be van-e kapcsolva.

= 18.1 =

Kiadás dátuma: 2019-07-16

Ebben a verzióban az Avada sablonhoz lett egyedi CSS rendelve, hogy a Pénztár oldalon működjenek a magyar formátum javítások.

JAVÍTÁSOK

- CSS hozzáadása az Avada sablon megjelenítésének a javításához.

= 18.0 =

Kiadás dátuma: 2019-07-09

A mostani verzióban külön modul lett hozzáadva a Pénztár oldali módosításokhoz. Itt lehet vezérelni, hogy az Irányítószám és Város, valamint a Telefonszám és Email mezők egymás mellé kerüljenek, illetve az Ország és Rendelés jegyzetek mezőket ki is lehet kapcsolni.

ŰJDONSÁGOK

- Pénztár oldal modul.

EGYÉB

- CPS SDK frissítése a 3.0 verzióra.
- Admin oldal módosítások a CPS SDK 3.0 alapján.

= 17.0 =

Kiadás dátuma: 2019-07-01

Ez a frissítés javítja a WPML bővítmény kompatibilitást és gyorsabb is a betöltés WPML használata mellett. A magyar formátum javítások logikája is megváltozott, mert most bizonyos mezők akkor módosulnak, ha magyar nyelven nézi a látogató, nem pedig akkor, ha Magyarország a számlázási cím. A Divi és Storefront sablonok esetén kisebb fordítással kapcsolatos javításokat is tartalmaz. Admin felületen megjelenik egy üdvözlés, ami lezárás után nem jelenik meg újra.

JAVÍTÁSOK

- WPML kompatibilitás és gyorsítás.

MÓDOSÍTÁSOK

- Pénztár mezők feltételei módosultak a nyelvre a számlázási cím helyett.

ÚJDONSÁGOK

- Admin üdvözlés a bővítmény első bekapcsolása után vagy a mostani frissítés után.
- A Divi és Storefront sablonok esetén javítja a kosár ikon melletti szöveget, hogy "elem" helyett "termék" jelenjen meg a megfelelő többesszám kezeléssel.

= 16.0 =

- FRISSÍTÉS - 2019-06-11
- ÚJ - HuCommerce hírlevél feliratkozási lehetőség a HuCommerce admin oldaláról.

= 15.1 =

- FRISSÍTÉS - 2019-05-07
- JAVÍTÁS - Új opció validálása.

= 15.0 =

- FRISSÍTÉS - 2019-05-07
- ÚJ - Automatikus frissítés a Kosár oldalon. (Köszönet: Nagy Gábor - @xnagyg)
- JAVÍTÁS - Irányítószámok kiegészítése a budapesti kerületekkel. (Köszönet: Nagy Gábor - @xnagyg)

= 14.4 =

- FRISSÍTÉS - 2019-05-02
- JAVÍTÁS - Változó alapértelmezett értékének beállítása. (Köszönet: Szépe Viktor - @szepeviktor)

= 14.3 =

- FRISSÍTÉS - 2019-04-22
- MÓDOSÍTÁS - Kisebb változtatások a leírásban.
- Tesztelve a WooCommerce 3.6 verziójával.

= 14.2 =

- FRISSÍTÉS - 2019-04-15
- JAVÍTÁS - Ingyenes szállítás értesítés link módosítása, hogy mindig az Üzlet oldalra mutasson.

= 14.1 =

- FRISSÍTÉS - 2019-04-08
- JAVÍTÁS - Admin stílusok betöltése csak a szükséges oldalakon.

= 14.0 =

- FRISSÍTÉS - 2019-04-07
- ÚJ - CPS SDK az egységes kinézethez és funkcionalitáshoz a többi bővítmény esetén is.
- CPS SDK 1.0
- MÓDOSÍTÁS - Leírásokban és a bővítmény jellemzőinél Cherry Pick Solutions megemlítése.
- MÓDOSÍTÁS - Prémium verzió kezelésének előkészítése.

= 13.0 =

- FRISSÍTÉS - 2019-04-02
- ÚJ - Adatkezelési tájékoztató elfogadásának rögzítése a regisztrációs folyamatban.
- ÚJ - IP cím opcionális rögzítése a regisztrációs folyamatban.
- ÚJ - Regisztrációs adatok megjelenítése mind az admin felületen, mind a fiókadatok oldalon.

= 12.0 =

- FRISSÍTÉS - 2019-03-11
- ÚJ - Adatkezelési Tájékoztató kötelező elfogadtatása a regisztrációs űrlapnál.
- FIGYELEM! Ha a Jogi megfelelés opció aktív, akkor a regisztrációs űrlapnál automatikusan megjelenik az Adatkezelési Tájékoztató elfogadtatása pipa. Kikapcsolásához törölni kell az alapértelmezett szöveget a mezőben.

= 11.3 =

- FRISSÍTÉS - 2019-03-06
- JAVÍTÁS - Kosár oldalon a becslés címének javítása az Adószám megjelenítése esetén.

= 11.2 =

- FRISSÍTÉS - 2019-02-28
- JAVÍTÁS - Admin felület nyelvi beállítása.

= 11.1 =

- FRISSÍTÉS - 2019-02-27
- JAVÍTÁS - WPML ellenőrzése csak a front-end felületen.

= 11.0 =

- FRISSÍTÉS - 2019-02-17
- ÚJ - Facebook támogatói csoport jelentkezési lehetőség az admin felületről.
- ÚJ - Jogi szövegek elhelyezése a Pénztár oldalon a Megrendelés gomb fölött és/vagy alatt közvetlenül.
- MÓDOSÍTÁS - Kisebb változtatások az admin felület megjelenésén.

= 10.1 =

- FRISSÍTÉS - 2019-02-17
- JAVÍTÁS - WPML bővítmény ellenőrzése, hogy ne generáljon végzetes hibát.

= 10.0 =

- FRISSÍTÉS - 2019-02-10
- ÚJ - Város automatikus kitöltése az irányítószám alapján.
- JAVÍTÁS - Kisebb javítások az admin felület megjelenítésén.

= 9.0 =

- FRISSÍTÉS - 2019-02-09
- ÚJ - Adószám bekérése vásárlásnál
- ÚJ - Jogi megfelelés (GDPR, ePrivacy, stb.)
- MÓDOSÍTÁS - A magyar név sorrend mostantól nem akkor érvényes, ha a cím magyar, hanem minden esetben, ha magyar nyelvű a webáruház.
- JAVÍTÁS - A magyar név sorrend kompatibilis a WPML bővítménnyel, így többnyelvű webáruháznál igazodik a választott nyelvhez.
- MÓDOSÍTÁS - Kisebb változtatás az admin megjelenésén.

= 8.0 =

- FRISSÍTÉS - 2019-02-06
- ÚJ - Ingyenes szállítás értesítés.
- JAVÍTÁS - Mező értékek validálásának a javítása.

= 7.1 =

- FRISSÍTÉS - 2019-02-06
- JAVÍTÁS - Vásárlás folytatása gomb class meghatározásainak módosítása a könnyebb formázás érdekében.

= 7.0 =

- FRISSÍTÉS - 2019-02-06
- ÚJ - Belépés és regisztráció utáni átirányítás.
- ÚJ - WPML és Polylang kompatibilitás a szöveges mezőkhöz.

= 6.0 =

- FRISSÍTÉS - 2019-02-06
- ÚJ - Vásárlás folytatása gomb megjelenítése a Kosár és/vagy a Pénztár oldalakon. A gomb pozíciója is beállítható.

= 5.2 =

- FRISSÍTÉS - 2019-01-12
- MÓDOSÍTÁS- Admin UIkit frissítése.

= 5.1 =

- FRISSÍTÉS - 2019-01-12
- JAVÍTÁS - Divi sablon egyedi formázások a plusz/minusz gombokhoz.
- JAVÍTÁS - Kisebb kód optimalizálás a plusz/minusz gombok CSS formázásánál.

= 5.0 =

- FRISSÍTÉS - 2019-01-11
- ÚJ - Mennyiségi gombok a termékekhez.
- ÚJ - A bővítmény nevének módosítása: HuCommerce | Magyar WooCommerce kiegészítések
- ÚJ - Admin beállítások a bővítményhez. Minden modul ki- bekapcsolható.
- ÚJ - Moduláris felépítés első lépései, felkészítés további hasznos funkciók optimális implementálásához.
- JAVÍTÁS - A WooCommerce myaccount/form-edit-account.php sablon frissítése a 3.5.0 verzióra.
- MÓDOSÍTÁS - A megye mező elrejtésének a kikapcsolása már nem működik az eddigi globális változó megadásával, ezt is az admin felületen lehet beállítani.
- MÓDOSÍTÁS - Kisebb javítások a leírásban.

= 4.5 =

- Kis javítás a leírásban.
- Tesztelve a WooCommerce 3.5 verziójával.
- Tesztelve a WordPress 5.0 verziójával.

= 4.4 =

- FIX - A form-edit-account template fájl frissítése a legújabb verzióra.

= 4.3 =

- Autofocus beállítása a vezetéknévre. (Köszönet: @nagygabor) #1

= 4.2 =

- A keresztnév mező autofocus törlése. #1

= 4.1 =

- A szerződési feltételek szövegének a felülírása már nem kell, mert az új verzióban helyesen jelenik meg.
- Tesztelve a WooCommerce 3.3 verziójával.

= 4.0 =

- A bővítmény most már felül tudja írni a WooCommerce template fájlokat.
- Fiók adatoknál is jól jelenik meg a nevek sorrendje, ha a számlázási cím Magyarország.
- Szállítási és számlázási címeknél is helyes a név megjelenítése magyarországi cím esetén.
- Kisebb kód javítások.

= 3.0 =

- A WooCommerce 3.2 verziónál a szerződési feltételek elfogadása szöveg fordítása nem jelenik meg, erre van egy javítás, amíg nem lesz elérhető a hivatalos fordítás javított változata.

= 2.0 =

- Az Irányítószám és Város mezők nagy monitoron egymás mellé kerültek és az Ország mező alatt jelennek meg közvetlenül, hogy logikusabb legyen a megjelenési sorrend.
- A Megye mező elrejtését most már WooCommerce filter kezeli. (Köszönet: Viszt Péter - @passatgt)
- További címkék a bővítményhez a jobb keresési találatok miatt.

= 1.1 =

- Kód elírás javítása.
- Leírások hozzáadása a függvényekhez.

= 1.0.0 =

- Első kiadás.
