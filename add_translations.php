<?php
$translations = [
    'Fashion Categories' => ['en' => 'Fashion Categories', 'rw' => 'Ibyiciro by\'Imyambaro', 'sw' => 'Aina za Mitindo'],
    'Electronics Categories' => ['en' => 'Electronics Categories', 'rw' => 'Ibyiciro bya Elegitoroniki', 'sw' => 'Aina za Elektroniki'],
    'Second Hand Categories' => ['en' => 'Second Hand Categories', 'rw' => 'Ibyiciro by\'Ibyakoreshejwe', 'sw' => 'Aina za Bidhaa Zilizotumika'],
    'Categories' => ['en' => 'Categories', 'rw' => 'Ibyiciro', 'sw' => 'Aina'],
    'All Fashion' => ['en' => 'All Fashion', 'rw' => 'Imyambaro Yose', 'sw' => 'Mitindo Yote'],
    'Clothes & Apparel' => ['en' => 'Clothes & Apparel', 'rw' => 'Imyenda', 'sw' => 'Nguo'],
    'Shoes & Footwear' => ['en' => 'Shoes & Footwear', 'rw' => 'Inkweto', 'sw' => 'Viatu'],
    'Bags & Accessories' => ['en' => 'Bags & Accessories', 'rw' => 'Amashakoshi & Imitako', 'sw' => 'Mifuko na Vifaa'],
    'All Electronics' => ['en' => 'All Electronics', 'rw' => 'Elegitoroniki Yose', 'sw' => 'Elektroniki Zote'],
    'Smartphones & Tablets' => ['en' => 'Smartphones & Tablets', 'rw' => 'Telefoni na Tabuleti', 'sw' => 'Simu Janja na Kompyuta Kibao'],
    'Laptops & Computers' => ['en' => 'Laptops & Computers', 'rw' => 'Mudasobwa (Laptops & Desktops)', 'sw' => 'Tarakilishi na Kompyuta'],
    'Smart Home Devices' => ['en' => 'Smart Home Devices', 'rw' => 'Ibikoresho by\'Ubwenge byo mu Rugu', 'sw' => 'Vifaa vya Nyumbani Janja'],
    'TV & Home Systems' => ['en' => 'TV & Home Systems', 'rw' => 'Televiziyo n\'Indangururamajwi', 'sw' => 'Runinga na Mifumo ya Nyumbani'],
    'All Used Goods' => ['en' => 'All Used Goods', 'rw' => 'Ibyakoreshejwe Byose', 'sw' => 'Bidhaa Zote Zilizotumika'],
    'Used Vehicles & Motos' => ['en' => 'Used Vehicles & Motos', 'rw' => 'Imodoka na Moto Zikoreshejwe', 'sw' => 'Magari na Pikipiki Zilizotumika'],
    'Used Mobile Phones' => ['en' => 'Used Mobile Phones', 'rw' => 'Telefoni Zikoreshejwe', 'sw' => 'Simu Zilizotumika'],
    'Used Laptops & Computers' => ['en' => 'Used Laptops & Computers', 'rw' => 'Mudasobwa Zikoreshejwe', 'sw' => 'Kompyuta Zilizotumika'],
    'Used Televisions' => ['en' => 'Used Televisions', 'rw' => 'Televiziyo Zikoreshejwe', 'sw' => 'Runinga Zilizotumika'],
    'Pre-owned Furniture' => ['en' => 'Pre-owned Furniture', 'rw' => 'Ibikoresho byo mu Nzu Byakoreshejwe', 'sw' => 'Fanicha Zilizotumika'],
    'Used Electronics' => ['en' => 'Used Electronics', 'rw' => 'Elegitoroniki Zikoreshejwe', 'sw' => 'Elektroniki Zilizotumika'],
    'All Products' => ['en' => 'All Products', 'rw' => 'Ibicuruzwa Byose', 'sw' => 'Bidhaa Zote'],
    'Farmers Market' => ['en' => 'Farmers Market', 'rw' => 'Isoko ry\'Abahinzi', 'sw' => 'Soko la Wakulima'],
    'Electronics' => ['en' => 'Electronics', 'rw' => 'Elegitoroniki', 'sw' => 'Elektroniki'],
    'Second Hand' => ['en' => 'Second Hand', 'rw' => 'Ibyakoreshejwe', 'sw' => 'Bidhaa Zilizotumika'],
    'Real Estate' => ['en' => 'Real Estate', 'rw' => 'Imitungo Itimukanwa', 'sw' => 'Mali isiyohamishika'],
    'No Products Found' => ['en' => 'No Products Found', 'rw' => 'Nta Bicuzwa Byabonetse', 'sw' => 'Hakuna Bidhaa Zilizopatikana'],
    'We couldn\'t find any products matching your selection. Try adjusting your search query or category filters.' => [
        'en' => 'We couldn\'t find any products matching your selection. Try adjusting your search query or category filters.', 
        'rw' => 'Nta bicuruzwa twabonye bihuye n\'ibyo mwashatse. Gerageza guhindura amagambo mwashakishije cyangwa ibyiciro.', 
        'sw' => 'Hatujaweza kupata bidhaa zozote kulingana na uchaguzi wako. Jaribu kurekebisha utafutaji wako au vichungi vya aina.'
    ],
    'Clear Filters' => ['en' => 'Clear Filters', 'rw' => 'Siba Gushungura', 'sw' => 'Ondoa Vichungi'],
    'items found' => ['en' => 'items found', 'rw' => 'ibyabonetse', 'sw' => 'zilizopatikana'],
    'Newest Arrivals' => ['en' => 'Newest Arrivals', 'rw' => 'Ibigezweho (Bishya)', 'sw' => 'Bidhaa Mpya'],
    'Price: Low to High' => ['en' => 'Price: Low to High', 'rw' => 'Igiciro: Gito kugeza Kinini', 'sw' => 'Bei: Chini kwenda Juu'],
    'Price: High to Low' => ['en' => 'Price: High to Low', 'rw' => 'Igiciro: Kinini kugeza Gito', 'sw' => 'Bei: Juu kwenda Chini'],
    'Independent Seller' => ['en' => 'Independent Seller', 'rw' => 'Umucuruzi Wigenga', 'sw' => 'Muuzaji wa Kujitegemea']
];

$langs = ['en', 'rw', 'sw'];
foreach ($langs as $lang) {
    $file = __DIR__ . '/lang/' . $lang . '.json';
    if (!file_exists($file)) continue;
    
    $current = json_decode(file_get_contents($file), true) ?: [];
    foreach ($translations as $key => $values) {
        $current[$key] = $values[$lang];
    }
    file_put_contents($file, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
echo "Translations added successfully!\n";
