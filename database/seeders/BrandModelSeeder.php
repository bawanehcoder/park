<?php

namespace Database\Seeders;

use App\Models\CarBrand;
use App\Models\CarModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brandsAndModels = [
            'Toyota' => ['Corolla', 'Camry', 'RAV4', 'Land Cruiser', 'Hilux'],
            'Ford' => ['F-150', 'Mustang', 'Explorer', 'Focus', 'Ranger'],
            'BMW' => ['X5', '3 Series', '5 Series', '7 Series', 'X3'],
            'Mercedes-Benz' => ['C-Class', 'E-Class', 'GLA', 'GLE', 'S-Class'],
            'Audi' => ['A3', 'A4', 'A6', 'Q5', 'Q7'],
            'Nissan' => ['Altima', 'Sentra', 'Maxima', 'Pathfinder', 'Patrol'],
            'Chevrolet' => ['Tahoe', 'Silverado', 'Malibu', 'Camaro', 'Suburban'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'Pilot', 'Odyssey'],
            'Hyundai' => ['Elantra', 'Sonata', 'Tucson', 'Santa Fe', 'Kona'],
            'Kia' => ['Sportage', 'Sorento', 'Optima', 'Telluride', 'Rio'],
            'Mazda' => ['Mazda3', 'Mazda6', 'CX-5', 'CX-9', 'MX-5 Miata'],
            'Subaru' => ['Outback', 'Forester', 'Impreza', 'WRX', 'Crosstrek'],
            'Volkswagen' => ['Jetta', 'Passat', 'Tiguan', 'Golf', 'Atlas'],
            'Jeep' => ['Wrangler', 'Cherokee', 'Grand Cherokee', 'Compass', 'Renegade'],
            'Volvo' => ['XC60', 'XC90', 'S60', 'S90', 'V60'],
            'Lexus' => ['RX', 'ES', 'NX', 'GX', 'LX'],
            'Tesla' => ['Model S', 'Model 3', 'Model X', 'Model Y', 'Cybertruck'],
            'Porsche' => ['911', 'Cayenne', 'Macan', 'Panamera', 'Taycan'],
            'Ferrari' => ['488 GTB', 'Portofino', 'F8 Tributo', 'Roma', 'SF90 Stradale'],
            'Lamborghini' => ['Huracán', 'Aventador', 'Urus', 'Gallardo', 'Veneno'],
            'Mitsubishi' => ['Lancer', 'Outlander', 'Eclipse Cross', 'Pajero', 'ASX'],
            'Peugeot' => ['208', '308', '508', '2008', '3008'],
            'Renault' => ['Clio', 'Megane', 'Captur', 'Kadjar', 'Duster'],
            'Skoda' => ['Octavia', 'Superb', 'Kodiaq', 'Karoq', 'Fabia'],
            'Land Rover' => ['Range Rover', 'Defender', 'Discovery', 'Evoque', 'Velar'],
            'Jaguar' => ['XE', 'XF', 'F-Type', 'E-Pace', 'F-Pace'],
            'Alfa Romeo' => ['Giulia', 'Stelvio', 'Tonale', '4C Spider', 'GTV'],
            'Chrysler' => ['300', 'Pacifica', 'Voyager', 'Aspen', 'Crossfire'],
            'Dodge' => ['Charger', 'Challenger', 'Durango', 'Journey', 'Viper'],
            'Cadillac' => ['Escalade', 'XT5', 'CT5', 'XT4', 'CT6'],
            'GMC' => ['Sierra', 'Yukon', 'Canyon', 'Acadia', 'Terrain'],
            'Buick' => ['Enclave', 'Encore', 'LaCrosse', 'Regal', 'Verano'],
            'Infiniti' => ['Q50', 'Q60', 'QX50', 'QX60', 'QX80'],
            'Acura' => ['ILX', 'TLX', 'RDX', 'MDX', 'NSX'],
            'Genesis' => ['G70', 'G80', 'G90', 'GV70', 'GV80'],
            'Suzuki' => ['Swift', 'Vitara', 'Jimny', 'Baleno', 'Ciaz'],
            'Fiat' => ['500', 'Panda', 'Tipo', 'Punto', 'Ducato'],
            'Seat' => ['Ibiza', 'Leon', 'Ateca', 'Arona', 'Tarraco'],
            'Mini' => ['Cooper', 'Countryman', 'Clubman', 'Paceman', 'Convertible'],
            'Maserati' => ['Ghibli', 'Levante', 'Quattroporte', 'MC20', 'GranTurismo'],
            'Bentley' => ['Continental GT', 'Bentayga', 'Flying Spur', 'Mulsanne'],
            'Rolls-Royce' => ['Phantom', 'Ghost', 'Wraith', 'Cullinan', 'Dawn'],
            'Aston Martin' => ['DB11', 'Vantage', 'DBX', 'Rapide', 'Vanquish'],
            'McLaren' => ['720S', '570S', 'Artura', 'P1', 'Speedtail'],
            'Bugatti' => ['Chiron', 'Veyron', 'Divo', 'Bolide', 'Centodieci'],
            'Citroën' => ['C3', 'C4', 'C5', 'Berlingo', 'SpaceTourer'],
            'Haval' => ['H2', 'H6', 'H9', 'Jolion', 'Big Dog'],
            'Great Wall' => ['Pao', 'Cannon', 'Haval H6', 'Wingle 7', 'Ora Cat'],
            'Geely' => ['Coolray', 'Azkarra', 'Emgrand', 'Okavango', 'Panda Mini'],
            'Chery' => ['Tiggo 2', 'Tiggo 5', 'Tiggo 7', 'Arrizo 5', 'QQ'],
        ];

        foreach ($brandsAndModels as $brandName => $models) {
            $brand = CarBrand::create(['name' => $brandName]);

            foreach ($models as $modelName) {
                CarModel::create([
                    'name' => $modelName,
                    'brand_id' => $brand->id,
                ]);
            }
        }
    }
}
