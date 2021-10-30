<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            'Austria',
            'Belgium',
            'Bulgaria',
            'Cyprus',
            'Czech Republic',
            'Germany',
            'Denmark',
            'Estonia',
            'Spain',
            'Finland',
            'France',
            'United Kingdom',
            'Greece',
            'Hungary',
            'Croatia',
            'Ireland, Republic of (EIRE)',
            'Italy',
            'Lithuania',
            'Luxembourg',
            'Latvia',
            'Malta',
            'Netherlands',
            'Poland',
            'Portugal',
            'Romania',
            'Sweden',
            'Slovenia',
            'Slovakia',
        ];

        $dbCountries = Country::get();

        foreach ($countries as $country ) {
            if (!$dbCountries->contains('country',$country)) {
                Country::create([
                    'country'=>$country
                ]);
            }
        }
    }
}
