# WIFI Finance - ein WIFI Projekt

## App Konzept

Das Projekt soll eine vereinfachte Version eines Aktien-Brokers darstellen. Ein nicht eingeloggter User kann sich Informationen (aktueller Preis, Preisverlauf, Firmenbeschreibung) über eine Aktien holen.
Ein eingeloggter User kann dazu noch Aktien auf dessen Watchlist hinzufügen. Wenn der User sogar noch verifiziert ist, kann er/sie Ein- Auszahlungen auf dessen Account tätigen und Aktien kaufen/verkaufen.

### Datenbank ER Model: 
![blueprint](./misc/database_blueprint_.png)


## Getting Started

### Packages zu installieren:

#### Laravel/UI

https://github.com/laravel/ui - Für User Registrierung und Anmeldung

In Terminal: `composer require laravel/ui` und `php artisan ui bootstrap --auth`
**WICHTIG:** Nach der Installation (und nach jeder `composer install`) muss noch in `/vendor/laravel/ui/auth-backend/RegistersUsers.php` in `function showRegistrationForm()` hinzugefügt werden:
```
public function showRegistrationForm()
    {
        $countries = Country::select('id','country')->orderBy('country')->get();
        return view('auth.register', compact('countries'));
    }
 ```
... um die Länder im Registrierungsformular darstellen zu können.

#### Bildverarbeitungspaket

http://image.intervention.io/ - Für die Bearbeitung des Verifizierungsfotos

In Terminal: `composer require intervention/image`
<!-- 
Nach der Installation muss noch folgendes gemacht werden für die Integration in Laravel:
*laut Installationsguide auf der Website:*
In config/app.php:
- In the `$providers` array add the service providers for this package `Intervention\Image\ImageServiceProvider::class` 
- Add the facade of this package to the `$aliases` array.`'Image' => Intervention\Image\Facades\Image::class` 
-->

#### (Optional) Debug Tool

https://github.com/barryvdh/laravel-debugbar


### Zusätzliche Einstellungen in der Applikation:

<!-- 
#### Pagination - Bootstrap - Funktion

Damit Bootstrap erkennt, dass er die Daten paginiert anzeigen soll, muss folgendes noch in `App\Providers\AppServiceProvider.php` in `function boot`:
```
 /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
 ``` 
-->

#### API TIINGO:

In `/.env` muss der API TOKEN eingefügt werden: `TIINGO_TOKEN=`

<!-- 
Unter `config/services.php` it's where we can map environment variables into the app configuration.
Noch hinzufuegen: 
```
'tiingo' => [
        'token' => env('TIINGO_TOKEN'),
    ],
 ``` 
 -->

<!-- 
Bzgl SSL Error
Download the cacert.pem file at https://curl.haxx.se/docs/caextract.html and update your php.ini file with the path (search for 'curl' in file):
`curl.cainfo = <absolute_path_to> cacert.pem` eg `curl.cainfo = "C:/Program Files/Ampps\www\WIFI\test_tiingo\cacert.pem"`
(https://github.com/taxjar/taxjar-php/issues/13)
 -->

Aufrufbar ist der Token mit: `config('services.tiingo.token')`


#### Datenbank

##### Ausführen beim ersten Mal der App, nachdem alle Packages installiert sind

- in `/.env` die Datenbank Connection herstellen
- `php artisan migrate` ... um die Datenbank zu migrieren
- `php artisan db:seed` ... Datenbank mit vordefinierten Daten befüllen, hier die Countries und die Rollen
