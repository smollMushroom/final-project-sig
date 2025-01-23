<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function getProvincesGeojson()
    {
        $provinces = Province::all();
        $geojson = [
            "type" => "FeatureCollection",
            "name" => "all_provinces_indonesia",
            "crs" => [
                "type" => "name",
                "properties" => [
                    "name" => "urn:ogc:def:crs:OGC:1.3:CRS84"
                ]
            ],
            "features" => []
        ];

        foreach ($provinces as $province) {
            $geojson['features'][] = [
                "type" => "Feature",
                "properties" => [
                    "id" => $province->id,
                    "name" => $province->name,
                    "total_visitors" => $province->total_visitors,
                    "labor_wages_avg" => $province->labor_wages_avg,
                    "total_SD" => $province->total_SD,
                    "total_SMP" => $province->total_SMP,
                    "total_SMA" => $province->total_SMA,
                    "total_SMK" => $province->total_SMK,
                ],
                "geometry" => [
                    "type" => "MultiPolygon",
                    "coordinates" => json_decode($province->coordinates)
                ]
                ];
        }

        return $geojson;
    }

    // public function visitorsView() {
    //     $data = [
    //         "geojson" => $this->getProvincesGeojson(),
    //         "title" => "Peta Total Pengunjung Pada Tahun 2024",
    //         "minValueColor" => 0,
    //         "maxValueColor" => 10000000
    //     ];
    //     return view('visitors', compact('data'));
    // }

    public function thematicView() {
        $geojson = $this->getProvincesGeojson();
        return view('thematic', compact('geojson'));
    }
}
