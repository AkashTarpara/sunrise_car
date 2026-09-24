<?php

namespace app\components;

use Yii;

class ServiceAreaHelper
{
    private static $_floridaZipMap = null;

    /**
     * Get the full associative map of allowed Florida ZIP codes: [zip => areaName]
     *
     * @return array
     */
    public static function getFloridaZipMap()
    {
        if (self::$_floridaZipMap !== null) {
            return self::$_floridaZipMap;
        }

        $discrete = [
            'Key Largo' => ['33037'],
            'Jupiter' => ['33458', '33469', '33477', '33478'],
            'Hallandale Beach' => ['33008', '33009'],
            'Tavernier' => ['33070'],
            'Islamorada' => ['33036'],
            'Florida City' => ['33034'],
            'Homestead' => ['33030', '33031', '33032', '33033', '33035'],
            'Sebastian' => ['32958'],
            'Miami' => [
                '33101', '33109', '33125', '33126', '33127', '33128', '33129', '33130', '33131', '33132',
                '33133', '33134', '33135', '33136', '33137', '33138', '33139', '33140', '33141', '33142',
                '33143', '33144', '33145', '33146', '33147', '33149', '33150', '33151', '33152', '33153',
                '33154', '33155', '33156', '33157', '33158', '33160', '33161', '33162', '33165', '33166',
                '33167', '33168', '33169', '33170', '33172', '33173', '33174', '33175', '33176', '33177',
                '33178', '33179', '33180', '33181', '33182', '33183', '33184', '33185', '33186', '33187',
                '33189', '33190', '33193', '33194', '33196'
            ],
            'Kissimmee' => [
                '34741', '34742', '34743', '34744', '34745', '34746', '34747', '34758', '34769', '34770',
                '34771', '34772', '34773', '34788'
            ],
            'Tampa' => [
                '33647', '33650', '33655', '33660', '33662', '33663', '33664', '33694'
            ],
            'Naples' => [
                '34102', '34103', '34104', '34105', '34108', '34109', '34110', '34112', '34113', '34114',
                '34116', '34117', '34119', '34120', '34145'
            ],
            'Punta Gorda' => ['33950', '33951', '33980', '33981', '33982', '33983'],
            'Cape Coral' => ['33904', '33909', '33914', '33919', '33990', '33991', '33993'],
            'Fort Myers' => [
                '33901', '33902', '33903', '33905', '33906', '33907', '33908', '33911', '33912', '33913',
                '33916', '33917', '33919', '33965', '33966', '33967', '33971', '33973'
            ],
        ];

        $ranges = [
            'West Palm Beach' => [[33401, 33422]],
            'Fort Lauderdale' => [[33301, 33351]],
            'Boca Raton' => [[33427, 33488]],
            'Delray Beach' => [[33444, 33484]],
            'Boynton Beach' => [[33426, 33474]],
            'Palm Beach Gardens' => [[33408, 33418]],
            'Port St. Lucie' => [[34952, 34987]],
            'Fort Pierce' => [[34945, 34982]],
            'Vero Beach' => [[32960, 32969]],
            'Melbourne' => [[32901, 32941]],
            'Palm Bay' => [[32905, 32911]],
            'Titusville' => [[32754, 32796]],
            'Orlando' => [[32801, 32899]],
            'Tampa' => [
                [33601, 33626],
                [33629, 33637],
                [33672, 33675],
                [33677, 33681],
                [33684, 33689]
            ],
        ];

        $map = [];
        foreach ($discrete as $area => $zips) {
            foreach ($zips as $z) {
                $map[(string)$z] = $area;
            }
        }
        foreach ($ranges as $area => $rList) {
            foreach ($rList as $r) {
                for ($z = $r[0]; $z <= $r[1]; $z++) {
                    $map[(string)$z] = $area;
                }
            }
        }

        self::$_floridaZipMap = $map;
        return self::$_floridaZipMap;
    }

    /**
     * Get all supported service zones, cities, counties, and airports.
     *
     * @return array
     */
    public static function getAllowedAreas()
    {
        return [
            'florida' => [
                'name' => 'Florida / South Florida',
                'states' => ['FL', 'Florida'],
                'cities' => [
                    'Key Largo',
                    'Tavernier',
                    'Islamorada',
                    'Florida City',
                    'Homestead',
                    'Kendall',
                    'Doral',
                    'Pinecrest',
                    'Coconut Grove',
                    'Coral Gables',
                    'Miami',
                    'Miami Beach',
                    'North Miami',
                    'South Miami',
                    'Sunny Isles',
                    'Sunny Isles Beach',
                    'Hallandale',
                    'Hallandale Beach',
                    'Hollywood',
                    'Fort Lauderdale',
                    'Ft Lauderdale',
                    'Ft. Lauderdale',
                    'Davie',
                    'Davie Beach',
                    'Dania Beach',
                    'Jupiter',
                    'Jupiter Island',
                    'South Ranches',
                    'Southwest Ranches',
                    'Boca Raton',
                    'Boynton Beach',
                    'Boyton Beach',
                    'Delray Beach',
                    'West Palm Beach',
                    'Palm Beach',
                    'North Palm Beach',
                    'Palm Beach Gardens',
                    'Port Saint Lucie',
                    'Port St Lucie',
                    'Port St. Lucie',
                    'Fort Pierce',
                    'Vero Beach',
                    'Sebastian',
                    'Melbourne',
                    'Palm Bay',
                    'Titusville',
                    'Orlando',
                    'Kissimmee',
                    'Tampa',
                    'St. Petersburg',
                    'Clearwater',
                    'Naples',
                    'Marco Island',
                    'Fort Myers',
                    'Ft Myers',
                    'Ft. Myers',
                    'Cape Coral',
                    'Punta Gorda',
                    'Port Charlotte',
                    'Aventura',
                    'Bal Harbour',
                    'Bay Harbor Islands',
                    'Surfside',
                    'Brickell',
                    'Fisher Island',
                    'Hialeah',
                    'Miramar',
                    'Pembroke Pines',
                    'Plantation',
                    'Sunrise',
                    'Coral Springs',
                    'Pompano Beach',
                    'Deerfield Beach',
                    'Wellington',
                ],
                'zip_count' => count(self::getFloridaZipMap()),
                'airports' => [
                    ['code' => 'MIA', 'name' => 'Miami International Airport', 'keywords' => ['MIA', 'Miami International Airport', 'Miami Airport']],
                    ['code' => 'FLL', 'name' => 'Fort Lauderdale–Hollywood International Airport', 'keywords' => ['FLL', 'Fort Lauderdale Airport', 'Fort Lauderdale Hollywood', 'Fort Lauderdale–Hollywood', 'Fort Lauderdale-Hollywood']],
                    ['code' => 'PBI', 'name' => 'Palm Beach International Airport', 'keywords' => ['PBI', 'Palm Beach International Airport', 'Palm Beach Airport']],
                    ['code' => 'MCO', 'name' => 'Orlando International Airport', 'keywords' => ['MCO', 'Orlando International Airport', 'Orlando Airport']],
                    ['code' => 'TPA', 'name' => 'Tampa International Airport', 'keywords' => ['TPA', 'Tampa International Airport', 'Tampa Airport']],
                    ['code' => 'RSW', 'name' => 'Southwest Florida International Airport (Fort Myers)', 'keywords' => ['RSW', 'Southwest Florida International Airport', 'Fort Myers Airport']],
                    ['code' => 'OPF', 'name' => 'Miami-Opa Locka Executive Airport', 'keywords' => ['OPF', 'Opa-locka', 'Opa Locka']],
                    ['code' => 'FXE', 'name' => 'Fort Lauderdale Executive Airport', 'keywords' => ['FXE', 'Fort Lauderdale Executive']],
                    ['code' => 'BCT', 'name' => 'Boca Raton Airport', 'keywords' => ['BCT', 'Boca Raton Airport']],
                ],
            ],
            'ny_nj' => [
                'name' => 'New York & New Jersey Metro',
                'states' => ['NY', 'New York', 'NJ', 'New Jersey'],
                'cities' => [
                    'Morristown',
                    'Newark',
                    'Teterboro',
                    'Manhattan',
                    'New York City',
                    'NYC',
                    'Bronx',
                    'The Bronx',
                    'Queens',
                    'Staten Island',
                    'Brooklyn',
                    'Long Island',
                    'Nassau',
                    'Suffolk',
                    'The Hamptons',
                    'Hamptons',
                    'East Hampton',
                    'Southampton',
                    'Montauk',
                    'White Plains',
                    'Westchester',
                    'Jersey City',
                    'Hoboken',
                    'Weehawken',
                    'Secaucus',
                    'Fort Lee',
                    'Englewood',
                    'Paramus',
                    'Hackensack',
                ],
                'airports' => [
                    ['code' => 'JFK', 'name' => 'John F. Kennedy International Airport', 'keywords' => ['JFK', 'John F. Kennedy', 'John F Kennedy', 'Kennedy Airport', 'JFK Airport']],
                    ['code' => 'EWR', 'name' => 'Newark Liberty International Airport', 'keywords' => ['EWR', 'Newark Airport', 'Newark Liberty', 'Newark Liberty International Airport', 'EWR Airport']],
                    ['code' => 'LGA', 'name' => 'LaGuardia Airport', 'keywords' => ['LGA', 'LaGuardia', 'La Guardia', 'LaGuardia Airport', 'LGA Airport']],
                    ['code' => 'TEB', 'name' => 'Teterboro Executive Airport', 'keywords' => ['TEB', 'Teterboro Executive Airport', 'Teterboro Airport', 'TEB Airport']],
                    ['code' => 'HPN', 'name' => 'Westchester County Airport (White Plains)', 'keywords' => ['HPN', 'White Plains Airport', 'Westchester County Airport', 'HPN Airport']],
                    ['code' => 'ISP', 'name' => 'Long Island MacArthur Airport', 'keywords' => ['ISP', 'MacArthur Airport', 'Long Island MacArthur']],
                ],
            ],
        ];
    }

    /**
     * Convert location payload (string or array) into a searchable string.
     *
     * @param mixed $location
     * @return string
     */
    public static function extractLocationString($location)
    {
        if (empty($location)) {
            return '';
        }

        if (is_numeric($location)) {
            return (string) $location;
        }

        if (is_string($location)) {
            return trim($location);
        }

        if (is_array($location)) {
            $parts = [];
            foreach (['zip', 'postal_code', 'zipcode', 'formatted_address', 'address', 'name', 'city', 'state', 'airportCode', 'code'] as $key) {
                if (!empty($location[$key]) && (is_string($location[$key]) || is_numeric($location[$key]))) {
                    $parts[] = trim((string)$location[$key]);
                }
            }
            if (!empty($parts)) {
                return implode(', ', array_unique($parts));
            }
            return json_encode($location);
        }

        return (string) $location;
    }

    /**
     * Check if a location string falls within our allowed service areas.
     * Checks:
     *  1. Florida ZIP code match (from user's allowed ZIP list)
     *  2. Airport code & name match
     *  3. City / area name match
     *
     * @param mixed $location
     * @return array
     */
    public static function validateLocation($location)
    {
        $rawText = self::extractLocationString($location);
        if ($rawText === '') {
            return [
                'valid' => false,
                'region' => null,
                'region_key' => null,
                'matched' => null,
                'location' => '',
                'message' => Yii::t('app', 'Location is empty.'),
            ];
        }

        $flZipMap = self::getFloridaZipMap();

        // 1. Check for 5-digit ZIP code in the location string
        if (preg_match_all('/\b(\d{5})\b/', $rawText, $zipMatches)) {
            foreach ($zipMatches[1] as $zipCandidate) {
                if (isset($flZipMap[$zipCandidate])) {
                    $areaName = $flZipMap[$zipCandidate];
                    return [
                        'valid' => true,
                        'region' => 'Florida / South Florida',
                        'region_key' => 'florida',
                        'matched' => $areaName . ' (ZIP ' . $zipCandidate . ')',
                        'matched_zip' => $zipCandidate,
                        'type' => 'zip',
                        'location' => $rawText,
                        'message' => Yii::t('app', 'Location is within our Florida service area (ZIP {zip} - {area}).', [
                            'zip' => $zipCandidate,
                            'area' => $areaName,
                        ]),
                    ];
                }
            }
        }

        $zones = self::getAllowedAreas();

        // 2. Check for specific airport codes and airport names
        foreach ($zones as $zoneKey => $zone) {
            foreach ($zone['airports'] as $airport) {
                foreach ($airport['keywords'] as $kw) {
                    $pattern = '/\b' . preg_quote($kw, '/') . '\b/i';
                    if (preg_match($pattern, $rawText)) {
                        return [
                            'valid' => true,
                            'region' => $zone['name'],
                            'region_key' => $zoneKey,
                            'matched' => $airport['name'] . ' (' . $airport['code'] . ')',
                            'type' => 'airport',
                            'location' => $rawText,
                            'message' => Yii::t('app', 'Location is within our service area ({airport}).', [
                                'airport' => $airport['name'],
                            ]),
                        ];
                    }
                }
            }
        }

        // 3. Check for city / area names (strictly from the allowed list)
        foreach ($zones as $zoneKey => $zone) {
            foreach ($zone['cities'] as $city) {
                $pattern = '/\b' . preg_quote($city, '/') . '\b/i';
                if (preg_match($pattern, $rawText)) {
                    return [
                        'valid' => true,
                        'region' => $zone['name'],
                        'region_key' => $zoneKey,
                        'matched' => $city,
                        'type' => 'city',
                        'location' => $rawText,
                        'message' => Yii::t('app', 'Location is within our service area ({city}).', [
                            'city' => $city,
                        ]),
                    ];
                }
            }
        }

        return [
            'valid' => false,
            'region' => null,
            'region_key' => null,
            'matched' => null,
            'type' => null,
            'location' => $rawText,
            'message' => Yii::t('app', 'Location "{location}" is outside our supported service areas.', [
                'location' => $rawText,
            ]),
        ];
    }

    /**
     * Validates both pickup and dropoff under Option B (Strict):
     * Both Pickup and Dropoff must be inside allowed service areas.
     * Also checks that the trip stays within the same region (Florida or NY/NJ).
     *
     * @param mixed $pickup
     * @param mixed $dropoff
     * @return array
     */
    public static function validateTrip($pickup, $dropoff)
    {
        $pickupStr = self::extractLocationString($pickup);
        $dropoffStr = self::extractLocationString($dropoff);

        if (empty($pickupStr)) {
            return [
                'valid' => false,
                'message' => Yii::t('app', 'Pickup location is required.'),
                'data' => null,
            ];
        }

        if (empty($dropoffStr)) {
            return [
                'valid' => false,
                'message' => Yii::t('app', 'Dropoff location is required.'),
                'data' => null,
            ];
        }

        $pickupResult = self::validateLocation($pickup);
        $dropoffResult = self::validateLocation($dropoff);

        if (!$pickupResult['valid']) {
            return [
                'valid' => false,
                'message' => Yii::t('app', 'Pickup location "{location}" is outside our service area. We currently serve South & Central Florida and the New York / New Jersey Metro area.', [
                    'location' => $pickupStr,
                ]),
                'data' => [
                    'pickup' => $pickupResult,
                    'dropoff' => $dropoffResult,
                ],
            ];
        }

        if (!$dropoffResult['valid']) {
            return [
                'valid' => false,
                'message' => Yii::t('app', 'Dropoff location "{location}" is outside our service area. We currently serve South & Central Florida and the New York / New Jersey Metro area.', [
                    'location' => $dropoffStr,
                ]),
                'data' => [
                    'pickup' => $pickupResult,
                    'dropoff' => $dropoffResult,
                ],
            ];
        }

        // Both locations are in allowed areas. Check if they belong to the same service zone
        if ($pickupResult['region_key'] !== $dropoffResult['region_key']) {
            return [
                'valid' => false,
                'message' => Yii::t('app', 'Interstate trips between {from} and {to} are not available for online booking. Please contact us directly for long-distance charter reservations.', [
                    'from' => $pickupResult['region'],
                    'to' => $dropoffResult['region'],
                ]),
                'data' => [
                    'pickup' => $pickupResult,
                    'dropoff' => $dropoffResult,
                ],
            ];
        }

        return [
            'valid' => true,
            'message' => Yii::t('app', 'Locations are valid and within our service area.'),
            'data' => [
                'region' => $pickupResult['region'],
                'region_key' => $pickupResult['region_key'],
                'pickup' => $pickupResult,
                'dropoff' => $dropoffResult,
            ],
        ];
    }
}
