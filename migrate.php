<?php
$bladePath = 'C:\xampp\htdocs\trust_rwanda_laravel\resources\views\store\real_estate.blade.php';
$c = file_get_contents($bladePath);

// Prepend the translations array
$translations = <<<EOT
@php
\$currentLang = app()->getLocale();
\$reLang = [
    'rw' => [
        'title' => 'Gushakisha Inzu n\'Ibibanza mu Rwanda',
        'placeholder_search' => 'Shakisha Kibagabaga, Nyarutarama, Rebero...',
        'any_status' => 'Icyiciro Byose',
        'for_rent' => 'Ibikodeshwa',
        'for_sale' => 'Ibigurishwa',
        'any_type' => 'Ubwoko Byose',
        'any_price' => 'Ibiciro Byose',
        'price_under_500k' => 'Munsi y\'ibihumbi 500 RWF',
        'price_500k_2m' => '500k - 2M RWF',
        'price_2m_10m' => '2M - 10M RWF',
        'price_10m_plus' => 'Hejuruy\'i 10M RWF',
        'any_beds' => 'Ibyumba Byose',
        'any_baths' => 'Ubwiherero Byose',
        'btn_map' => 'Aho ndi',
        'view_details' => 'Reba Ibisobanuro',
        'beds_label' => 'Ibyumba',
        'baths_label' => 'Ubwiherero',
        'sqm_label' => 'sqm',
        'verified' => 'Byemejwe',
        'list_cta' => 'Ufite Inzu cyangwa Ikibanza?',
        'list_sub' => 'Kora lisiti y\'imitungo yawe kuri Trust Rwanda maze uziheze ku bakiriya benshi ku buntu!',
        'list_btn' => 'Kwiyandikisha nk\'Umunyamutungo',
        'no_results' => 'Nta mitungo yabonetse ihuje n\'ibyo mwashakishije.',
        'show_grid' => 'Imitungo Yose',
        'show_map' => 'Reba ku Ikarita'
    ],
    'en' => [
        'title' => 'Trust Rwanda Premium Real Estate',
        'placeholder_search' => 'Search by Kibagabaga, Nyarutarama, Rebero, Kiyovu...',
        'any_status' => 'Any Status',
        'for_rent' => 'For Rent',
        'for_sale' => 'For Sale',
        'any_type' => 'Any Type',
        'any_price' => 'Any Price',
        'price_under_500k' => 'Under 500k RWF',
        'price_500k_2m' => '500k - 2M RWF',
        'price_2m_10m' => '2M - 10M RWF',
        'price_10m_plus' => 'Above 10M RWF',
        'any_beds' => 'Any Beds',
        'any_baths' => 'Any Baths',
        'btn_map' => 'Locate Area',
        'view_details' => 'View Details',
        'beds_label' => 'Beds',
        'baths_label' => 'Baths',
        'sqm_label' => 'sqm',
        'verified' => 'Verified',
        'list_cta' => 'Own a House or Land?',
        'list_sub' => 'Partner with Trust Rwanda and list your properties to reach thousands of buyers for free!',
        'list_btn' => 'Become a Property Partner',
        'no_results' => 'No properties found matching your criteria.'
    ],
    'sw' => [
        'title' => 'Kutafuta Nyumba na Ardhi Rwanda',
        'placeholder_search' => 'Tafuta kwa Kibagabaga, Nyarutarama, Rebero...',
        'any_status' => 'Hali Yoyote',
        'for_rent' => 'Ya Kupanga',
        'for_sale' => 'Ya Kuuza',
        'any_type' => 'Aina Yoyote',
        'any_price' => 'Bei Yoyote',
        'price_under_500k' => 'Chini ya 500k RWF',
        'price_500k_2m' => '500k - 2M RWF',
        'price_2m_10m' => '2M - 10M RWF',
        'price_10m_plus' => 'Zaidi ya 10M RWF',
        'any_beds' => 'Vyumba Vyote',
        'any_baths' => 'Bafu Zote',
        'btn_map' => 'Tafuta Eneo',
        'view_details' => 'Angalia Maelezo',
        'beds_label' => 'Vyumba',
        'baths_label' => 'Bafu',
        'sqm_label' => 'sqm',
        'verified' => 'Imethibitishwa',
        'list_cta' => 'Je, unamiliki nyumba au ardhi?',
        'list_sub' => 'Weka orodha yako kwenye Trust Rwanda sasa na ufikie maelfu ya wateja bure kabisa!',
        'list_btn' => 'Jisajili kama Mshirika',
        'no_results' => 'Hakuna mali iliyopatikana kulingana na vigezo vyako.'
    ]
];
\$t = \$reLang[\$currentLang] ?? \$reLang['en'];
@endphp
EOT;

$c = str_replace("@section('content')", "@section('content')\n" . $translations, $c);

// Perform replacements
$c = preg_replace('/<\?php echo htmlspecialchars\(\$t\[\'(.*?)\'\]\); \?>/', '{{ $t[\'$1\'] }}', $c);
$c = str_replace('<?php echo $baseUrl; ?>/index.php?route=property_owner_register', '{{ route(\'property_owner.register\') }}', $c);
$c = preg_replace('/<\?php echo json_encode\(array_values\(\$propertiesList\)\) \?: \'\[\]\'; \?>/', '@json(array_values($propertiesList))', $c);
$c = preg_replace('/<\?php echo json_encode\(\$t\); \?>/', '@json($t)', $c);
$c = preg_replace('/<\?php echo \$(lat|lng); \?>/', '{{ $$1 }}', $c);
$c = preg_replace('/<\?php require_once.*?footer\.php\'; \?>/', '', $c);

file_put_contents($bladePath, $c);
echo "Migration successful.";
