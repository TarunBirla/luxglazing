<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicWebsiteController extends Controller
{
    public function index()
    {
        $products = [
            [
                'name' => 'Ultra-Slim Sliding Doors',
                'category' => 'Sliding Systems',
                'description' => 'Floor-to-ceiling panoramic glass walls with 20mm ultra-minimal interlock sightlines and flush threshold transitions.',
                'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
                'starting_price' => 'From £4,500',
                'specs' => 'Cortizo Cor Vision Plus, U-Value 1.1 W/m²K, Acoustic laminated glass',
            ],
            [
                'name' => 'Architectural Bi-Fold Doors',
                'category' => 'Bifold Systems',
                'description' => 'Multi-panel thermally broken aluminium bifolds designed to seamlessly dissolve boundaries between living spaces and patios.',
                'image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
                'starting_price' => 'From £3,200',
                'specs' => 'Schüco / Aluk engineered, Part L & Part Q compliant, 120mm sightline',
            ],
            [
                'name' => 'Heritage Steel-Look Crittall Doors',
                'category' => 'Heritage Collection',
                'description' => 'Timeless Art Deco aesthetic with slender aluminium Georgian glazing bars, matte black hardware, and acoustic glazing.',
                'image' => 'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?auto=format&fit=crop&w=1200&q=80',
                'starting_price' => 'From £2,800',
                'specs' => 'Internal room dividers & external French doors, RAL 9005 finish',
            ],
            [
                'name' => 'Flush Casement Aluminium Windows',
                'category' => 'Windows',
                'description' => 'Sleek, contemporary exterior profiles with multi-point stainless steel security shoots and argon-filled solar control glass.',
                'image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
                'starting_price' => 'From £650 / unit',
                'specs' => 'A++ Energy rated, PAS 24 security certified, Dual colour RAL options',
            ],
            [
                'name' => 'Structural Glass Roof Lanterns',
                'category' => 'Rooflights',
                'description' => 'Maximized natural light with discreet rafter profiles and self-cleaning, heat-reflective architectural solar glass.',
                'image' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=80',
                'starting_price' => 'From £1,950',
                'specs' => 'Korniche / Atlas slimline rafters, Blue tint heat-mirror glazing',
            ],
            [
                'name' => 'Commercial Storefronts & Curtain Walling',
                'category' => 'Commercial',
                'description' => 'Engineered structural facade glazing, automatic commercial sliding doors, and fire-rated glass for UK developments.',
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                'starting_price' => 'POA / Tender',
                'specs' => 'Full architectural specification support, NBS clauses, FENSA commercial',
            ],
        ];

        return view('welcome', compact('products'));
    }
}
